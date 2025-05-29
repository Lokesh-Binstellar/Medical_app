<?php

namespace App\Http\Controllers;

use App\Models\Laboratories;
use App\Models\LabTest;
use App\Models\PackageCategory;
use App\Models\Rating;
use App\Models\User;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class LaboratoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Laboratories::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
    <div class="dropdown">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
            Action
        </button>
        <ul class="dropdown-menu">
            <li><a href="' .
                        route('laboratorie.show', $row->id) .
                        '" class="dropdown-item">View</a></li>
            <li><a href="' .
                        route('laboratorie.edit', $row->id) .
                        '" class="dropdown-item">Edit</a></li>
            <li>
                <button
                    class="dropdown-item btn-delete-laboratory"
                    data-id="' .
                        $row->id .
                        '"
                    data-url="' .
                        route('laboratorie.destroy', $row->id) .
                        '"
                >
                    Delete
                </button>
            </li>
        </ul>
    </div>';
                })

                ->rawColumns(['action'])
                ->make(true);
        }
        // $tests = LabTest::all();
        return view('laboratorie.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tests = LabTest::all();
        $categories = PackageCategory::select('id', 'name')->get();

        return view('laboratorie.create', compact('tests', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->all();

        // $validation = validator($params, [
        //     'lab_name' => 'required|string',
        //     'owner_name' => 'required|string',
        //     'email' => 'required|email|unique:users,email',
        //     'phone' => 'required|numeric|digits_between:11,15',
        //     'city' => 'required|string',
        //     'state' => 'required|string',
        //     'pincode' => 'required|string',
        //     'address' => 'required|string',
        //     'latitude' => 'required|string',
        //     'longitude' => 'required|string',
        //     'image' => 'nullable|file|image|max:10240',
        //     'username' => 'required|string',
        //     'password' => 'required|string',
        //     'license' => 'required|string',
        //     'nabl_iso_certified' => 'required|boolean',
        //     'pickup' => 'required|string',
        //     'gstno' => 'nullable|string|max:20',
        //     'test' => 'nullable|array',
        //     'test.*' => 'string',
        //     'price' => 'nullable|array',
        //     'homeprice' => 'nullable|array',
        //     'report' => 'nullable|array',
        //     'offer_visiting_price' => 'nullable|array',
        //     'offer_home_price' => 'nullable|array',
        //     'package_details.*' => 'string',
        //     // Package fields:
        //     'package_name' => 'nullable|array',
        //     'package_visiting_price' => 'nullable|array',
        //     'package_home_price' => 'nullable|array',
        //     'package_report' => 'nullable|array',
        //     'package_offer_visiting_price' => 'nullable|array',
        //     'package_offer_home_price' => 'nullable|array',
        //     'package_description' => 'nullable|array',
        //     'package_category' => 'nullable|array',
        // ]);

        // if ($validation->fails()) {
        //     return back()->withErrors($validation)->withInput();
        // }

        $roleId = \App\Models\Role::where('name', 'laboratory')->value('id');

        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $roleId,
        ]);

        $params['user_id'] = $user->id;

        // Image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('assets/image/');
            $image->move($destinationPath, $imageName);
            $params['image'] = $imageName;
        }

        //test details
        $testData = [];
        $tests = $request->test ?? [];
        $prices = $request->price ?? [];
        $homeprices = $request->homeprice ?? [];
        $report = $request->report ?? [];
        $offer_visiting_price = $request->offer_visiting_price ?? [];
        $offer_home_price = $request->offer_home_price ?? [];

        foreach ($tests as $key => $test) {
            $testData[] = [
                'test' => $test,
                'price' => $prices[$key] ?? null,
                'homeprice' => $homeprices[$key] ?? null,
                'report' => $report[$key] ?? null,
                'offer_visiting_price' => $offer_visiting_price[$key] ?? null,
                'offer_home_price' => $offer_home_price[$key] ?? null,
            ];
        }
        //package details json data

        $packageData = [];
        $package_names = $request->package_name ?? [];
        $package_descriptions = $request->package_description ?? [];
        $package_visiting_prices = $request->package_visiting_price ?? [];
        $package_home_prices = $request->package_home_price ?? [];
        $package_reports = $request->package_report ?? [];
        $package_offer_visiting_prices = $request->package_offer_visiting_price ?? [];
        $package_offer_home_prices = $request->package_offer_home_price ?? [];
        $package_categories = $request->package_category ?? [];

        foreach ($package_names as $key => $name) {
            $packageData[] = [
                'package_name' => $name,
                'package_description' => $package_descriptions[$key] ?? null,
                'package_visiting_price' => $package_visiting_prices[$key] ?? null,
                'package_home_price' => $package_home_prices[$key] ?? null,
                'package_report' => $package_reports[$key] ?? null,
                'package_offer_visiting_price' => $package_offer_visiting_prices[$key] ?? null,
                'package_offer_home_price' => $package_offer_home_prices[$key] ?? null,
                'package_category' => $package_categories[$key] ?? null,
            ];
        }
        // dd( $packageData);

        // Assuming $testData is prepared somewhere before, else default empty array
        //$testData = $request->test ?? [];

        $params['test'] = json_encode($testData);
        $params['package_details'] = json_encode($packageData);

        unset($params['price'], $params['homeprice'], $params['report'], $params['offer_visiting_price'], $params['offer_home_price'], $params['package_name'], $params['package_description'], $params['package_visiting_price'], $params['package_home_price'], $params['package_report'], $params['package_offer_visiting_price'], $params['package_offer_home_price'], $params['package_category']);

        Laboratories::create($params);

        return redirect()->route('laboratorie.index')->with('success', 'Laboratory created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Fetch the laboratory details
        $lab = Laboratories::findOrFail($id);

        // Decode JSON fields
        $testData = json_decode($lab->test, true);
        $packageData = json_decode($lab->package_details, true);

        $labTests = [];
        $packageDetails = [];

        // Lab Test Processing
        foreach ($testData as $test) {
            $testInfo = \DB::table('lab_tests')->where('id', $test['test'])->select('name as test_name')->first();

            $labTests[] = [
                'test_name' => $testInfo ? $testInfo->test_name : 'Unknown',
                'price' => $test['price'],
                'homeprice' => $test['homeprice'],
                'report' => $test['report'],
                'offer_visiting_price' => $test['offer_visiting_price'],
                'offer_home_price' => $test['offer_home_price'],
            ];
        }

        // Package Processing
        foreach ($packageData as $package) {
            $categoryIds = [];

            // Get category IDs as array
            if (!empty($package['package_category'])) {
                if (is_string($package['package_category'])) {
                    $categoryIds = explode(',', $package['package_category']);
                } elseif (is_array($package['package_category'])) {
                    $categoryIds = $package['package_category'];
                }
            }

            // Get all category names from IDs
            $categoryNames = PackageCategory::whereIn('id', $categoryIds)->pluck('name')->toArray();

            $packageDetails[] = [
                'package_name' => $package['package_name'] ?? 'Unknown',
                'package_description' => $package['package_description'] ?? '',
                'package_visiting_price' => $package['package_visiting_price'] ?? 0,
                'package_home_price' => $package['package_home_price'] ?? 0,
                'package_report' => $package['package_report'] ?? '',
                'package_offer_visiting_price' => $package['package_offer_visiting_price'] ?? 0,
                'package_offer_home_price' => $package['package_offer_home_price'] ?? 0,
                'package_category' => $categoryNames,
            ];
        }

        return view('laboratorie.show', compact('lab', 'labTests', 'packageDetails'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $laboratorie = Laboratories::findOrFail($id);

        $labTests = json_decode($laboratorie->test, true) ?? [];
        $categories = PackageCategory::select('id', 'name')->get();
        $cat1 = json_decode($laboratorie->package_details, true) ?? [];
        // dd($cat1);
        $cat = $cat1[0]['package_category'] ?? [];
        // $packageCtegory=PackageCategory::all();
        $allTests = LabTest::all();

        return view('laboratorie.edit', compact('laboratorie', 'labTests', 'allTests', 'categories', 'cat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $laboratorie = Laboratories::findOrFail($id);

        // Validate input
        $data = $request->validate([
            'lab_name' => 'required|string',
            'owner_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|min:11|numeric',
            'city' => 'required|string',
            'state' => 'required|string',
            'pincode' => 'required|max:9',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            // 'username' => 'required|string',
            'license' => 'required|string',
            'pickup' => 'required|string',
            'gstno' => 'nullable|string',
            'nabl_iso_certified' => 'required|boolean',
            'image' => 'nullable|image|max:10240',
            'test.*' => 'nullable|string',
            'price.*' => 'nullable|numeric',
            'homeprice.*' => 'nullable|numeric',
            'report.*' => 'nullable|string',
            'offer_visiting_price.*' => 'nullable|string',
            'offer_home_price.*' => 'nullable|string',
            // Package fields:
            'package_details.*' => 'string',
            'package_name.*' => 'nullable|string',
            'package_visiting_price.*' => 'nullable|string',
            'package_home_price.*' => 'nullable|string',
            'package_report.*' => 'nullable|string',
            'package_offer_visiting_price.*' => 'nullable|string',
            'package_offer_home_price.*' => 'nullable|string',
            'package_description.*' => 'nullable|string',
            'package_category.*' => 'nullable|array',
            'package_category.*.*' => 'string',
        ]);

        $data = $request->only(['lab_name', 'owner_name', 'email', 'phone', 'city', 'state', 'pincode', 'address', 'latitude', 'longitude', 'username', 'license', 'pickup', 'gstno', 'nabl_iso_certified']);

        // Image upload and old delete
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('assets/image/');
            $image->move($destinationPath, $imageName);
            $data['image'] = $imageName;

            if ($laboratorie->image && File::exists($destinationPath . $laboratorie->image)) {
                File::delete($destinationPath . $laboratorie->image);
            }
        }

        $existingTests = json_decode($laboratorie->test, true) ?? [];
        $existingPackage = json_decode($laboratorie->package_details, true) ?? [];

        // Prepare test data to update
        $testData = [];
        $tests = $request->test ?? [];
        $prices = $request->price ?? [];
        $homeprices = $request->homeprice ?? [];
        $report = $request->report ?? [];
        $offer_visiting_price = $request->offer_visiting_price ?? [];
        $offer_home_price = $request->offer_home_price ?? [];

        foreach ($tests as $key => $testId) {
            if (!empty($testId)) {
                $found = false;
                foreach ($existingTests as &$existingTest) {
                    if ($existingTest['test'] == $testId) {
                        $existingTest['price'] = $prices[$key] ?? 0;
                        $existingTest['homeprice'] = $homeprices[$key] ?? 0;
                        $existingTest['report'] = $report[$key] ?? 0;
                        $existingTest['offer_visiting_price'] = $offer_visiting_price[$key] ?? 0;
                        $existingTest['offer_home_price'] = $offer_home_price[$key] ?? 0;
                        $found = true;
                        break;
                    }
                }

                // If the test is not found, add a new one
                if (!$found) {
                    $testData[] = [
                        'test' => $testId,
                        'price' => $prices[$key] ?? 0,
                        'homeprice' => $homeprices[$key] ?? 0,
                        'report' => $report[$key] ?? null,
                        'offer_visiting_price' => $offer_visiting_price[$key] ?? null,
                        'offer_home_price' => $offer_home_price[$key] ?? null,
                    ];
                }
            }
        }

        $package_names = $request->package_name ?? [];
        $package_descriptions = $request->package_description ?? [];
        $package_visiting_prices = $request->package_visiting_price ?? [];
        $package_home_prices = $request->package_home_price ?? [];
        $package_reports = $request->package_report ?? [];
        $package_offer_visiting_prices = $request->package_offer_visiting_price ?? [];
        $package_offer_home_prices = $request->package_offer_home_price ?? [];
        $package_categories = $request->package_category ?? [];

        $packageDataToAdd = [];

        foreach ($package_names as $key => $name) {
            if (!empty($name)) {
                $found = false;

                foreach ($existingPackage as $index => $existingPkg) {
                    if ($existingPkg['package_name'] == $name) {
                        $updatedPkg = [
                            'package_name' => $name,
                            'package_description' => $package_descriptions[$key] ?? ($existingPkg['package_description'] ?? null),
                            'package_visiting_price' => $package_visiting_prices[$key] ?? ($existingPkg['package_visiting_price'] ?? null),
                            'package_home_price' => $package_home_prices[$key] ?? ($existingPkg['package_home_price'] ?? null),
                            'package_report' => $package_reports[$key] ?? ($existingPkg['package_report'] ?? null),
                            'package_offer_visiting_price' => $package_offer_visiting_prices[$key] ?? ($existingPkg['package_offer_visiting_price'] ?? null),
                            'package_offer_home_price' => $package_offer_home_prices[$key] ?? ($existingPkg['package_offer_home_price'] ?? null),
                            'package_category' => $package_categories[$key] ?? ($existingPkg['package_category'] ?? null),
                        ];

                        $existingPackage[$index] = $updatedPkg;
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $packageDataToAdd[] = [
                        'package_name' => $name,
                        'package_description' => $package_descriptions[$key] ?? null,
                        'package_visiting_price' => $package_visiting_prices[$key] ?? null,
                        'package_home_price' => $package_home_prices[$key] ?? null,
                        'package_report' => $package_reports[$key] ?? null,
                        'package_offer_visiting_price' => $package_offer_visiting_prices[$key] ?? null,
                        'package_offer_home_price' => $package_offer_home_prices[$key] ?? null,
                        'package_category' => $package_categories[$key] ?? null,
                    ];
                }
            }
        }

        $mergedTests = array_merge($existingTests, $testData);
        $mergedPackages = array_merge($existingPackage, $packageDataToAdd);

        $uniqueTests = [];
        foreach ($mergedTests as $test) {
            $uniqueTests[$test['test']] = $test;
        }

        $uniquePackages = [];
        foreach ($mergedPackages as $pkg) {
            $uniquePackages[$pkg['package_name']] = $pkg;
        }
        // Re-index the array
        $testData = array_values($uniqueTests);
        $packageDataFinal = array_values($uniquePackages);
        $data['package_details'] = json_encode($packageDataFinal);
        $data['test'] = json_encode($testData);

        $laboratorie->update($data);
        // dd($laboratorie);

        return redirect()->route('laboratorie.index')->with('success', 'Laboratory updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Request $request)
    {
        try {
            $laboratory = Laboratories::findOrFail($id);
            $laboratory->delete();

            if ($request->ajax()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Laboratory deleted successfully',
                ]);
            }

            return redirect()->route('laboratorie.index')->with('success', 'Laboratory deleted successfully');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Failed to delete laboratory: ' . $e->getMessage(),
                    ],
                    500,
                );
            }

            return redirect()->route('laboratorie.index')->with('error', 'Failed to delete laboratory');
        }
    }

    public function getAllLaboratory(Request $request)
    {
        try {
            $latlong = $request->latlong;
            if (!$latlong) {
                return response()->json(['status' => false, 'message' => 'latlong is required'], 400);
            }

            [$userLat, $userLon] = explode(',', $latlong);
            $userLat = trim($userLat);
            $userLon = trim($userLon);

            $apiKey = env('GOOGLE_MAPS_API_KEY');

            // 1. Get city from coordinates using Geocoding API
            $geoUrl = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$userLat,$userLon&key=$apiKey";
            $geoResponse = file_get_contents($geoUrl);
            $geoData = json_decode($geoResponse, true);

            if (!$geoData || $geoData['status'] !== 'OK') {
                return response()->json(['status' => false, 'message' => 'Could not determine city from location'], 400);
            }

            $city = null;
            foreach ($geoData['results'][0]['address_components'] as $component) {
                if (in_array('locality', $component['types'])) {
                    $city = $component['long_name'];
                    break;
                }
            }

            if (!$city) {
                return response()->json(['status' => false, 'message' => 'City not found in address data'], 400);
            }

            // 2. Get labs in that city
            $labs = Laboratories::where('city', $city)->get([
                'id', 'lab_name', 'user_id', 'pickup', 'latitude', 'longitude', 'city'
            ]);


            // 3. Calculate distance using Google Directions API
            $result = [];
            foreach ($labs as $lab) {
                if ($lab->latitude && $lab->longitude) {
                    $directionUrl = "https://maps.googleapis.com/maps/api/directions/json?origin=$userLat,$userLon&destination={$lab->latitude},{$lab->longitude}&key=$apiKey";
                    $response = file_get_contents($directionUrl);
                    $data = json_decode($response, true);

                    if ($data['status'] === 'OK') {
                        $distanceMeters = $data['routes'][0]['legs'][0]['distance']['value'];
                        $distanceKm = round($distanceMeters / 1000, 2);

                        // Get rating
                        $ratingData = Rating::where('rateable_type', 'Laboratory')
                            ->where('rateable_id', $lab->user_id)
                            ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as rating_count')
                            ->first();

                        $formattedRating = $ratingData->rating_count > 0
                            ? round($ratingData->avg_rating, 1) . ' (' . $ratingData->rating_count . ')'
                            : null;

                        $result[] = [
                            'id' => $lab->id,
                            'lab_name' => $lab->lab_name,
                            'user_id' => $lab->user_id,
                            'pickup' => $lab->pickup,
                            'latitude' => $lab->latitude,
                            'longitude' => $lab->longitude,
                            'distance_km' => $distanceKm,
                            'rating' => $formattedRating,
                            'rating_value' => $ratingData->avg_rating ?? 0, // used only for sorting
                            'lab_city' => $lab->city,
                        ];
                    }
                }
            }

            // Sort labs by rating descending
            usort($result, function ($a, $b) {
                return $b['rating_value'] <=> $a['rating_value'];
            });

            // Remove internal sorting field
            $result = array_map(function ($lab) {
                unset($lab['rating_value']);
                return $lab;
            }, $result);


            return response()->json([
                'status' => true,
                'city' => $city,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getAllLaboratory: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again later.',
                'error' => $e->getMessage(), // remove in production
            ], 500);
        }
    }

}
