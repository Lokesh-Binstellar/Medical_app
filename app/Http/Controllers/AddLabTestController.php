<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LabTest;
use App\Models\Prescription;
use Illuminate\Support\Facades\DB;

class AddLabTestController extends Controller
{
    public function index()
    {
        return view('laboratorie.addLabTest.index');
    }

//  public function store(Request $request)
//     {
//        $validated = $request->validate([
//             'prescription_id' => 'required',
//             'labtest.*.id' => 'required',
//             'labtest.*.contains' => 'required',
//         ]);

//         $prescriptionId = $request['prescription_id'];

//         $prescription = Prescription::find($prescriptionId);
//         if (!$prescription) {
//             return redirect()->back()->with('error', 'Prescription not found.');
//         }

//         $customerId = $prescription->customer_id;

//         $incomingProducts = $validated['labtest'];

//         $cart = DB::table('carts')->where('customer_id', $customerId)->first();

//         if ($cart) {
//             $existingProducts = json_decode($cart->products_details, true) ?? [];

//             $existingProductIds = array_column($existingProducts, 'product_id');

//             $mergedProducts = $existingProducts;

//             foreach ($incomingProducts as $row) {
//                 if (!in_array($row['id'], $existingProductIds)) {
//                     $mergedProducts[] = [
//                         'product_id' => $row['id'],
//                         'packaging_detail' => $row['contains'],
//                     ];
//                 }
//             }

//             $mergedProducts = array_map(function ($item) {
//                 $item['quantity'] = (int) $item['quantity'];
//                 return $item;
//             }, $mergedProducts);

//             $existingPrescriptions = json_decode($cart->prescription_id, true) ?? [];
//             $updatedPrescriptions = array_unique(array_merge($existingPrescriptions, [$prescriptionId]));

//             DB::table('carts')
//                 ->where('id', $cart->id)
//                 ->update([
//                     'products_details' => json_encode($mergedProducts),
//                     'prescription_id' => json_encode($updatedPrescriptions),
//                     'updated_at' => now(),
//                 ]);
//         } else {
//             $productsToInsert = [];

//             foreach ($incomingProducts as $row) {
//                 $productsToInsert[] = [
//                     'product_id' => $row['medicine_id'],
//                     'packaging_detail' => $row['packaging_detail'],
//                     'quantity' => (int) $row['quantity'],
//                     'is_substitute' => $row['is_substitute'],
//                 ];
//             }

//             $productsToInsert = array_map(function ($item) {
//                 $item['quantity'] = (int) $item['quantity'];
//                 return $item;
//             }, $productsToInsert);

//             DB::table('carts')->insert([
//                 'customer_id' => $customerId,
//                 'prescription_id' => json_encode([$prescriptionId]),
//                 'test_details' => json_encode($productsToInsert),
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ]);
//         }
//         DB::table('prescriptions')
//             ->where('id', $prescriptionId)
//             ->update([
//                 'status' => 0,
//                 'updated_at' => now(),
//             ]);

//         return redirect()->back()->with('success', 'Products added to cart successfully ');
//     }

public function store(Request $request)
{
    $validated = $request->validate([
        'prescription_id' => 'required',
        'labtest.*.id' => 'required',
        'labtest.*.contains' => 'required',
    ]);
// dd($validated);
    $prescriptionId = $request['prescription_id'];

    $prescription = Prescription::find($prescriptionId);
    if (!$prescription) {
        return redirect()->back()->with('error', 'Prescription not found.');
    }

    $customerId = $prescription->customer_id;
    $incomingTests = $validated['labtest'];

    $labCart = DB::table('lab_carts')->where('customer_id', $customerId)->first();

    if ($labCart) {
        $existingTests = json_decode($labCart->test_details, true) ?? [];

        $existingTestIds = array_column($existingTests, 'test_id');

        $mergedTests = $existingTests;

        foreach ($incomingTests as $row) {
            if (!in_array($row['id'], $existingTestIds)) {
                $mergedTests[] = [
                    'test_id' => $row['id'],
                    'contains' => $row['contains'],
                ];
            }
        }

        $existingPrescriptions = json_decode($labCart->prescription_id, true) ?? [];
        $updatedPrescriptions = array_unique(array_merge($existingPrescriptions, [$prescriptionId]));

        DB::table('lab_carts')
            ->where('id', $labCart->id)
            ->update([
                'test_details' => json_encode($mergedTests),
                'prescription_id' => json_encode($updatedPrescriptions),
                'updated_at' => now(),
            ]);
    } else {
        $testsToInsert = [];

        foreach ($incomingTests as $row) {
            $testsToInsert[] = [
                'test_id' => $row['id'],
                'contains' => $row['contains'],
            ];
        }

        DB::table('lab_carts')->insert([
            'customer_id' => $customerId,
            'prescription_id' => json_encode([$prescriptionId]),
            'test_details' => json_encode($testsToInsert),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    DB::table('prescriptions')
        ->where('id', $prescriptionId)
        ->update([
            'status' => 0,
            'updated_at' => now(),
        ]);

    return redirect()->back()->with('success', 'Lab tests added to lab cart successfully.');
}






    public function search(Request $request)
    {
        $query = $request->input('query');

        // Search in medicines table using product_id
        $results = LabTest::where('name', 'like', "%{$query}%")
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id, // use product_id instead of id
                    'text' => "{$item->name} "
                   
                ];
            });



        return response()->json([
            'results' => $results,
        ]);
    }


//get contains data onchange
public function getContains(Request $request)
{
    $id = $request->input('id');
    try {
        if (!$id) {
            return response()->json([
                'status' => false,
                'message' => 'Lab Test ID is required.'
            ], 400);
        }
        
        $labTest = LabTest::select('contains')->find($id);
       
        // echo $labTest;die; 

        if ($labTest) {
            return response()->json([
                'status' => true,
                'contains' => $labTest->contains ?? ''
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Lab test not found'
        ], 404);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Something went wrong',
            'error' => $e->getMessage()
        ], 500);
    }
}


// Get prescriptions that match the search query (e.g., based on mobile_no or firstName)
  public function prescriptionSelect(Request $request)
    {
        $search = $request->input('query');

       
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





}
