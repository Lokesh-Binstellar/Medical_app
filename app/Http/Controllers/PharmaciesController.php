<?php

namespace App\Http\Controllers;

use App\Models\Pharmacies;
use App\Models\User;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PharmaciesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pharmacist = Pharmacies::all();
        return view('pharmacist.index', compact('pharmacist'));
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

        // Validate
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
            'image' => 'required|file',
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

        // Handle Image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
        
            // Generate unique image name
            $imageName = time() . '_' . $image->getClientOriginalName();
        
            // Define the destination path
            $destinationPath = public_path('assets/image');
        
            // Move image to the destination
            $image->move($destinationPath, $imageName);
        
            // Store just the image name (or relative path if preferred)
            $params['image'] = $imageName; // or use 'assets/image/' . $imageName if needed
        
            // No need to delete the file now since we're storing it
        }
        

        // Create Pharmacy
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
            'image' => 'nullable|file', // optional for update
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

        // Handle image update
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = public_path('assets/image/') . $imageName;
    
            // Move new image to folder
            $image->move(public_path('assets/image/'), $imageName);
    
            // Delete old image if exists
            if ($pharmacy->image && File::exists(public_path('assets/image/') . $pharmacy->image)) {
                File::delete(public_path('assets/image/') . $pharmacy->image);
            }
    
            // Save new image name to database
            $data['image'] = $imageName;
        }

        // Update pharmacy record
        $pharmacy->update($data);

        return redirect()->route('pharmacist.index')->with('success', 'Pharmacy updated successfully!');
    }

// Api
public function getPharmacy()
{
    $pharmacies = Pharmacies::all();

    $pharmacyData = $pharmacies->map(function ($pharmacy) {
        return [
           'id' => $pharmacy->id,
           'pharmacy_name' => $pharmacy->pharmacy_name,
           'latitude'=>$pharmacy->latitude,
           'longitude'=>$pharmacy->longitude,
           'image' => $pharmacy->image
                ? [url('assets/image/' . basename($pharmacy->image))]
                : [],
        ];
    });

    return response()->json([
        'success' => true,
        'data' => $pharmacyData
    ], 200);
}



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pharmacies = Pharmacies::findOrFail($id);
        // dd( $pharmacies);
        $pharmacies->delete();

        return redirect()->route('pharmacist.index')
            ->with('success', 'Pharmacist deleted successfully');
    }
}
