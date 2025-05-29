<?php

namespace App\Http\Controllers;

use App\Models\LabCart;
use App\Models\Laboratories;
use App\Models\LabTest;
use App\Models\PackageCategory;
use Illuminate\Http\Request;

class LabPackageAndTestDetailsController extends Controller
{
    public function getpackageandtestbyorgan()
    {

        $categories = PackageCategory::select('id', 'name', 'package_image')->get()->map(function ($item) {
            $item->package_image = $item->package_image
                ? url('assets/package_image/' . basename($item->package_image))
                : null; // or use [] if you prefer an empty array
            return $item;
        });

        return response()->json([
            'status' => true,
            'data' => $categories
        ], 200);

        //return response()->json($categories);

    }

    public function getPacakgesAndTestByOrgan(Request $request, $organId)
    {
        // 1. Get organ name from package_categories
        $organ = PackageCategory::find($organId);
        $organName = $organ ? $organ->name : null;

        // 2. Get all lab tests that include the organId in comma-separated 'organ' field
        $labTestsRaw = LabTest::whereRaw("FIND_IN_SET(?, organ)", [$organId])->get();

        // Step 2: Get matching lab test IDs
        $labTestIds = LabTest::whereRaw("FIND_IN_SET(?, organ)", [$organName])
            ->pluck('id')
            ->toArray();


        $labTests = [];

        $labs = Laboratories::all();

        foreach ($labs as $lab) {
            $tests = json_decode($lab->test, true);

            if (is_array($tests)) {
                foreach ($tests as $testItem) {
                    if (in_array($testItem['test'], $labTestIds)) {
                        $testDetails = LabTest::find($testItem['test']);
                        if ($testDetails) {
                            $labTests[] = [
                                'lab_id' => $lab->id,
                                'lab_name' => $lab->lab_name,
                                'test_id' => $testItem['test'],
                                'test_name' => $testDetails->name,
                                'price' => $testItem['price'],
                                'home_price' => $testItem['homeprice'],
                                'report_time' => $testItem['report'],
                                'offer_visiting_price' => $testItem['offer_visiting_price'],
                                'offer_home_price' => $testItem['offer_home_price'],
                                'description' => $testDetails->description,
                                'contains' => $testDetails->contains,
                                'gender' => $testDetails->gender,
                                'sample_required' => $testDetails->sample_required,
                                'preparation' => $testDetails->preparation,
                                'sub_reports' => $testDetails->sub_reports,
                            ];
                        }
                    }
                }
            }
        }

        // 3. Extract packages based on organId from each lab's package_details column
        $packages = [];

        foreach ($labs as $lab) {
            $packageDetails = json_decode($lab->package_details, true);

            if (is_array($packageDetails)) {
                foreach ($packageDetails as $pkg) {
                    if (!empty($pkg['package_category']) && in_array((string) $organId, $pkg['package_category'])) {
                        $packages[] = [
                            'lab_id' => $lab->id,
                            'lab_name' => $lab->lab_name,
                            'package_name' => $pkg['package_name'] ?? '',
                            'package_description' => $pkg['package_description'] ?? '',
                            'visiting_price' => $pkg['package_visiting_price'] ?? '',
                            'home_price' => $pkg['package_home_price'] ?? '',
                            'report' => $pkg['package_report'] ?? '',
                            'offer_visiting_price' => $pkg['package_offer_visiting_price'] ?? '',
                            'offer_home_price' => $pkg['package_offer_home_price'] ?? '',
                        ];
                    }
                }
            }
        }

        return response()->json([
            'status' => true,
            'organ_name' => $organName,
            'data' => [
                'lab_tests' => $labTests,
                'packages' => $packages,
            ],
        ], 200);

    }

    public function bookpackageorlabtest(Request $request)
    {
        $customerId = $request->get('user_id');
        $labId = $request->input('lab_id');
        $testId = $request->input('test_id');
        $packageName = $request->input('package_name');
        $contains = $request->input('contains');

        // 1. Retrieve or create LabCart for the customer
        $labCart = LabCart::firstOrCreate(
            ['customer_id' => $customerId],
            [
                'test_details' => json_encode([]),
                'prescription_id' => null,
            ]
        );

        // 2. Decode current test_details
        $testDetails = json_decode($labCart->test_details, true) ?? [];

        // 3. Check if another lab already exists in cart
        $existingLabId = null;
        foreach ($testDetails as $item) {
            if (isset($item['lab_id'])) {
                $existingLabId = $item['lab_id'];
                break;
            }
        }

        if ($existingLabId && $labId && $labId != $existingLabId) {
            $existingLabName = Laboratories::where('id', $existingLabId)->value('lab_name');
            return response()->json([
                'status' => false,
                'message' => "Lab '$existingLabName' already exists in cart. Please clear cart before adding from a new lab.",
            ], 200);
        }

        // 4. If lab_id still not present, assign existing
        if (!$labId && $existingLabId) {
            $labId = $existingLabId;
        }

        // 5. Check and update or add entry
        $updated = false;

        if ($testId) {
            foreach ($testDetails as &$item) {
                if (isset($item['test_id']) && $item['test_id'] == $testId) {
                    $item['contains'] = $contains;
                    $updated = true;
                    break;
                }
            }
            unset($item);

            if (!$updated) {
                $testDetails[] = [
                    'test_id' => (string) $testId,
                    'contains' => (string) $contains,
                    'lab_id' => (string) $labId,
                ];
            }

        } elseif ($packageName) {
            foreach ($testDetails as &$item) {
                if (isset($item['package_name']) && $item['package_name'] == $packageName) {
                    $updated = true;
                    break;
                }
            }
            unset($item);

            if (!$updated) {
                $testDetails[] = [
                    'package_name' => $packageName,
                    'lab_id' => (string) $labId,
                ];
            }
        }

        // 6. Save updated test_details
        $labCart->test_details = json_encode($testDetails);
        $labCart->save();

        return response()->json([
            'status' => true,
            'message' => 'Lab cart updated successfully.',
            'updated_test_details' => $testDetails,
        ]);
    }

}
