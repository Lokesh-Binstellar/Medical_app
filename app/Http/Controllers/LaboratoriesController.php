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
use Log;
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
        $params['password'] = Hash::make($request->password);

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
        $allTests = LabTest::all();

        return view('laboratorie.edit', compact('laboratorie', 'labTests', 'allTests', 'categories', 'cat'));
    }

    public function update(Request $request, $id)
    {
        $laboratorie = Laboratories::findOrFail($id);

        // Validate input
        $validated = $request->validate([
            'lab_name' => 'required|string',
            'owner_name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $laboratorie->user_id,

            'password' => 'nullable|string|min:6',
            'phone' => 'required|min:11|numeric',
            'city' => 'required|string',
            'state' => 'required|string',
            'pincode' => 'required|max:9',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'username' => 'required|string',
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
            'package_name.*' => 'nullable|string',
            'package_description.*' => 'nullable|string',
            'package_visiting_price.*' => 'nullable|string',
            'package_home_price.*' => 'nullable|string',
            'package_report.*' => 'nullable|string',
            'package_offer_visiting_price.*' => 'nullable|string',
            'package_offer_home_price.*' => 'nullable|string',
            'package_category.*' => 'nullable|array',
            'package_category.*.*' => 'string',
        ]);

        // Basic fields (excluding tests and packages)
        $data = $request->only(['lab_name', 'owner_name', 'email', 'phone', 'city', 'state', 'pincode', 'address', 'latitude', 'longitude', 'username', 'license', 'pickup', 'gstno', 'nabl_iso_certified']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('assets/image/');
            $image->move($destinationPath, $imageName);
            $data['image'] = $imageName;

            // Delete old image if exists
            if ($laboratorie->image && File::exists($destinationPath . $laboratorie->image)) {
                File::delete($destinationPath . $laboratorie->image);
            }
        }

        // --- TEST DATA ---
        $tests = $request->test ?? [];
        $prices = $request->price ?? [];
        $homeprices = $request->homeprice ?? [];
        $reports = $request->report ?? [];
        $offer_visiting_prices = $request->offer_visiting_price ?? [];
        $offer_home_prices = $request->offer_home_price ?? [];

        $testData = [];

        foreach ($tests as $key => $testId) {
            if (!empty($testId)) {
                $testData[] = [
                    'test' => $testId,
                    'price' => $prices[$key] ?? 0,
                    'homeprice' => $homeprices[$key] ?? 0,
                    'report' => $reports[$key] ?? null,
                    'offer_visiting_price' => $offer_visiting_prices[$key] ?? null,
                    'offer_home_price' => $offer_home_prices[$key] ?? null,
                ];
            }
        }

        $data['test'] = json_encode($testData);

        // --- PACKAGE DATA ---
        $package_names = $request->package_name ?? [];
        $package_descriptions = $request->package_description ?? [];
        $package_visiting_prices = $request->package_visiting_price ?? [];
        $package_home_prices = $request->package_home_price ?? [];
        $package_reports = $request->package_report ?? [];
        $package_offer_visiting_prices = $request->package_offer_visiting_price ?? [];
        $package_offer_home_prices = $request->package_offer_home_price ?? [];
        $package_categories = $request->package_category ?? [];

        $packageData = [];

        foreach ($package_names as $key => $name) {
            if (!empty($name)) {
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
        }

        // Update linked user account
        $user = User::find($laboratorie->user_id);
        if ($user) {
            $user->name = $request->username ?? $user->name;
            $user->email = $request->email ?? $user->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();
        }

        $data['package_details'] = json_encode($packageData);

        // Update the laboratory record
        $laboratorie->update($data);

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

            [$userLat, $userLon] = array_map('trim', explode(',', $latlong));
            $apiKey = env('GOOGLE_MAPS_API_KEY');

            // Get city from coordinates using Geocoding API
            $geoUrl = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$userLat,$userLon&key=$apiKey";
            $geoData = json_decode(file_get_contents($geoUrl), true);

            if (!$geoData || $geoData['status'] !== 'OK') {
                return response()->json(['status' => false, 'message' => 'Could not determine city from location'], 400);
            }

            $city = collect($geoData['results'][0]['address_components'])->first(fn($comp) => in_array('locality', $comp['types']))['long_name'] ?? null;

            if (!$city) {
                return response()->json(['status' => false, 'message' => 'City not found in address data'], 400);
            }

            // Get labs in that city
            $labs = Laboratories::where('city', $city)->get(['id', 'lab_name', 'pickup', 'latitude', 'longitude', 'image']);

            $result = [];

            foreach ($labs as $lab) {
                if ($lab->latitude && $lab->longitude) {
                    $directionUrl = "https://maps.googleapis.com/maps/api/directions/json?origin=$userLat,$userLon&destination={$lab->latitude},{$lab->longitude}&key=$apiKey";
                    $data = json_decode(file_get_contents($directionUrl), true);

                    if ($data['status'] === 'OK') {
                        $distanceMeters = $data['routes'][0]['legs'][0]['distance']['value'];
                        $distanceKm = round($distanceMeters / 1000, 2);

                        // Get rating
                        $ratingData = Rating::where('rateable_type', 'Laboratory')->where('rateable_id', $lab->id)->selectRaw('AVG(rating) as avg_rating, COUNT(*) as rating_count')->first();

                        $formattedRating = $ratingData->rating_count > 0 ? round($ratingData->avg_rating, 1) . ' (' . $ratingData->rating_count . ')' : null;

                        $imageArray = [];
                        if (!empty($lab->image)) {
                            $rawImages = json_decode($lab->image, true); // Try JSON first
                            if (json_last_error() === JSON_ERROR_NONE && is_array($rawImages)) {
                                $imageArray = array_map(fn($img) => url('storage/lab_images/' . $img), $rawImages);
                            } else {
                                // fallback: comma-separated string
                                $imageArray = array_map(fn($img) => url('assets/image/' . trim($img)), explode(',', $lab->image));
                            }
                        }

                        $result[] = [
                            'id' => $lab->id,
                            'lab_name' => $lab->lab_name,
                            'pickup' => $lab->pickup,
                            'latitude' => $lab->latitude,
                            'longitude' => $lab->longitude,
                            'image' => $imageArray,
                            'distance_km' => $distanceKm,
                            'rating' => $formattedRating,
                            'rating_value' => $ratingData->avg_rating ?? 0, // used for sorting
                        ];
                    }
                }
            }

            // Sort by rating descending
            usort($result, fn($a, $b) => $b['rating_value'] <=> $a['rating_value']);

            // Remove rating_value before returning
            $result = array_map(fn($lab) => array_diff_key($lab, ['rating_value' => '']), $result);

            return response()->json([
                'status' => true,
                'city' => $city,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getAllLaboratory: ' . $e->getMessage());
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Something went wrong. Please try again later.',
                    'error' => $e->getMessage(), // Consider removing in production
                ],
                500,
            );
        }
    }

    public function getLaboratoryDetailsById($laboratorie_id)
    {
        try {
            $userId = $laboratorie_id; // pulled from route

            if (!$userId) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'laboratorie_id is required',
                    ],
                    400,
                );
            }

            $lab = Laboratories::where('id', $userId)->first();

            if (!$lab) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Laboratory not found.',
                    ],
                    404,
                );
            }

            // Process images
            $imageArray = [];
            if (!empty($lab->image)) {
                $rawImages = json_decode($lab->image, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($rawImages)) {
                    $imageArray = array_map(fn($img) => url('storage/lab_images/' . $img), $rawImages);
                } else {
                    $imageArray = array_map(fn($img) => url('assets/image/' . trim($img)), explode(',', $lab->image));
                }
            }

            // Decode test and package_details
            $tests = [];
            $packages = [];

            if (!empty($lab->test)) {
                $rawTests = json_decode($lab->test, true);

                if (is_array($rawTests)) {
                    // Extract all test IDs
                    $testIds = collect($rawTests)->pluck('test')->filter(fn($id) => is_numeric($id))->unique()->values()->toArray();

                    // Fetch test names
                    $testMap = LabTest::whereIn('id', $testIds)->pluck('name', 'id')->toArray();

                    // Map names into the test list
                    foreach ($rawTests as $item) {
                        $testId = $item['test'] ?? null;
                        unset($item['test']); // Remove old 'test' key
                        $item['test_id'] = $testId;
                        $item['test_name'] = $testMap[$testId] ?? null;
                        $tests[] = $item;
                    }
                }
            }

            if (!empty($lab->package_details)) {
                $rawPackages = json_decode($lab->package_details, true);

                if (is_array($rawPackages)) {
                    $categoryIds = [];

                    // Collect all IDs (flattened)
                    foreach ($rawPackages as $pkg) {
                        if (!empty($pkg['package_category'])) {
                            if (is_array($pkg['package_category'])) {
                                $categoryIds = array_merge($categoryIds, $pkg['package_category']);
                            } else {
                                $categoryIds[] = $pkg['package_category'];
                            }
                        }
                    }

                    $categoryIds = array_unique($categoryIds);

                    // Get category names mapped by ID
                    $categoryMap = PackageCategory::whereIn('id', $categoryIds)->pluck('name', 'id')->toArray();

                    foreach ($rawPackages as $pkg) {
                        $pkgWithNames = $pkg;

                        // Remove ID field
                        unset($pkgWithNames['package_category']);

                        // Add names
                        $pkgWithNames['package_category_name'] = null;

                        if (!empty($pkg['package_category'])) {
                            if (is_array($pkg['package_category'])) {
                                $names = [];
                                foreach ($pkg['package_category'] as $id) {
                                    if (isset($categoryMap[$id])) {
                                        $names[] = $categoryMap[$id];
                                    }
                                }
                                $pkgWithNames['package_category_name'] = $names;
                            } else {
                                $pkgWithNames['package_category_name'] = $categoryMap[$pkg['package_category']] ?? null;
                            }
                        }

                        $packages[] = $pkgWithNames;
                    }
                }
            }

            // Rating data
            $ratingData = Rating::where('rateable_type', 'Laboratory')->where('rateable_id', $lab->user_id)->selectRaw('AVG(rating) as avg_rating, COUNT(*) as rating_count')->first();

            $formattedRating = $ratingData->rating_count > 0 ? round($ratingData->avg_rating, 1) . ' (' . $ratingData->rating_count . ')' : null;

            // Final response
            return response()->json([
                'status' => true,
                'data' => [
                    'id' => $lab->id,
                    'lab_name' => $lab->lab_name,
                    'pickup' => $lab->pickup,
                    'latitude' => $lab->latitude,
                    'longitude' => $lab->longitude,
                    'city' => $lab->city,
                    'address' => $lab->address ?? null,
                    'contact' => $lab->phone ?? null,
                    'email' => $lab->email ?? null,
                    'image' => $imageArray,
                    'rating' => $formattedRating,
                    'nabl_iso_certified' => $lab->nabl_iso_certified == 1 ? 'Yes' : 'No',
                    'test' => $tests,
                    'package_details' => $packages,
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Validation error.',
                    'errors' => $e->errors(),
                ],
                422,
            );
        } catch (\Exception $e) {
            Log::error('Error in getLaboratoryDetailsById: ' . $e->getMessage());
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

    public function getTestDetailsById($test_id)
    {
        $test = LabTest::find($test_id);

        if (!$test) {
            return response()->json([
                'success' => false,
                'message' => 'Test not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $test,
        ]);
    }

    public function getPackageDetailsByName(Request $request, $packageName)
    {
        $labId = $request->query('laboratory_id');

        if (!$labId) {
            return response()->json([
                'success' => false,
                'message' => 'Laboratory ID is missing.',
            ], 400);
        }

        $lab = Laboratories::find($labId);

        if (!$lab) {
            return response()->json([
                'success' => false,
                'message' => 'Laboratory not found.',
            ], 404);
        }

        $packageDetails = json_decode($lab->package_details, true);

        $matchedPackage = collect($packageDetails)->firstWhere('package_name', $packageName);

        if (!$matchedPackage) {
            return response()->json([
                'success' => false,
                'message' => 'Package not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'lab_name' => $lab->lab_name, // 👈 add lab name here
            'data' => $matchedPackage,
        ]);
    }

}
