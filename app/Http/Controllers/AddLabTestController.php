<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LabTest;
use App\Models\LabCart;
use App\Models\Prescription;
use Illuminate\Support\Facades\DB;

class AddLabTestController extends Controller
{
    public function index()
    {
        return view('laboratorie.addLabTest.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'prescription_id' => 'required',
            'labtest.*.id' => 'required',
            'labtest.*.contains' => 'required',
        ]);

        $prescriptionId = $request['prescription_id'];
        $prescription = Prescription::find($prescriptionId);

        if (!$prescription) {
            return redirect()->back()->with('error', 'Prescription not found.');
        }

        $customerId = $prescription->customer_id;
        $incomingTests = $validated['labtest'];

        $labCart = LabCart::where('customer_id', $customerId)->first();

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

            $labCart->test_details = json_encode($mergedTests);
            $labCart->prescription_id = json_encode($updatedPrescriptions);
            $labCart->save();
        } else {
            $testsToInsert = [];

            foreach ($incomingTests as $row) {
                $testsToInsert[] = [
                    'test_id' => $row['id'],
                    'contains' => $row['contains'],
                ];
            }

            LabCart::create([
                'customer_id' => $customerId,
                'prescription_id' => json_encode([$prescriptionId]),
                'test_details' => json_encode($testsToInsert),
            ]);
        }

        $prescription->update([
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
                    'text' => "{$item->name} ",
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
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Lab Test ID is required.',
                    ],
                    400,
                );
            }

            $labTest = LabTest::select('contains')->find($id);

            // echo $labTest;die;

            if ($labTest) {
                return response()->json([
                    'status' => true,
                    'contains' => $labTest->contains ?? '',
                ]);
            }

            return response()->json(
                [
                    'status' => false,
                    'message' => 'Lab test not found',
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

    //get labcart tests data
    public function getUserlabcart(Request $request)
    {
        $userId = $request->get('user_id');
        $labcart = LabCart::where('customer_id', $userId)->first();
        
        if (!$labcart) {
            return response()->json(['status' => false, 'message' => 'labCart not found.']);
        }
        $testDetail = json_decode($labcart->test_details, true);
        $detailTests = [];
        foreach ($testDetail as $test) {
            $labTest = LabTest::find($test['test_id']);
            $detailTests[] = [
                'test_id' => $test['test_id'],
                'test_name' => $labTest ? $labTest->name : 'Unknown',
                'contains' => $test['contains'],
            ];
        }
        
        return response()->json([
            'status' => true,
            'data' => [
                'id' => $labcart->id,
                'customer_id' => $labcart->customer_id,
                'test_details' => $detailTests,
            ],
        ]);
    }
    
    //delete labcart specific test record
    public function deleteTestFromLabCart(Request $request,$id)
{
    // echo "okk";die;
    $userId = $request->get('user_id');

    $labcart = LabCart::where('customer_id', $userId)->first();

    if (!$labcart) {
        return response()->json(['status' => false, 'message' => 'LabCart not found.']);
    }

    $testDetails = json_decode($labcart->test_details, true);
    if (empty($testDetails)) {
                return response()->json(['status' => false, 'message' => 'No products in cart.']);
            }

            $originalCount = count($testDetails);

    $updatedTests = array_filter($testDetails, function ($test) use ($id) {
        return $test['test_id'] != $id;
    });
     if (count($updatedTests) === $originalCount) {
                // No change = product not found
                return response()->json(['status' => false, 'message' => 'Product not found in cart.']);
            }

    $updatedTests = array_values($updatedTests);

    $labcart->test_details = json_encode($updatedTests);
    $labcart->save();

    return response()->json([
        'status' => true,
        'message' => 'Test removed from lab cart successfully.',
        'updated_test_details' => $updatedTests,
    ]);
}



}
