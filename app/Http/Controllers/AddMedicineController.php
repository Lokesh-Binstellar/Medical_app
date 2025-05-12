<?php

namespace App\Http\Controllers;

use App\Models\Carts;
use App\Models\Customers;
use App\Models\Medicine;
use App\Models\Otcmedicine;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JWTAuth;
use Str;

class AddMedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd('index');

        return view('medicine.addMedicine.index');
    }


    public function searchMedicines(Request $request)
    {
        $query = $request->input('query');

        // Search in medicines table using product_id
        $medicines = Medicine::where('product_name', 'like', "%{$query}%")
            ->orWhere('salt_composition', 'like', "%{$query}%")
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->product_id, // use product_id instead of id
                    'text' => "{$item->product_name} + {$item->salt_composition}",
                    'type' => 'prescription',
                ];
            });

        // Search in otcmedicines table using otc_id
        $otcmedicines = Otcmedicine::where('name', 'like', "%{$query}%")
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->otc_id, // use otc_id instead of id
                    'text' => $item->name,
                    'type' => 'otc',
                ];
            });

        // Combine both
        $results = $medicines->concat($otcmedicines)->values();

        return response()->json([
            'results' => $results
        ]);
    }

    public function prescriptionSelect(Request $request)
    {
        $search = $request->input('query');

        // Get prescriptions that match the search query (e.g., based on mobile_no or firstName)
        $prescriptions = Prescription::with('customers')
            ->where('prescription_status', 0)
            ->where('status', 1)
            ->whereHas('customers', function ($query) use ($search) {
                $query->where('mobile_no', 'like', "%{$search}%")
                    ->orWhere('firstName', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get()
            ->map(function ($prescription) {
                return [
                    'id' => $prescription->id,
                    'text' => 'Prescription #' . $prescription->id . ' - ' .
                        $prescription->customers->firstName . ' (' .
                        $prescription->customers->mobile_no . ')',
                ];
            });

        return response()->json(['results' => $prescriptions]);
    }


    public function getMedicineStrip(Request $request)
    {
        $id = $request->input('id');

        try {
            if (!$id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Medicine ID is required.'
                ], 400);
            }

            // Try to find in prescription medicines first
            $medicine = Medicine::where('product_id', $id)->first();
            if ($medicine) {
                return response()->json([
                    'status' => true,
                    'packaging_detail' => $medicine->packaging_detail ?? ''
                ]);
            }

            // If not found, try OTC medicines
            $otcMedicine = Otcmedicine::where('otc_id', $id)->first();
            if ($otcMedicine) {
                return response()->json([
                    'status' => true,
                    'packaging_detail' => $otcMedicine->packaging ?? ''
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Medicine not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }






    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'prescription_id' => 'required',
            'medicine.*.medicine_id' => 'required',
            'medicine.*.packaging_detail' => 'required',
            'medicine.*.quantity' => 'required',
            'medicine.*.is_substitute' => 'required',
        ]);

        $prescriptionId = $request['prescription_id'];

        // Get prescription
        $prescription = Prescription::find($prescriptionId);
        if (!$prescription) {
            return redirect()->back()->with('error', 'Prescription not found.');
        }

        $customerId = $prescription->customer_id;

        // Prepare new products from request
        $incomingProducts = $validated['medicine'];

        // Check existing cart
        $cart = DB::table('carts')->where('customer_id', $customerId)->first();

        if ($cart) {
            $existingProducts = json_decode($cart->products_details, true) ?? [];

            // Get existing product_ids to avoid duplicates
            $existingProductIds = array_column($existingProducts, 'product_id');

            $mergedProducts = $existingProducts;

            foreach ($incomingProducts as $row) {
                if (!in_array($row['medicine_id'], $existingProductIds)) {
                    $mergedProducts[] = [
                        'product_id' => $row['medicine_id'],
                        'packaging_detail' => $row['packaging_detail'],
                        'quantity' => $row['quantity'],
                        'is_substitute' => $row['is_substitute'],
                    ];
                }
            }

            // Merge prescription IDs too
            $existingPrescriptions = json_decode($cart->prescription_id, true) ?? [];
            $updatedPrescriptions = array_unique(array_merge($existingPrescriptions, [$prescriptionId]));

            // Update cart
            DB::table('carts')->where('id', $cart->id)->update([
                'products_details' => json_encode($mergedProducts),
                'prescription_id' => json_encode($updatedPrescriptions),
                'updated_at' => now(),
            ]);
        } else {
            // Create new cart
            $productsToInsert = [];

            foreach ($incomingProducts as $row) {
                $productsToInsert[] = [
                    'product_id' => $row['medicine_id'],
                    'packaging_detail' => $row['packaging_detail'],
                    'quantity' => $row['quantity'],
                    'is_substitute' => $row['is_substitute'],
                ];
            }

            DB::table('carts')->insert([
                'customer_id' => $customerId,
                'prescription_id' => json_encode([$prescriptionId]),
                'products_details' => json_encode($productsToInsert),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Update prescription status
        DB::table('prescriptions')->where('id', $prescriptionId)->update([
            'status' => 0,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Products added to cart successfully (skipping duplicates).');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }
    public function getAddToCart(Request $request)
    {
        $id = $request->get('user_id');

        try {
            $carts = DB::table('carts')
                ->where('customer_id', $id)
                ->orderByDesc('created_at')
                ->get();

            if ($carts->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No cart records found for customer ID ' . $id
                ], 404);
            }

            $result = $carts->map(function ($cart) {
                $productDetails = json_decode($cart->products_details, true);
                $detailedProducts = [];

                if (is_array($productDetails)) {
                    foreach ($productDetails as $product) {
                        $productId = $product['product_id'] ?? null;
                        if (!$productId)
                            continue;

                        $type = null;
                        $medicine = \App\Models\Medicine::where('product_id', $productId)->first();

                        if ($medicine) {
                            $type = 'medicine';
                        } else {
                            $medicine = \App\Models\Otcmedicine::where('otc_id', $productId)->first();
                            if ($medicine) {
                                $type = 'otc';
                            }
                        }

                        if ($medicine && $type) {
                            $name = $type === 'medicine'
                                ? ($medicine->product_name ?? '')
                                : ($medicine->name ?? '');

                            $packageDetail = $product['packaging_detail'] ?? $medicine->packaging ?? $medicine->packaging_detail ?? '';
                            $quantity = $product['quantity'] ?? $medicine->qty ?? 1;

                            $imageUrls = [];
                            if (!empty($medicine->image_url)) {
                                $images = is_array($medicine->image_url)
                                    ? $medicine->image_url
                                    : (json_decode($medicine->image_url, true) ?: explode(',', $medicine->image_url));

                                $imageUrls = array_map(function ($img) {
                                    $img = trim($img);
                                    return Str::startsWith($img, 'medicines/')
                                        ? asset('storage/' . $img)
                                        : asset('storage/medicines/' . $img);
                                }, $images);
                            }

                            $detailedProducts[] = [
                                "product_id" => $type === 'medicine' ? $medicine->product_id : $medicine->otc_id,
                                'type' => $type,
                                'name' => $name,
                                'prescription_required' => ($medicine->prescription_required === 'Prescription Required'),
                                'packaging_detail' => $packageDetail,
                                'quantity' => $quantity,
                                'is_substitute' => $product['is_substitute'] ?? 'no',
                                'image_url' => $imageUrls,
                            ];
                        }
                    }
                }

                return [
                    'id' => $cart->id,
                    'customer_id' => $cart->customer_id,
                    'products_details' => $detailedProducts,
                    'created_at' => \Carbon\Carbon::parse($cart->created_at)->toDateTimeString(),
                ];
            });

            return response()->json([
                'status' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }




    public function removeProduct($cartId, $productId)
    {

        $cart = DB::table('carts')->where('id', $cartId)->first();

        if (!$cart) {
            return response()->json([
                'status' => false,
                'message' => 'Cart not found.'
            ], 404);
        }

        $products = json_decode($cart->products_details, true);

        // Filter out the product
        $updatedProducts = array_filter($products, function ($product) use ($productId) {
            return $product['product_id'] != $productId;
        });

        // Reindex the array
        $updatedProducts = array_values($updatedProducts);

        DB::table('carts')->where('id', $cartId)->update([
            'products_details' => json_encode($updatedProducts),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Product removed successfully.',
            'data' => $updatedProducts
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function frontendAddToCart(Request $request)
    {
        $userId = $request->get('user_id');

        $productId = $request->get('product_id');
        $quantity = $request->get('quantity');

        // Validate product_id
        if (!$productId) {
            return response()->json([
                'status' => false,
                'message' => 'Product ID is required'
            ], 400);
        }

        // Validate quantity
        if (!$quantity || !is_numeric($quantity) || $quantity <= 0) {
            return response()->json([
                'status' => false,
                'message' => 'Quantity must be a valid number greater than 0'
            ], 400);
        }

        // Check if customer exists
        $customer = Customers::find($userId);
        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Customer not found'
            ], 404);
        }

        // Check which table the product belongs to and get packaging_detail
        $packagingDetail = null;

        $otcProduct = \App\Models\Otcmedicine::where('otc_id', $productId)->first();
        if ($otcProduct) {
            $packagingDetail = $otcProduct->packaging;
        } else {
            $medProduct = \App\Models\Medicine::where('product_id', $productId)->first();
            if ($medProduct) {
                $packagingDetail = $medProduct->packaging_detail;
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Product not found in both otcmedicines and medicines'
                ], 404);
            }
        }

        // Find or create cart
        $cart = Carts::where('customer_id', $userId)->first();
        if (!$cart) {
            $cart = Carts::create([
                'customer_id' => $userId,
                "prescription_id" => "",
                'products_details' => json_encode([]),
            ]);
        }

        $currentProducts = json_decode($cart->products_details, true) ?? [];

        $found = false;
        foreach ($currentProducts as &$item) {
            if ($item['product_id'] == $productId) {
                // If found, update quantity & packaging
                $item['quantity'] += $quantity;
                $item['packaging_detail'] = $packagingDetail;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $currentProducts[] = [
                'product_id' => $productId,
                'packaging_detail' => $packagingDetail,
                'quantity' => $quantity,
                'is_substitute' => 'no'
            ];
        }

        $cart->products_details = json_encode($currentProducts);
        $cart->save();

        return response()->json([
            'status' => true,
            'message' => 'Product added to cart successfully'
        ]);
    }




    public function fetchCustomerCart(Request $request)
    {
        $prescriptionId = $request->input('prescription_id');

        // Step 1: Get prescription with customer_id
        $prescription = Prescription::find($prescriptionId);
        if (!$prescription) {
            return response()->json(['status' => 'error', 'message' => 'Prescription not found']);
        }

        $customerId = $prescription->customer_id;

        // Step 2: Find Cart using customer_id
        $cart = Carts::where('customer_id', $customerId)->first();
        if (!$cart || !$cart->products_details) {
            return response()->json(['status' => 'error', 'message' => 'No cart found']);
        }

        // Step 3: Decode product_details JSON
        $products = json_decode($cart->products_details, true);

        return response()->json(['status' => 'success', 'data' => $products]);
    }



    public function deleteCartProduct(Request $request)
    {
        $prescriptionId = $request->input('prescription_id');
        $productId = $request->input('product_id');

        $prescription = Prescription::find($prescriptionId);
        if (!$prescription) {
            return response()->json(['status' => 'error', 'message' => 'Prescription not found']);
        }

        $customerId = $prescription->customer_id;
        $cart = Carts::where('customer_id', $customerId)->first();

        if (!$cart || !$cart->products_details) {
            return response()->json(['status' => 'error', 'message' => 'Cart not found']);
        }

        $products = json_decode($cart->products_details, true);
        $updatedProducts = array_filter($products, function ($product) use ($productId) {
            return $product['product_id'] != $productId;
        });

        $cart->products_details = json_encode(array_values($updatedProducts));
        $cart->save();

        return response()->json(['status' => 'success']);
    }




    //prescritption files
    public function fetchPrescriptionFile(Request $request)
    {
        $prescriptionId = $request->input('prescription_id');

        $prescription = Prescription::find($prescriptionId);

        if (!$prescription || !$prescription->prescription_file) {
            return response()->json([
                'status' => 'error',
                'message' => 'Prescription file not found'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'file_url' => asset('storage/' . $prescription->prescription_file)
        ]);
    }
}
