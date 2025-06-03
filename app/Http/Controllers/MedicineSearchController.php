<?php

namespace App\Http\Controllers;

use App\Events\MyEvent;
use App\Events\Pharmacymedicine;
use App\Models\Carts;
use App\Models\Additionalcharges;
use App\Models\CustomerAddress;
use App\Models\Customers;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\Otcmedicine;
use App\Models\Pharmacies;
use App\Models\Phrmacymedicine;
use App\Models\Prescription;
use App\Models\RequestQuote;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        // ✅ Calculate total quantity from all medicine entries
        $totalQuantity = 0;
        if (isset($data['medicine']) && is_array($data['medicine'])) {
            foreach ($data['medicine'] as $medicine) {
                $totalQuantity += intval($medicine['quantity'] ?? 0);
            }
        }

        // Save medicine
        $medicine = new Phrmacymedicine();
        $medicine->medicine = json_encode($data['medicine']);
        //$medicine->quantity = $totalQuantity; // ✅ Use calculated total quantity
        // $medicine->quantity = $totalQuantity; 
        $medicine->total_amount = $data['total_amount'] ?? 0; // ✅ Add fallback
        $medicine->mrp_amount = $data['mrp_amount'] ?? 0; // ✅ Add fallback
        $medicine->commission_amount = $data['commission_amount'] ?? 0; // ✅ Add fallback
        $medicine->phrmacy_id = $pharmacy->user_id;
        $medicine->customer_id = $data['customer'][0]['customer_id'];
        $medicine->save();

        // ✅ Update request_quotes only if both customer_id and pharmacy_id match
        DB::table('request_quotes')
            ->where('customer_id', $data['customer'][0]['customer_id'])
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

        $cart = Carts::where('customer_id', $customerId)->first();

        if (!$cart || !$cart->products_details) {
            return response()->json(['status' => 'error', 'message' => 'Cart not found']);
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
        $pharmacy = Pharmacies::where('user_id', Auth::user()->id)->first();

        $medicines = Phrmacymedicine::where('phrmacy_id', Auth::user()->id)->get();

        // Fetch orders where pharmacy_id matches current pharmacy
        //$orders = Order::where('pharmacy_id', Auth::user()->id)->get();

        $orders = Order::with('customer')->where('pharmacy_id', Auth::user()->id)->get();
        $roleName = Auth::user()->role->name;


        if ($roleName === 'admin') {
            // Admin: Get all orders
            $orders = Order::with('customer')->get();
        } elseif ($roleName === 'pharmacy') {
            // Pharmacy: Get orders for that pharmacy
            $orders = Order::with('customer')
                ->where('pharmacy_id', Auth::id())
                ->get();
        } elseif ($roleName === 'laboratory') {
            // Laboratory: Get orders for that lab
            $orders = Order::with('customer')
                ->where('lab_id', Auth::id())
                ->get();
        } elseif ($roleName === 'delivery_person') {
            // Delivery Person: Get only assigned orders
            $orders = Order::with('customer')
                ->where('delivery_person_id', Auth::id())
                ->get();
        } else {
            // Unknown or unauthorized role: return empty
            $orders = collect();
        }


        // $deliveryPersons = User::whereHas('role', function ($q) {
        //     $q->where('name', 'delivery_person');
        // })->get();

        $deliveryPersons = User::with('deliveryProfile')->whereHas('role', function ($q) {
            $q->where('name', 'delivery_person');
        })->get();



        return view('pharmacist.orderdetails', compact('medicines', 'pharmacy', 'orders', 'deliveryPersons'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:1,2',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return back()->with('success', 'Order status updated successfully.');
    }

    public function showMedicines($id)
    {
        $order = Order::with('customer')->findOrFail($id);
        $medicines = json_decode($order->product_details, true);

        return view('pharmacist.medicine_details', compact('order', 'medicines'));
    }

    public function assignDeliveryPerson(Request $request, Order $order)
    {
        $request->validate([
            'delivery_person_id' => 'required|exists:users,id',
        ]);

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



}