<?php

namespace App\Http\Controllers;

use App\Events\MyEvent;
use App\Events\Pharmacymedicine;
use App\Models\Carts;
use App\Models\Additionalcharges;
use App\Models\CustomerAddress;
use App\Models\Customers;
use App\Models\DeliveryPerson;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\Otcmedicine;
use App\Models\Patient;
use App\Models\Pharmacies;
use App\Models\Phrmacymedicine;
use App\Models\Prescription;
use App\Models\RequestQuote;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\DataTables;

class MedicineSearchController extends Controller
{
    public function index()
    {
        $pharmacy = Pharmacies::where('user_id', Auth::user()->id)->first();

        $medicines = Phrmacymedicine::where('phrmacy_id', Auth::user()->id)->get();

        return view('pharmacist.add-medicine', compact('medicines'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $user = Auth::user();

        // Get pharmacy info
        $pharmacy = Pharmacies::where('user_id', $user->id)->first();

        $totalQuantity = 0;
        $substituteMedicines = [];

        if (isset($data['medicine']) && is_array($data['medicine'])) {
            foreach ($data['medicine'] as $medicineItem) {
                $totalQuantity += intval($medicineItem['quantity'] ?? 0);

                // Convert substitute to object with medicine_name
                if (!empty($medicineItem['substitute_medicine']) && is_string($medicineItem['substitute_medicine'])) {
                    $substituteMedicines[] = [
                        'medicine_name' => $medicineItem['substitute_medicine']
                    ];
                }
            }
        }


        $medicine = new Phrmacymedicine();
        $medicine->medicine = json_encode($data['medicine']);
        //$medicine->quantity = $totalQuantity;
        //$medicine->substitute_medicines = json_encode($substituteMedicines);

        $medicine->total_amount = $data['total_amount'] ?? 0;
        $medicine->mrp_amount = $data['mrp_amount'] ?? 0;
        $medicine->commission_amount = $data['commission_amount'] ?? 0;
        $medicine->phrmacy_id = $pharmacy->user_id;
        $medicine->customer_id = $data['customer'][0]['customer_id'] ?? null;
        $medicine->save();

        DB::table('request_quotes')
            ->where('customer_id', $data['customer'][0]['customer_id'] ?? 0)
            ->where('pharmacy_id', $pharmacy->user_id)
            ->update(['pharmacy_address_status' => 1]);

        return redirect()->back()->with('success', 'Medicine added successfully!');
    }


    public function search(Request $request)
    {
        $term = $request->get('q');

        $medicines = DB::table('medicines')
            ->select('product_id', 'product_name', 'salt_composition')  // select product_id instead of id
            ->when($term, function ($query, $term) {
                return $query->where('product_name', 'like', "%$term%")
                    ->orWhere('salt_composition', 'like', "%$term%");
            })
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->product_id,  // use product_id here
                    'text' => $item->product_name . ' + ' . $item->salt_composition,
                ];
            });

