<?php

namespace App\Http\Controllers;

use App\Models\Carts;
use App\Models\Customers;
use App\Models\Janaushadhi;
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

        // Search in medicines table
        $medicines = Medicine::where('product_name', 'like', "%{$query}%")
            ->orWhere('salt_composition', 'like', "%{$query}%")
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->product_id,
                    'text' => "{$item->product_name} + {$item->salt_composition}",
                    'type' => 'prescription',
                ];
            });

        // Search in otcmedicines table
        $otcmedicines = Otcmedicine::where('name', 'like', "%{$query}%")
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->otc_id,
                    'text' => $item->name,
                    'type' => 'otc',
                ];
            });

        // Search in janaushadhi table using generic_name and group_name
        $janaushadhi = Janaushadhi::where('generic_name', 'like', "%{$query}%")
            ->orWhere('group_name', 'like', "%{$query}%")
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->drug_code,
                    'text' => "{$item->generic_name} ({$item->group_name})",
                    'type' => 'janaushadhi',
                ];
            });

        // Combine all results
        $results = $medicines
            ->concat($otcmedicines)
            ->concat($janaushadhi)
            ->values();

        return response()->json([
            'results' => $results,
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
                $query->where('mobile_no', 'like', "%{$search}%")->orWhere('firstName', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get()
            ->map(function ($prescription) {
                return [
                    'id' => $prescription->id,
                    'text' => 'Prescription #' . $prescription->id . ' - ' . $prescription->customers->firstName . ' (' . $prescription->customers->mobile_no . ')',
                ];
            });

        return response()->json(['results' => $prescriptions]);
    }

    public function getMedicineStrip(Request $request)
    {
        $id = $request->input('id');

        try {
            if (!$id) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Medicine ID is required.',
                    ],
                    400,
                );
            }

            // Try to find in prescription medicines first
            $medicine = Medicine::where('product_id', $id)->first();
            if ($medicine) {
                return response()->json([
                    'status' => true,
                    'packaging_detail' => $medicine->packaging_detail ?? '',
                ]);
            }

            // If not found, try OTC medicines
            $otcMedicine = Otcmedicine::where('otc_id', $id)->first();
            if ($otcMedicine) {
                return response()->json([
                    'status' => true,
                    'packaging_detail' => $otcMedicine->packaging ?? '',
                ]);
            }

            // Check in Janaushadhi medicines
            $janaushadhi = Janaushadhi::where('drug_code', $id)->first();
            if ($janaushadhi) {
                return response()->json([
                    'status' => true,
                    'packaging_detail' => $janaushadhi->unit_size ?? '',
                ]);
            }

            return response()->json(
                [
                    'status' => false,
                    'message' => 'Medicine not found',
                ],
                404,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Something went wrong',
                    'error' => $e->getMessage(),
                ],
                500,
            );
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
            'medicine.*.quantity' => 'required|numeric',
            'medicine.*.is_substitute' => 'required',
        ]);

        $prescriptionId = $request['prescription_id'];

        $prescription = Prescription::find($prescriptionId);
        if (!$prescription) {
            return redirect()->back()->with('error', 'Prescription not found.');
        }

        $customerId = $prescription->customer_id;
        $incomingProducts = $validated['medicine'];

        $incomingHasJanaushadhi = false;
        $incomingHasOtcOrMed = false;

        foreach ($incomingProducts as $row) {
            $id = $row['medicine_id'];

            if (\App\Models\Janaushadhi::where('drug_code', $id)->exists()) {
                $incomingHasJanaushadhi = true;
            } elseif (
                \App\Models\Medicine::where('product_id', $id)->exists() ||
                \App\Models\Otcmedicine::where('otc_id', $id)->exists()
            ) {
                $incomingHasOtcOrMed = true;
            }
        }

        $cart = Carts::where('customer_id', $customerId)->first();

        if ($cart) {
            $existingProducts = json_decode($cart->products_details, true) ?? [];
            $existingProductIds = array_column($existingProducts, 'product_id');

            $existingHasJanaushadhi = false;
            $existingHasOtcOrMed = false;

            foreach ($existingProductIds as $productId) {
                if (\App\Models\Janaushadhi::where('drug_code', $productId)->exists()) {
                    $existingHasJanaushadhi = true;
                } elseif (
                    \App\Models\Medicine::where('product_id', $productId)->exists() ||
                    \App\Models\Otcmedicine::where('otc_id', $productId)->exists()
                ) {
                    $existingHasOtcOrMed = true;
                }
            }

            // Conflict validation during cart update
            if ($existingHasJanaushadhi && $incomingHasOtcOrMed) {
                return redirect()->back()->with('error', 'You cannot add OTC or general medicines while Janaushadhi items are in the cart. Please clear your cart first.');
            }

            if ($existingHasOtcOrMed && $incomingHasJanaushadhi) {
                return redirect()->back()->with('error', 'You cannot add Janaushadhi medicines while OTC or general medicines are in the cart. Please clear your cart first.');
            }

            $mergedProducts = $existingProducts;

            foreach ($incomingProducts as $row) {
                if (!in_array($row['medicine_id'], $existingProductIds)) {
                    $mergedProducts[] = [
                        'product_id' => $row['medicine_id'],
                        'packaging_detail' => $row['packaging_detail'],
                        'quantity' => (int) $row['quantity'],
                        'is_substitute' => $row['is_substitute'],
                    ];
                }
            }

            $mergedProducts = array_map(function ($item) {
                $item['quantity'] = (int) $item['quantity'];
                return $item;
            }, $mergedProducts);

            $existingPrescriptions = json_decode($cart->prescription_id, true) ?? [];
            $updatedPrescriptions = array_unique(array_merge($existingPrescriptions, [$prescriptionId]));

            $cart->products_details = json_encode($mergedProducts);
            $cart->prescription_id = json_encode($updatedPrescriptions);
            $cart->updated_at = now();
            $cart->save();
        } else {
            // Conflict validation when cart is empty (creating for first time)
            if ($incomingHasJanaushadhi && $incomingHasOtcOrMed) {
                return redirect()->back()->with('error', 'You cannot mix Janaushadhi and OTC/general medicines. Please separate them into different prescriptions.');
            }

            $productsToInsert = [];

            foreach ($incomingProducts as $row) {
                $productsToInsert[] = [
                    'product_id' => $row['medicine_id'],
                    'packaging_detail' => $row['packaging_detail'],
                    'quantity' => (int) $row['quantity'],
                    'is_substitute' => $row['is_substitute'],
                ];
            }

            Carts::create([
                'customer_id' => $customerId,
                'prescription_id' => json_encode([$prescriptionId]),
                'products_details' => json_encode($productsToInsert),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Prescription::where('id', $prescriptionId)->update([
            'status' => 0,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Products added to cart successfully');
    }



    public function getAddToCart(Request $request)
    {
        $id = $request->get('user_id');

        try {
            $cart = DB::table('carts')->where('customer_id', $id)->orderByDesc('created_at')->first();

            if (!$cart) {
                return response()->json(
                    [
                        'status' => true,
                        'data' => [],
                    ],
                    200
                );
            }

            $productDetails = json_decode($cart->products_details, true);
            $detailedProducts = [];

            if (is_array($productDetails)) {
                foreach ($productDetails as $product) {
                    $productId = $product['product_id'] ?? null;
                    $quantity = $product['quantity'] ?? 1;
                    $isSubstitute = $product['is_substitute'] ?? 'no';

                    if (!$productId) {
                        continue;
                    }

                    $type = null;
                    $medicine = \App\Models\Medicine::where('product_id', $productId)->first();

                    if ($medicine) {
                        $type = 'medicine';
                    } else {
                        $medicine = \App\Models\Otcmedicine::where('otc_id', $productId)->first();
                        if ($medicine) {
                            $type = 'otc';
                        } else {
                            $medicine = \App\Models\Janaushadhi::where('drug_code', $productId)->first();
                            if ($medicine) {
                                $type = 'janaushadhi';
                            }
                        }
                    }

                    if ($medicine && $type) {
                        if ($type === 'medicine' || $type === 'otc') {
                            $name = $type === 'medicine' ? $medicine->product_name ?? '' : $medicine->name ?? '';
                            $packaging = $product['packaging_detail'] ?? ($medicine->packaging ?? ($medicine->packaging_detail ?? ''));
                            $prescriptionRequired = $medicine->prescription_required === 'Prescription Required';

                            $baseUrl = url('medicines');
                            $defaultImage = "{$baseUrl}/placeholder.png";
                            $imageUrls = [$defaultImage];

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
                                'product_id' => $type === 'medicine' ? $medicine->product_id : $medicine->otc_id,
                                'type' => $type,
                                'name' => $name,
                                'prescription_required' => $prescriptionRequired,
                                'packaging_detail' => $packaging,
                                'quantity' => $quantity,
                                'is_substitute' => $isSubstitute,
                                'image_url' => $imageUrls,
                            ];
                        } elseif ($type === 'janaushadhi') {
                            $name = $medicine->generic_name ?? '';
                            $packaging = $medicine->unit_size ?? '';
                            $prescriptionRequired = false;

                            $detailedProducts[] = [
                                'product_id' => $medicine->drug_code,
                                'type' => 'janaushadhi',
                                'name' => $name,
                                'prescription_required' => $prescriptionRequired,
                                'packaging_detail' => $packaging,
                                'quantity' => $quantity,
                                'is_substitute' => $isSubstitute,
                                'mrp' => $medicine->mrp,
                            ];
                        }
                    }
                }
            }

            $cartObject = [
                'id' => $cart->id,
                'customer_id' => $cart->customer_id,
                'products_details' => $detailedProducts,
            ];

            return response()->json([
                'status' => true,
                'data' => (object) $cartObject,
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Something went wrong.',
                    'error' => $e->getMessage(),
                ],
                500
            );
        }
    }


    public function removeProduct($cartId, $productId)
    {
        $cart = DB::table('carts')->where('id', $cartId)->first();

        if (!$cart) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Cart not found.',
                ],
                404,
            );
        }

        $products = json_decode($cart->products_details, true);

        // Filter out the product
        $updatedProducts = array_filter($products, function ($product) use ($productId) {
            return $product['product_id'] != $productId;
        });

        // Reindex the array
        $updatedProducts = array_values($updatedProducts);

        DB::table('carts')
            ->where('id', $cartId)
            ->update([
                'products_details' => json_encode($updatedProducts),
                'updated_at' => now(),
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Product removed successfully.',
            'data' => $updatedProducts,
        ]);
    }

    public function removeCartProduct(Request $request, $id)
    {
        $userId = $request->get('user_id');

        try {
            if (!$userId) {
                return response()->json(['status' => false, 'message' => 'Unauthorized.'], 401);
            }

            $cart = Carts::where('customer_id', $userId)->first();

            if (!$cart) {
                return response()->json(['status' => false, 'message' => 'Cart not found.'], 404);
            }

            $productDetails = json_decode($cart->products_details ?: '[]', true);

            if (empty($productDetails)) {
                return response()->json(['status' => false, 'message' => 'No products in cart.']);
            }

            $originalCount = count($productDetails);

            $filtered = collect($productDetails)
                ->filter(function ($item) use ($id) {
                    return isset($item['product_id']) && $item['product_id'] != $id;
                })
                ->values()
                ->all();

            if (count($filtered) === $originalCount) {
                // No change = product not found
                return response()->json(['status' => false, 'message' => 'Product not found in cart.']);
            }

            $cart->products_details = json_encode($filtered);
            $cart->save();

            return response()->json(['status' => true, 'message' => 'Product removed from cart.']);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Something went wrong.',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
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
                'message' => 'Product ID is required',
            ], 400);
        }

        // Validate quantity
        if (!$quantity || !is_numeric($quantity) || $quantity <= 0) {
            return response()->json([
                'status' => false,
                'message' => 'Quantity must be a valid number greater than 0',
            ], 400);
        }

        // Check if customer exists
        $customer = Customers::find($userId);
        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Customer not found',
            ], 404);
        }

        // Identify product type and packaging detail
        $productType = null;
        $packagingDetail = null;

        $otcProduct = \App\Models\Otcmedicine::where('otc_id', $productId)->first();
        if ($otcProduct) {
            $productType = 'otc';
            $packagingDetail = $otcProduct->packaging;
        } else {
            $medProduct = \App\Models\Medicine::where('product_id', $productId)->first();
            if ($medProduct) {
                $productType = 'med';
                $packagingDetail = $medProduct->packaging_detail;
            } else {
                $janaushadhi = \App\Models\Janaushadhi::where('drug_code', (int) $productId)->first();
                if ($janaushadhi) {
                    $productType = 'janaushadhi';
                    $packagingDetail = $janaushadhi->unit_size;
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Product not found in otcmedicines, medicines, or janaushadhi',
                    ], 404);
                }
            }
        }

        // Find or create cart
        $cart = Carts::where('customer_id', $userId)->first();
        if (!$cart) {
            $cart = Carts::create([
                'customer_id' => $userId,
                'prescription_id' => '',
                'products_details' => json_encode([]),
            ]);
        }

        $currentProducts = json_decode($cart->products_details, true) ?? [];

        // Rule enforcement: restrict janaushadhi mix
        $hasJanaushadhi = false;
        $hasOtcOrMed = false;

        foreach ($currentProducts as $item) {
            $pid = $item['product_id'];

            if (\App\Models\Janaushadhi::where('drug_code', (int) $pid)->exists()) {
                $hasJanaushadhi = true;
            } elseif (
                \App\Models\Otcmedicine::where('otc_id', $pid)->exists() ||
                \App\Models\Medicine::where('product_id', $pid)->exists()
            ) {
                $hasOtcOrMed = true;
            }
        }

        if ($hasJanaushadhi && ($productType === 'otc' || $productType === 'med')) {
            return response()->json([
                'status' => false,
                'message' => 'You cannot add OTC or other general medicines to the cart while it contains Janaushadhi items. Please clear your cart first.',
            ], 400);
        }

        if ($hasOtcOrMed && $productType === 'janaushadhi') {
            return response()->json([
                'status' => false,
                'message' => 'Janaushadhi medicines cannot be added to your cart while it contains OTC or other medicines. Please clear your cart before proceeding.',
            ], 400);
        }

        // Add or update product in cart
        $found = false;
        foreach ($currentProducts as &$item) {
            if ($item['product_id'] == $productId) {
                $item['quantity'] = $quantity;
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
                'is_substitute' => 'no',
            ];
        }

        $cart->products_details = json_encode($currentProducts);
        $cart->save();

        return response()->json([
            'status' => true,
            'message' => 'Product added to cart successfully',
        ]);
    }

    public function replaceCart(Request $request)
    {

        $userId = $request->get('user_id');
        // dd($userId);
        $newProducts = $request->get('products');

        $finalCart = [];
        $typesInCart = [];

        foreach ($newProducts as $item) {
            $productId = $item['product_id'];
            $quantity = $item['quantity'];
            $productType = null;
            $packagingDetail = null;

            if ($janaushadhi = \App\Models\Janaushadhi::where('drug_code', $productId)->first()) {
                $productType = 'janaushadhi';
                $packagingDetail = $janaushadhi->unit_size;
                $name = $janaushadhi->name;
            } elseif ($med = \App\Models\Medicine::where('product_id', $productId)->first()) {
                $productType = 'med';
                $packagingDetail = $med->packaging_detail;
                $name = $med->product_name . ' + ' . $med->salt_composition;
            } elseif ($otc = \App\Models\Otcmedicine::where('otc_id', $productId)->first()) {
                $productType = 'otc';
                $packagingDetail = $otc->packaging;
                $name = $otc->name;
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "Invalid product_id: {$productId}",
                ], 400);
            }

            $typesInCart[] = $productType;

            $finalCart[] = [
                'product_id' => $productId,
                'packaging_detail' => $packagingDetail,
                'quantity' => $quantity,
                'is_substitute' => 'no',
                'type' => $productType,
                'name' => $name,
            ];
        }

        // ❌ Restrict Janaushadhi + OTC/Med mix
        $typesInCart = array_unique($typesInCart);
        if (in_array('janaushadhi', $typesInCart) && (in_array('med', $typesInCart) || in_array('otc', $typesInCart))) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot mix Janaushadhi with OTC or general medicines. Please keep only one category.',
            ], 400);
        }

        // 🛒 Update or Create Cart
        $cart = Carts::updateOrCreate(
            ['customer_id' => $userId],
            [
                'products_details' => json_encode($finalCart),
                'prescription_id' => '', // optional
                'updated_at' => now(),
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Cart replaced successfully',
            'data' => [
                'id' => $cart->id,
                'customer_id' => $userId,
                'products_details' => $finalCart
            ]
        ]);
    }



    public function fetchCustomerCart(Request $request)
    {
        $prescriptionId = $request->input('prescription_id');

        $prescription = Prescription::find($prescriptionId);
        if (!$prescription) {
            return response()->json(['status' => 'error', 'message' => 'Prescription not found']);
        }

        $customerId = $prescription->customer_id;

        $cart = Carts::where('customer_id', $customerId)->first();
        if (!$cart || !$cart->products_details) {
            return response()->json(['status' => 'error', 'message' => 'No cart found']);
        }

        $products = json_decode($cart->products_details, true);
        $result = [];

        foreach ($products as $item) {
            $productId = $item['product_id'];
            $isSubstitute = $item['is_substitute'] ?? 0;
            $packagingDetail = $item['packaging_detail'] ?? '';
            $quantity = $item['quantity'] ?? 1;

            $type = null;
            $medName = null;

            // Check general medicine
            $medicine = Medicine::where('product_id', $productId)->first();
            if ($medicine) {
                $medName = $medicine->product_name . ' + ' . $medicine->salt_composition;
                $type = 'medicine';
            }

            // Check OTC if not found
            if (!$medicine) {
                $medicine = Otcmedicine::where('otc_id', $productId)->first();
                if ($medicine) {
                    $medName = $medicine->name;
                    $type = 'otc';
                }
            }

            // Check Janaushadhi if still not found
            if (!$medicine) {
                $medicine = Janaushadhi::where('drug_code', $productId)->first();
                if ($medicine) {
                    $medName = $medicine->generic_name;
                    $type = 'janaushadhi';
                }
            }

            if ($medicine) {
                $result[] = [
                    'product_id' => $productId,
                    'type' => $type,
                    'name' => $medName ?? 'N/A',
                    'packaging_detail' => $packagingDetail,
                    'quantity' => $quantity,
                    'is_substitute' => $isSubstitute,
                ];
            }
        }

        return response()->json(['status' => 'success', 'data' => $result]);
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

    public function fetchPrescriptionFiles(Request $request)
    {
        $prescriptionId = $request->input('prescriptionId');

        $prescription = Prescription::find($prescriptionId);

        if (!$prescription) {
            return response()->json([
                'status' => 'error',
                'message' => 'Prescription not found',
            ]);
        }

        $customerId = $prescription->customer_id;

        $prescriptions = Prescription::where('customer_id', $customerId)->where('status', 1)->get();

        if ($prescriptions->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No prescriptions found for this customer',
            ]);
        }

        $fileUrls = [];
        foreach ($prescriptions as $prescription) {
            $files = explode(',', $prescription->prescription_file);

            foreach ($files as $file) {
                $fileUrls[] = asset('uploads/' . trim($file));
            }
        }
        if (empty($fileUrls)) {
            return response()->json([
                'status' => 'error',
                'message' => 'No files found for this customer',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'files' => $fileUrls,
        ]);
    }
}
