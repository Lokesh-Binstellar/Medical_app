<?php

namespace App\Http\Controllers;

use App\Events\MyEvent;
use App\Events\TestEvent;
use App\Models\Pharmacies;
use App\Models\Rating;
use App\Models\User;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class PharmaciesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = Pharmacies::all();
            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
    <div class="dropdown">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
            Action
        </button>
        <ul class="dropdown-menu">
            <li><a href="' . route('pharmacist.show', $row->id) . '" class="dropdown-item">View</a></li>
            <li><a href="' . route('pharmacist.edit', $row->id) . '" class="dropdown-item">Edit</a></li>
            <li>
                <button class="dropdown-item btn-delete-pharmacist" data-id="' . $row->id . '" data-url="' . route('pharmacist.destroy', $row->id) . '">
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
        return view('pharmacist.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pharmacist.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->all();
        $validation = validator($params, [
            'pharmacy_name' => 'required',
            'owner_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|min:11|numeric',
            'city' => 'required',
            'state' => 'required',
            'pincode' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'image' => 'nullable|file|image|max:10240',
            'username' => 'required',
            'password' => 'required',
            'license' => 'required',
        ]);

        if ($validation->fails()) {
            return back()->withErrors($validation)->withInput();
        }
        $roleId = \App\Models\Role::where('name', 'pharmacy')->value('id');


        // Create user and get ID
        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $roleId
        ]);

        $params['user_id'] = $user->id;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('assets/image/');
            $image->move($destinationPath, $imageName);
            $params['image'] = $imageName;
        }
        Pharmacies::create($params);

        return redirect()->route('pharmacist.index')->with('success', 'Pharmacy created successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pharmacy = Pharmacies::with('user')->findOrFail($id);
        return view('pharmacist.show', compact('pharmacy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pharmacies = Pharmacies::find($id);
        // dd($pharmacies);

        return view('pharmacist.edit', compact('pharmacies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request
        $pharmacy = Pharmacies::findOrFail($id);

        // Validate input
        $validation = validator($request->all(), [
            'pharmacy_name' => 'required',
            'owner_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|min:11|numeric',
            'city' => 'required',
            'state' => 'required',
            'pincode' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'username' => 'required',
            'license' => 'required',
            'image' => 'nullable|image|max:10240',
        ]);

        if ($validation->fails()) {
            return back()->withErrors($validation)->withInput();
        }

        // Update related user
        $user = User::find($pharmacy->user_id);
        if ($user) {
            $user->name = $request->username;
            $user->email = $request->email;

            // Only update password if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();
        }


        // Prepare updated pharmacy data
        $data = $request->only([
            'pharmacy_name',
            'owner_name',
            'email',
            'phone',
            'city',
            'state',
            'pincode',
            'address',
            'latitude',
            'longitude',
            'username',
            'license',
        ]);

        // Image upload and old delete
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('assets/image/');
            $image->move($destinationPath, $imageName);
            $data['image'] = $imageName;

            if ($pharmacy->image && File::exists($destinationPath . $pharmacy->image)) {
                File::delete($destinationPath . $pharmacy->image);
            }
        }

        // Update pharmacy record
        $pharmacy->update($data);

        return redirect()->route('pharmacist.index')->with('success', 'Pharmacy updated successfully!');
    }

    // Api
    public function getPharmacy(Request $request)
    {
        $latlong = $request->latlong;
        if (!$latlong) {
            return response()->json(['status' => false, 'message' => 'latlong is required'], 400);
        }

        [$userLat, $userLon] = explode(',', $latlong);
        $userLat = trim($userLat);
        $userLon = trim($userLon);

        $apiKey = env('GOOGLE_MAPS_API_KEY'); // Set this in your .env

        $pharmacies = Pharmacies::all();

        $nearbyPharmacies = [];

        foreach ($pharmacies as $pharmacy) {
            $pharmacyLat = $pharmacy->latitude;
            $pharmacyLon = $pharmacy->longitude;

            $url = "https://maps.googleapis.com/maps/api/directions/json?origin=$userLat,$userLon&destination=$pharmacyLat,$pharmacyLon&key=$apiKey";

            $response = file_get_contents($url);
            $data = json_decode($response, true);

            if ($data['status'] === 'OK') {
                $distanceMeters = $data['routes'][0]['legs'][0]['distance']['value'];
                $distanceKm = $distanceMeters / 1000;




                if ($distanceKm <= 10) {

                    // Fetch rating info
                    $ratingData = Rating::where('rateable_type', 'Pharmacy')
                        ->where('rateable_id', $pharmacy->user_id)
                        ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as rating_count')
                        ->first();

                    $formattedRating = $ratingData->rating_count > 0
                        ? round($ratingData->avg_rating, 1) . ' (' . $ratingData->rating_count . ')'
                        : null;

                    $nearbyPharmacies[] = [
                        'id' => $pharmacy->id,
                        'pharmacy_name' => $pharmacy->pharmacy_name,
                        'latitude' => $pharmacy->latitude,
                        'longitude' => $pharmacy->longitude,
                        'distance_km' => round($distanceKm, 2),
                        'image' => $pharmacy->image
                            ? [url('assets/image/' . basename($pharmacy->image))]
                            : [],
                        'rating' => $formattedRating,
                    ];
                }
            }
        }

        return response()->json([
            'status' => true,
            'data' => $nearbyPharmacies
        ], 200);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Request $request)
    {
        try {
            $pharmacist = Pharmacies::findOrFail($id);
            $pharmacist->delete();

            if ($request->ajax()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Pharmacist deleted successfully'
                ]);
            }

            return redirect()->route('pharmacist.index')->with('success', 'Pharmacist deleted successfully');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to delete pharmacist. Error: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('pharmacist.index')->with('error', 'Failed to delete pharmacist');
        }
    }

    public function popularpharmacydetails(Request $request, $id)
    { 
        $pharmacy = Pharmacies::find($id);
        // Fetch rating info
        $ratingData = Rating::where('rateable_type', 'Pharmacy')
            ->where('rateable_id', $pharmacy->user_id)
            ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as rating_count')
            ->first();

        $formattedRating = $ratingData->rating_count > 0
            ? round($ratingData->avg_rating, 1) . ' (' . $ratingData->rating_count . ')'
            : null;

        if ($pharmacy) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $pharmacy->id,
                    'name' => $pharmacy->pharmacy_name,
                    'owner_name' => $pharmacy->owner_name,
                    'address' => $pharmacy->address,
                    'city' => $pharmacy->city,
                    'state' => $pharmacy->state,
                    'phone' => $pharmacy->phone,
                    'email' => $pharmacy->email,
                    'license' => $pharmacy->license,
                    'image' => $pharmacy->image
                        ? [url('assets/image/' . basename($pharmacy->image))]
                        : [],
                    'rating' => $formattedRating,
                    // Add any other fields as needed
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Pharmacy not found.'
            ], 404);
        }
    }

    public function toggleStatus(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->pharmacies) {
            $pharmacy = $user->pharmacies;
            $pharmacy->status = !$pharmacy->status;
            $pharmacy->save();

            return response()->json([
                'status' => true,
                'new_status' => $pharmacy->status ? 'On' : 'Off'
            ]);
        }

        return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
    }


}
