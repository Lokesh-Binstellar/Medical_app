<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Medicine;
use App\Models\Otcmedicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    public function customerSelect(Request $request)
    {
        $search = $request->input('query');

        $customers = DB::table('customers')
            ->where('firstName', 'like', "%{$search}%")
            ->orWhere('mobile_no', 'like', "%{$search}%")
            ->select('id', DB::raw("CONCAT(firstName, ' (', mobile_no, ')') as text"))
            ->limit(10)
            ->get();

        return response()->json(['results' => $customers]);
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
            'customer.0.customer_id' => 'required',
            'medicine.*.medicine_id' => 'required',
            'medicine.*.is_substitute' => 'required',
        ]);
    
        $customerId = $request->input('customer')[0]['customer_id'];
    
        $allProducts = [];
    
        foreach ($validated['medicine'] as $row) {
            $allProducts[] = [
                'product_id' => $row['medicine_id'],
                'is_substitute' => $row['is_substitute'],
            ];
        }
    
        DB::table('carts')->insert([
            'customer_id' => $customerId,
            'products_details' => json_encode($allProducts),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
        return redirect()->back()->with('success', 'All product data saved successfully ');
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
                return [
                    'id' => $cart->id,
                    'customer_id' => $cart->customer_id,
                    'products_details' => json_decode($cart->products_details),
                    'created_at' => $cart->created_at
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
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