        $otc = DB::table('otcmedicines')
            ->select('otc_id', 'name')  // select otc_id instead of id
            ->when($term, function ($query, $term) {
                return $query->where('name', 'like', "%$term%");
            })
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->otc_id,  // use otc_id here
                    'text' => $item->name,
                ];
            });

        return response()->json($medicines->merge($otc));
    }

    public function customerSelect(Request $request)
    {
        $search = $request->input('query');
        $currentPharmacyId = $request->input('current_pharmacy_id');

        $customerIds = DB::table('request_quotes')
            ->where('pharmacy_id', $currentPharmacyId)
            ->where('pharmacy_address_status', 0)
            ->pluck('customer_id');

        $customers = DB::table('customers')
            ->whereIn('id', $customerIds)
            ->select('id', 'firstName', 'mobile_no') // Include 'id' here
            ->get();

        // Format for Select2: id + text
        $results = $customers->map(function ($customer) {
            return [
                'id' => $customer->id,
                'text' => $customer->firstName . ' (' . $customer->mobile_no . ')',
            ];
        });

        $firstCustomerId = $customers->first()?->id;
        $product = null;

        if ($firstCustomerId) {
            $cart = Carts::where('customer_id', $firstCustomerId)->first();
            if ($cart && $cart->products_details) {
                $product = json_decode($cart->products_details, true);
            }
        }

        return response()->json([
            'results' => $results,
            'product' => $product
        ]);
    }

    public function allPharmacyRequests(Request $request)
    {
        // echo "okk";die;
        $currentCustomer = $request->get('user_id');  // e.g. 2

        $getMedicine = Phrmacymedicine::with('pharmacy')
            ->whereHas('pharmacy', function ($query) use ($currentCustomer) {
                $query->where('customer_id', $currentCustomer);
            })
            ->get();

        try {
            $combinations = $getMedicine->map(function ($item) {
                return [
                    'customer_id' => $item->customer_id,
                    'pharmacy_id' => $item->phrmacy_id,
                ];
            });

            $quoteAddresses = RequestQuote::where(function ($query) use ($combinations) {
                foreach ($combinations as $combo) {
                    $query->orWhere(function ($q) use ($combo) {
                        $q->where('customer_id', $combo['customer_id'])
                            ->where('pharmacy_id', $combo['pharmacy_id']);
                    });
                }
            })->get()->mapWithKeys(function ($quote) {
                $address = json_decode($quote->customer_address, true);
                $key = $quote->customer_id . '_' . $quote->pharmacy_id;

                return [
                    $key => [
                        'type' => $address['type'] ?? null,
                        'lat' => trim($address['lat'] ?? ''),
                        'lng' => trim($address['lng'] ?? ''),
                    ]
                ];
            });

            $apiKey = env('GOOGLE_MAPS_API_KEY');
            $userId = $request->get('user_id');

            $carts = Carts::where('customer_id', $userId)->get()->flatMap(function ($cart) {
                try {
                    return json_decode($cart->products_details, true);
                } catch (\Exception $e) {
                    return [];
                }
            });
            $cartQuantities = collect($carts)->mapWithKeys(function ($item) {
                return [$item['product_id'] => $item['quantity']];
            });

            $grouped = $getMedicine->groupBy('phrmacy_id')->map(function ($group, $pharmacyId) use ($cartQuantities, $quoteAddresses, $apiKey, $userId) {
                $pharmacy = $group->first()->pharmacy;
                $customerId = $group->first()->customer_id;
                $key = $customerId . '_' . $pharmacyId;
                $customerAddress = $quoteAddresses[$key] ?? null;
                $distance = null;
                if ($customerAddress && $pharmacy && $pharmacy->latitude && $pharmacy->longitude) {
                    $distanceValue = $this->getRoadDistance(
                        $customerAddress['lat'],
                        $customerAddress['lng'],
                        $pharmacy->latitude,
                        $pharmacy->longitude,
                        $apiKey
                    );
                    $distance = $distanceValue !== false ? round($distanceValue, 2) . ' km' : 'Unknown';
                }

                $decodedMedicines = $group->flatMap(function ($item) use ($cartQuantities) {
                    try {
                        $decoded = is_string($item->medicine)
                            ? json_decode($item->medicine, true)
                            : $item->medicine;

                        $decodedArray = is_array($decoded) ? $decoded : [$decoded];

                        return collect($decodedArray)->map(function ($med) use ($cartQuantities) {
                            $medId = $med['medicine_id'];


                            $image = Medicine::where('product_id', $medId)->value('image_url');
                            if (!$image) {
                                $image = Otcmedicine::where('otc_id', $medId)->value('image_url');
                            }

                            $med['image'] = $image ? asset('storage/' . $image) : null;

                            $medicine = Medicine::where('product_id', $medId)->first();

                            $prescription = 'No';
                            if ($medicine && $medicine->prescription_required === 'Prescription Required') {
                                $prescription = 'Yes';
                            }


                            $med['qty'] = $cartQuantities[$medId] ?? 0;
                            $med['price'] = $med['discount'] ?? 0;
                            unset($med['discount']);

                            return [
                                'medicine_id' => $med['medicine_id'] ?? null,
                                'medicine_name' => $med['medicine_name'] ?? null,
                                'qty' => isset($med['qty']) ? (int) $med['qty'] : 0,
                                'available' => $med['available'] ?? null,
                                'is_substitute' => $med['is_substitute'] ?? null,
                                'image' => $med['image'] ?? null,
                                'prescription' => $prescription,
                                'mrp' => isset($med['mrp']) ? (float) $med['mrp'] : null,
                                'price' => isset($med['price']) ? (float) $med['price'] : null,
                                'discount_percent' => isset($med['discount_percent']) ? (float) $med['discount_percent'] : null,
                            ];
                        });
                    } catch (\Exception $e) {
                        return [['error' => 'Invalid JSON']];
                    }
                });


                $delivery_available = 'no';

                $zipCode = DB::table('customer_addresses')
                    ->where('customer_id', $userId)
                    ->where('address_type', $customerAddress['type'])
                    ->value('postal_code');

                if ($zipCode) {
                    $zipExists = DB::table('zip_code_vice_deliveries')
                        ->where('zipcode', $zipCode)
                        ->exists();

                    if ($zipExists) {
                        $delivery_available = 'yes';
                    }
                }


                $delivery_charge = null;

                $distance = floatval($distance);

                if ($distance > 0 && $distance <= 5) {
                    $delivery_charge = 30;
                } elseif ($distance > 5) {
                    $delivery_charge = 50;
                } else {
                    // Handle case where distance is 0 or invalid
                    $delivery_charge = null; // or any default value you want
                }

                $rating = null;

                $ratings = DB::table('ratings')
                    ->where('rateable_id', $pharmacyId)
                    ->where('rateable_type', 'Pharmacy')
                    ->pluck('rating');

                $totalRatings = $ratings->count();

                if ($totalRatings > 0) {
                    $average = round($ratings->avg(), 1);
                    $rating = $average . " ($totalRatings)";
                }

                $discount = $group->sum('mrp_amount') - $group->sum('total_amount');

                $platfromfee = Additionalcharges::value('platfrom_fee') ?? 0;

                return [
                    'pharmacy_id' => $pharmacyId,
                    'pharmacy_name' => $pharmacy->pharmacy_name ?? 'Unknown',
                    'pharmacy_address' => $pharmacy->address ?? 'Unknown',
                    'medicines' => $decodedMedicines->values(),
                    'mrp_amount' => $group->sum('mrp_amount'),
                    'item_price' => $group->sum('total_amount'),
                    'total_discount' => $group->sum('mrp_amount') > 0 ? round(($discount / $group->sum('mrp_amount')) * 100, 2) : 0,
                    'platform_fees' => $platfromfee,
                    'total_price' => $group->sum('total_amount') + $platfromfee,
                    'rating' => $rating,
                    'distance' => $distance ?? 'Unknown',
                    'delivery_available' => $delivery_available,
                    'delivery_charge' => $delivery_charge
                ];
            })->values();

            return response()->json([
                'status' => true,
                'data' => $grouped
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getRoadDistance($lat1, $lon1, $lat2, $lon2, $apiKey)
    {

        $url = "https://maps.googleapis.com/maps/api/directions/json?origin=$lat1,$lon1&destination=$lat2,$lon2&key=$apiKey";

        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if ($data['status'] === 'OK') {
            $distanceMeters = $data['routes'][0]['legs'][0]['distance']['value'];
            return $distanceMeters / 1000; // return in KM
        }

        return false;
    }

    public function fetchCartByCustomer(Request $request)
    {
        $customerId = $request->input('customer_id');
        $current_pharmacy_id = $request->input('current_pharmacy_id');
        $cart = RequestQuote::where('customer_id', $customerId)
            ->where('pharmacy_id', $current_pharmacy_id)
            ->first();

        if ($cart) {
            $quoteTime = \Carbon\Carbon::parse($cart->created_at);
            $now = \Carbon\Carbon::now();

            // Show message if more than 15 minutes old
            if ($now->diffInMinutes($quoteTime) > 15) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Request quote exceeded 15 minutes'
                ]);
            }
        }



        $products = json_decode($cart->products_details, true);
        $result = [];

        foreach ($products as $item) {
            $productId = $item['product_id'];
            $isSubstitute = $item['is_substitute'] ?? 0;
            $packagingDetail = $item['packaging_detail'] ?? '';
            $quantity = $item['quantity'] ?? 1;

            // ✅ First try to find in medicines table
            $medicine = Medicine::where('product_id', $productId)->first();
            $medName = null;
            $type = 'medicine';

            if ($medicine) {
                // ✅ Check if product_name and salt_composition exist before concatenating
                $productName = $medicine->product_name ?? 'Unknown Product';
                $saltComposition = $medicine->salt_composition ?? '';
                $medName = $saltComposition ? $productName . ' + ' . $saltComposition : $productName;
            } else {
                // ✅ If not found in medicines, try otcmedicines
                $otcMedicine = Otcmedicine::where('otc_id', $productId)->first();
                if ($otcMedicine) {
                    $medName = $otcMedicine->name ?? 'Unknown OTC Medicine';
                    $type = 'otc';
                }
            }

            // ✅ Only add to result if we found a valid medicine
            if ($medName) {
                $result[] = [
                    'product_id' => $productId,
                    'type' => $type,
                    'name' => $medName,
                    'packaging_detail' => $packagingDetail,
                    'quantity' => $quantity,
                    'is_substitute' => $isSubstitute,
                ];
            } else {
                // ✅ Log or handle products that don't exist in either table
                \Log::warning("Product not found in medicines or otcmedicines tables", [
                    'product_id' => $productId,
                    'customer_id' => $customerId
                ]);

                // ✅ Optionally, you can still add it with a placeholder name
                $result[] = [
                    'product_id' => $productId,
                    'type' => 'unknown',
                    'name' => 'Product Not Found (ID: ' . $productId . ')',
                    'packaging_detail' => $packagingDetail,
                    'quantity' => $quantity,
                    'is_substitute' => $isSubstitute,
                ];
            }
        }

        return response()->json(['status' => 'success', 'data' => $result]);
    }

    public function fetchPrescriptionFiles(Request $request)
    {
        // Step 1: Get the customer_id from the request
        $customerId = $request->input('customer_id');

        if (!$customerId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer ID is required'
            ]);
        }

        // Step 2: Check if customer_id exists in request_quotes table
        $requestQuoteExists = DB::table('request_quotes')->where('customer_id', $customerId)->first();
        // dd($requestQuoteExists);
        if (!$requestQuoteExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'No request quote found for this customer'
            ]);
        }

        // Step 3: Fetch all prescriptions for this customer_id
        $prescriptions = Prescription::where('customer_id', $customerId)
            ->where('status', 0)
            ->get();

        if ($prescriptions->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No prescriptions found for this customer'
            ]);
        }

        $fileUrls = [];

        // Step 4: Loop through prescriptions and get all files
        foreach ($prescriptions as $prescription) {
            if ($prescription->prescription_file) {
                $files = explode(',', $prescription->prescription_file);
                foreach ($files as $file) {
                    $file = trim($file);
                    if (!empty($file)) {
                        $fileUrls[] = asset('uploads/' . $file);
                    }
                }
            }
        }

        // Step 5: Return result
        if (empty($fileUrls)) {
            return response()->json([
                'status' => 'error',
                'message' => 'No files found for this customer'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'files' => $fileUrls
        ]);
    }


    public function orderdetails(Request $request)
    {



        if ($request->ajax()) {
            $roleName = Auth::user()->role->name;
            $query = Order::with(['customer', 'pharmacy', 'deliveryPerson']);

            if ($request->filled('order_date')) {
                $query->whereDate('created_at', $request->order_date);
            }

            if ($roleName === 'admin') {
                $orders = $query->get();
            } elseif ($roleName === 'pharmacy') {
                $orders = $query->where('pharmacy_id', Auth::id())->get();
            } elseif ($roleName === 'laboratory') {
                $orders = $query->where('lab_id', Auth::id())->get();
            } elseif ($roleName === 'delivery_person') {
                $orders = $query->where('delivery_person_id', Auth::id())->get();
            } else {
                $orders = collect();
            }

            $deliveryPersons = User::whereHas('role', function ($q) {
                $q->where('name', 'delivery_person');
            })->with('deliveryProfile')->get();

            return DataTables::of($orders)
                ->addColumn('date_formatted', function ($order) {
                    if (!$order->created_at)
                        return 'N/A';

                    return $order->created_at->format('d M Y h:i A');
                })
                ->addColumn('date_raw', function ($order) {
                    if (!$order->created_at)
                        return null;

                    return $order->created_at->toDateTimeString(); // e.g. "2025-06-05 15:45:00"
                })


                ->addColumn('customer_name', function ($order) {
                    if (!$order->customer) {
                        return 'N/A';
                    }

                    $fullName = $order->customer->firstName . ' ' . $order->customer->lastName;
                    $phone = $order->customer->mobile_no;

                    return '<div style="white-space: normal; word-wrap: break-word; max-width: 150px;">'
                        . e($fullName) . ' (' . e($phone) . ')'
                        . '</div>';
                })
                ->rawColumns(['customer_name']) // <-- important to render the HTML


                // ->addColumn('total_price', function ($order) {
                //     return '₹' . number_format($order->total_price, 2);
                // })

                ->addColumn(
                    'payment_mode',
                    fn($order) =>
                    $order->payment_option
                    ? ucwords(str_replace('_', ' ', $order->payment_option))
                    : 'N/A'
                )
                ->addColumn(
                    'delivery_method',
                    fn($order) =>
                    $order->delivery_options
                    ? ucwords(str_replace('_', ' ', $order->delivery_options))
                    : 'N/A'
                )

                // ->addColumn('delivery_person', function ($order) {
                //     return $order->deliveryPerson?->name ?? 'Unassigned';
                // })
                ->addColumn('status', function ($order) {
                    switch ($order->status) {
                        case 0:
                            return '<span class="badge bg-warning">Request Accepted</span>';
                        case 1:
                            return '<span class="badge bg-success">Completed</span>';
                        case 2:
                            $reason = $order->cancel_by ? '<br><small class="text-danger">Cancelled By: ' . e($order->cancel_by) . '</small>' : '';
                            return '<span class="badge bg-danger">Cancelled</span>' . $reason;
                        case 3:
                            return '<span class="badge bg-info">Returned</span>';
                        default:
                            return '<span class="badge bg-secondary">Unknown</span>';
                    }
                })



                ->addColumn('action', function ($order) {
                    return '
                <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
                <a href="' . route('orders.medicines', $order->id) . '" 
                class="btn btn-sm btn-primary control me-2">
                    <i class="mdi mdi-eye"></i> View
                </a>
                </div>';
                })
                ->addColumn('assign_delivery', function ($order) use ($deliveryPersons) {
                    if (Auth::user()->role->name === 'admin') {
                        if ($order->delivery_options === 'home_delivery' && $order->status == 0) {
                            $html = '<form action="' . route('orders.assignDeliveryPerson') . '" method="POST">';
                            $html .= '<input type="hidden" name="order_id" value="' . $order->id . '">';
                            $html .= csrf_field();
                            $html .= '<div class="d-flex align-items-center">';
                            $html .= '<select name="delivery_person_id" class="form-select form-select-sm me-2 fw-bold text-black border border-dark" required>';
                            $html .= '<option value=""> - Select Delivery Person - </option>';

                            foreach ($deliveryPersons as $person) {
                                $selected = $order->delivery_person_id == $person->id ? 'selected' : '';
                                $html .= '<option value="' . $person->id . '" ' . $selected . '>' . $person->name . '</option>';
                            }

                            $html .= '</select>';
                            $html .= '<button type="submit" class="btn btn-sm btn-primary">Assign</button>';
                            $html .= '</div></form>';

                            return $html;
                        } else {
                            if ($order->status == 1) {
                                return '<span class="badge bg-success">Completed</span>';
                            } elseif ($order->status == 2) {
                                return '<span class="badge bg-danger">Order is Cancelled</span>';
                            } elseif ($order->status == 3) {
                                return '<span class="badge bg-info">Order is Returned</span>';
                            }
                            else {
                                return ucfirst(str_replace('_', ' ', $order->delivery_options));
                            }
                        }
                    }
                    return ''; // or 'N/A' for other roles
                })
                ->addColumn('status_control', function ($order) {
                    $html = '<div class="text-center">';

                    if ($order->status == 0) {
                        $html .= '<form action="' . route('pharmacy.updateOrderStatus', $order->id) . '" method="POST" class="d-inline-block status-form">';
                        $html .= csrf_field();
                        $html .= method_field('PUT');

                        $html .= '<select name="status" class="form-select form-select-sm me-2 fw-bold text-black border border-dark status-select" data-role="' . auth()->user()->role->name . '">';
                        $html .= '<option value="">-- Update Status --</option>';

                        if (auth()->user()->role->name === 'delivery_person') {
                            $html .= '<option value="1">Complete</option>';
                        } else {
                            $html .= '<option value="1">Complete</option>';
                            $html .= '<option value="2">Cancel</option>';
                        }

                        $html .= '</select>';
                        $html .= '<input type="hidden" name="cancel_by" class="cancel-by-input" value="">';
                        $html .= '</form>';
                    } else {
                        if ($order->status == 1) {
                            $html .= '<span class="badge bg-success">Delivered to Customer</span>';
                        } elseif ($order->status == 2) {
                            $html .= '<span class="badge bg-danger">Order Cancelled</span>';
                        } elseif ($order->status == 3) {
                            $html .= '<span class="badge bg-info">Order Returned</span>';
                        }
                    }

                    $html .= '</div>';
                    return $html;
                })

                ->addColumn('invoice', function ($order) {
                    $url = route('invoice.download', $order->order_id);

                    return '<div class="text-center align-middle">
                    <a href="' . $url . '" class="btn btn-success" title="Download Invoice">
                    <i class="tf-icons mdi mdi-download"></i>
                    </a>
                    </div>';
                })
                ->addColumn('delivery_info', function ($order) {
                    if (!$order->delivery_person_id)
                        return '';

                    return '<a href="' . route('delivery.showDeliveryInfo', [$order->delivery_person_id, $order->order_id]) . '" 
                            class="btn btn-sm btn-primary" 
                            title="View Delivery Info">
                            <i class="mdi mdi-truck-fast me-1"></i> View Delivery Info
                        </a>';
                })



                ->rawColumns(['delivery_person', 'action', 'status', 'date', 'assign_delivery', 'customer_name', 'status_control', 'invoice', 'delivery_info'])
                ->make(true);
        }

        return view('pharmacist.orderdetails');
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:1,2',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;

        // If order is being cancelled (status = 2)
        if ($request->status == 2) {
            // Determine who is cancelling
            $userRole = auth()->user()->role->name ?? 'admin';

            // Set cancel_by based on role
            $order->cancel_by = in_array($userRole, ['admin', 'pharmacy'])
                ? $userRole
                : 'customer';
        }

        $order->save();

        return back()->with('success', 'Order status updated successfully.');
    }


    public function showMedicines($id)
    {
        // Load order and customer
        $order = Order::with('customer')->findOrFail($id);

        // Decode medicines from product_details JSON
        $medicines = json_decode($order->product_details, true);

        // Load patient details if ID is available
        $patient = null;
        if ($order->add_patient) {
            $patient = Patient::find($order->add_patient);
        }

        return view('pharmacist.medicine_details', compact('order', 'medicines', 'patient'));
    }

    public function assignDeliveryPerson(Request $request, Order $order)
    {
        // $request->validate([
        //     'delivery_person_id' => 'required|exists:users,id',
        // ]);

        // $order->delivery_person_id = $request->delivery_person_id;
        // $order->save();

        // return back()->with('success', 'Delivery person assigned successfully.');

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'delivery_person_id' => 'nullable|exists:users,id'
        ]);

        $order = Order::find($request->order_id);
        $order->delivery_person_id = $request->delivery_person_id;
        $order->save();

        return back()->with('success', 'Delivery person assigned successfully.');
    }

    public function downloadInvoice($id)
    {
        $order = Order::where('order_id', $id)
            ->with(['customer', 'pharmacy'])
            ->firstOrFail();

        $pdf = Pdf::setOptions([
            'defaultFont' => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ])->loadView('invoice.invoice', compact('order'));

        return $pdf->download("invoice-{$order->order_number}.pdf");
    }

    public function saveInvoice($orderId)
    {
        $order = Order::where('order_id', $orderId)
            ->with(['customer', 'pharmacy'])
            ->firstOrFail();

        $pdf = Pdf::setOptions([
            'defaultFont' => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ])->loadView('invoice.invoice', compact('order'));

        $fileName = "invoice-{$order->order_id}.pdf";
        $publicPath = public_path('invoices');

        // Create directory if it doesn't exist
        if (!File::exists($publicPath)) {
            File::makeDirectory($publicPath, 0755, true);
        }

        $filePath = "{$publicPath}/{$fileName}";
        file_put_contents($filePath, $pdf->output());

        return [
            'file_name' => $fileName,
            'file_path' => $filePath,
            'url' => asset("invoices/{$fileName}")
        ];
    }

    public function showDeliveryInfo($id, $orderId)
    {
        // Get delivery person using user_id
        $deliveryPersons = DeliveryPerson::with('user')->where('user_id', $id)->firstOrFail();

        // Get the latest or specific order for this delivery person (optional: customize based on your logic)
        $order = Order::where('order_id', $orderId)->firstOrFail();

        return view('delivery.info', compact('deliveryPersons', 'order'));
    }




    public function fetchSubstituteBySalt(Request $request)
    {
        $input = $request->query('salt');

        if (!$input) {
            return response()->json(['results' => []]);
        }

        // Extract salt after '+', if present
        $salt = $input;
        if (strpos($input, '+') !== false) {
            $parts = explode('+', $input);
            $salt = trim($parts[1]);
        }

        $currentProductId = $request->query('selectedMedicineId');
        //dd($request);
        $results = [];
        // Query medicines with salt_composition LIKE $salt
        $medicines = Medicine::where('salt_composition', 'LIKE', '%' . $salt . '%')->get();

        foreach ($medicines as $m) {
            // Skip the current product
            if ($m->product_id === $currentProductId) {
                continue;
            }

            $results[] = [
                'id' => $m->product_name . ' + ' . $m->salt_composition,
                'text' => $m->product_name . ' + ' . $m->salt_composition,
            ];
        }

        // For OTC medicines, no salt filtering (or just skip)
        // You can either skip or fetch all OTCs if you want:
        /*
    $otcs = Otcmedicine::all();
    foreach ($otcs as $o) {
        $results[] = [
            'id' => 'otc_' . $o->otc_id,
            'text' => $o->name,
        ];
    }
    */

        return response()->json(['results' => $results]);
    }



    public function getSalt(Request $request)
    {
        $medicineId = $request->query('medicine_id');

        \Log::info('getSalt called', ['medicine_id' => $medicineId]);

        $medicine = Medicine::where('product_id', $medicineId)->first();
        if ($medicine) {
            \Log::info('Medicine found', ['salt_composition' => $medicine->salt_composition]);
            return response()->json(['salt' => $medicine->salt_composition]);
        }

        $otc = Otcmedicine::where('otc_id', $medicineId)->first();
        if ($otc) {
            \Log::info('OTC medicine found, but no salt_composition field available');
            // Assuming OTC medicine does not have salt_composition
            return response()->json(['salt' => null]);
        }

        \Log::warning('Medicine or OTC not found for ID', ['medicine_id' => $medicineId]);

        return response()->json(['salt' => null]);
    }

    public function returnorderdetails(Request $request){
         if ($request->ajax()) {
            $roleName = Auth::user()->role->name;
            $query = Order::with(['customer', 'pharmacy', 'deliveryPerson'])
                     ->where('status', 3); // Only return orders with status 3

            if ($request->filled('order_date')) {
                $query->whereDate('created_at', $request->order_date);
            }

            if ($roleName === 'admin') {
                $orders = $query->get();
            } elseif ($roleName === 'pharmacy') {
                $orders = $query->where('pharmacy_id', Auth::id())->get();
            } elseif ($roleName === 'laboratory') {
                $orders = $query->where('lab_id', Auth::id())->get();
            } elseif ($roleName === 'delivery_person') {
                $orders = $query->where('delivery_person_id', Auth::id())->get();
            } else {
                $orders = collect();
            }

            $deliveryPersons = User::whereHas('role', function ($q) {
                $q->where('name', 'delivery_person');
            })->with('deliveryProfile')->get();

            return DataTables::of($orders)
                ->addColumn('date_formatted', function ($order) {
                    if (!$order->created_at)
                        return 'N/A';

                    return $order->created_at->format('d M Y h:i A');
                })
                ->addColumn('date_raw', function ($order) {
                    if (!$order->created_at)
                        return null;

                    return $order->created_at->toDateTimeString(); // e.g. "2025-06-05 15:45:00"
                })


                ->addColumn('customer_name', function ($order) {
                    if (!$order->customer) {
                        return 'N/A';
                    }

                    $fullName = $order->customer->firstName . ' ' . $order->customer->lastName;
                    $phone = $order->customer->mobile_no;

                    return '<div style="white-space: normal; word-wrap: break-word; max-width: 150px;">'
                        . e($fullName) . ' (' . e($phone) . ')'
                        . '</div>';
                })
                ->rawColumns(['customer_name']) // <-- important to render the HTML


                // ->addColumn('total_price', function ($order) {
                //     return '₹' . number_format($order->total_price, 2);
                // })

                ->addColumn(
                    'payment_mode',
                    fn($order) =>
                    $order->payment_option
                    ? ucwords(str_replace('_', ' ', $order->payment_option))
                    : 'N/A'
                )
                ->addColumn(
                    'delivery_method',
                    fn($order) =>
                    $order->delivery_options
                    ? ucwords(str_replace('_', ' ', $order->delivery_options))
                    : 'N/A'
                )

                // ->addColumn('delivery_person', function ($order) {
                //     return $order->deliveryPerson?->name ?? 'Unassigned';
                // })
                ->addColumn('status', function ($order) {
                    switch ($order->status) {
                        case 0:
                            return '<span class="badge bg-warning">Request Accepted</span>';
                        case 1:
                            return '<span class="badge bg-success">Completed</span>';
                        case 2:
                            $reason = $order->cancel_by ? '<br><small class="text-danger">Cancelled By: ' . e($order->cancel_by) . '</small>' : '';
                            return '<span class="badge bg-danger">Cancelled</span>' . $reason;
                        case 3:
                            return '<span class="badge bg-info">Returned</span>';
                        default:
                            return '<span class="badge bg-secondary">Unknown</span>';
                    }
                })

                ->addColumn('status_control', function ($order) {
                    $html = '<div class="text-center">';

                    if ($order->status == 0) {
                        $html .= '<form action="' . route('pharmacy.updateOrderStatus', $order->id) . '" method="POST" class="d-inline-block status-form">';
                        $html .= csrf_field();
                        $html .= method_field('PUT');

                        $html .= '<select name="status" class="form-select form-select-sm me-2 fw-bold text-black border border-dark status-select" data-role="' . auth()->user()->role->name . '">';
                        $html .= '<option value="">-- Update Status --</option>';

                        if (auth()->user()->role->name === 'delivery_person') {
                            $html .= '<option value="1">Complete</option>';
                        } else {
                            $html .= '<option value="1">Complete</option>';
                            $html .= '<option value="2">Cancel</option>';
                        }

                        $html .= '</select>';
                        $html .= '<input type="hidden" name="cancel_by" class="cancel-by-input" value="">';
                        $html .= '</form>';
                    } else {
                        if ($order->status == 1) {
                            $html .= '<span class="badge bg-success">Delivered to Customer</span>';
                        } elseif ($order->status == 2) {
                            $html .= '<span class="badge bg-danger">Order Cancelled</span>';
                        } elseif ($order->status == 3) {
                            $html .= '<span class="badge bg-info">Order Returned</span>';
                        }
                    }

                    $html .= '</div>';
                    return $html;
                })

                ->rawColumns(['status', 'date', 'customer_name', 'status_control'])
                ->make(true);
        }
        return view('pharmacist.returnorderdetails');
    }
}
