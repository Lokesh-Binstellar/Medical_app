<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Medicine;
use App\Models\Otcmedicine;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                    'id' => $item->id,
                    'text' => "{$item->product_name} + {$item->salt_composition}",
                    'type' => 'prescription',
                ];
            });

        // Search in otcmedicines table
        $otcmedicines = Otcmedicine::where('name', 'like', "%{$query}%")
            ->get()
            ->map(function ($item) {
                return [
                    'id' => 'otc_' . $item->id, // Prefix to distinguish
                    'text' => $item->name,
                    'type' => 'otc',
                ];
            });

        // Combine both
        $results = $medicines->concat($otcmedicines)->values(); // use concat instead of merge to keep Collection

        return response()->json([
            'results' => $results
        ]);

    }
    public function prescriptionSelect(Request $request)
    {
        $search = $request->input('query');

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

    public function getMedicineStrip($id)
    {
        dd($id);
        $medicine = Medicine::find($id);
    
        if ($medicine) {
            return response()->json([
                'status' => true,
                'packaging_detail' => $medicine->packaging_detail ?? '',
            ]);
        }
    
        return response()->json([
            'status' => false,
            'packaging_detail' => '',
        ]);
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
    public function getAddToCart($id)
    {
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
                        $productId = $product['product_id'];
                        $medicine = null;
                        $type = null;

                        if (Str::startsWith($productId, 'otc_')) {
                            $type = 'otc';
                            $numericId = str_replace('otc_', '', $productId);
                            $medicine = \App\Models\Otcmedicine::find($numericId);
                        } else {
                            $type = 'medicine';
                            $medicine = \App\Models\Medicine::find($productId);
                        }

                        if ($medicine) {
                            $name = $type === 'medicine'
                                ? ($medicine->product_name ?? '')
                                : ($medicine->name ?? '');


                            // Prepare image URLs

                            $imageUrls = [];
                            if (!empty($medicine->image_url)) {
                                $images = is_array($medicine->image_url)
                                    ? $medicine->image_url
                                    : (json_decode($medicine->image_url, true) ?: explode(',', $medicine->image_url));

                                $imageUrls = array_map(function ($img) {
                                    $img = trim($img);
                                    // If path already contains 'medicines/', don't prepend again
                                    return Str::startsWith($img, 'medicines/')
                                        ? asset('storage/' . $img)
                                        : asset('storage/medicines/' . $img);
                                }, $images);
                            }

                            $detailedProducts[] = [
                                'product_id' => $productId, // e.g., otc_9
                                'type' => $type,
                                'name' => $name,
                                'prescription_required' => ($medicine->prescription_required === 'Prescription Required') ? true : false,
                                'qty' => $medicine->qty,
                                'image_url' => $imageUrls,
                                'is_substitute' => $product['is_substitute'] ?? 'no',
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

  
}
