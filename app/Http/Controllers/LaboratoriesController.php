<?php

namespace App\Http\Controllers;

use App\Models\Laboratories;
use App\Models\User;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LaboratoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $laboratorie=Laboratories::all();
        return view('laboratorie.index',compact('laboratorie'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('laboratorie.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->all();

        // Validate
        $validation = validator($params, [
            'lab_name' => 'required',
            'owner_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|min:11|numeric',
            'city'=>'required',
            'state'=>'required',
            'pincode'=>'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'image' => 'required|file',
            'username' => 'required',
            'password' => 'required',
            'license' => 'required',
            'pickup'=>'required',
        ]);
    
        if ($validation->fails()) {
            return back()->withErrors($validation)->withInput();
        }
        $roleId = \App\Models\Role::where('name', 'laboratory')->value('id');
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
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = public_path('assets/image') . '/' . $imageName;
            $image->move(public_path('assets/image'), $imageName);
    
            $imageData = file_get_contents($imagePath);
            $type = pathinfo($imagePath, PATHINFO_EXTENSION);
            $base64Image = 'data:image/' . $type . ';base64,' . base64_encode($imageData);
    
            $params['image'] = $base64Image;
    
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
    
        // Create Pharmacy
        Laboratories::create($params);
    
     

        return redirect()->route('laboratorie.index')

                        ->with('success','laboratorie created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $lab = Laboratories::with('user')->findOrFail($id);
        return view('laboratorie.show', compact('lab'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $laboratorie = Laboratories::find($id);
        // dd($laboratorie);
        
        return view('laboratorie.edit',compact('laboratorie'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
         // Validate the request
         $laboratorie = Laboratories::findOrFail($id);

         // Validate input
         $validation = validator($request->all(), [
             'lab_name' => 'required',
             'owner_name' => 'required',
             'email' => 'required|email',
             'phone' => 'required|min:11|numeric',
             'city' => 'required',
             'state' => 'required',
             'pincode' => 'required|max:9',
             'address' => 'required',
             'latitude' => 'required',
             'longitude' => 'required',
             'username' => 'required',
             'license' => 'required',
             'pickup'=>'required',
             'image' => 'nullable|file', // optional for update
         ]);
 
         if ($validation->fails()) {
             return back()->withErrors($validation)->withInput();
         }
 
         // Update related user
         $user = User::find($laboratorie->user_id);
         if ($user) {
             $user->name = $request->username;
             $user->email = $request->email;
             if ($request->filled('password')) {
                 $user->password = Hash::make($request->password);
             }
             $user->save();
         }
 
         // Prepare updated pharmacy data
         $data = $request->only([
             'lab_name',
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
             'pickup'
         ]);
 
         // Handle image update
         if ($request->hasFile('image')) {
             $image = $request->file('image');
             $imageName = time() . '_' . $image->getClientOriginalName();
             $imagePath = public_path('assets/image') . '/' . $imageName;
             $image->move(public_path('assets/image'), $imageName);
 
             $imageData = file_get_contents($imagePath);
             $type = pathinfo($imagePath, PATHINFO_EXTENSION);
             $base64Image = 'data:image/' . $type . ';base64,' . base64_encode($imageData);
 
             $data['image'] = $base64Image;
 
             if (File::exists($imagePath)) {
                 File::delete($imagePath);
             }
         }
 
         // Update pharmacy record
         $laboratorie->update($data);
        return redirect()->route('laboratorie.index')
                         ->with('success', 'laboratorie updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $laboratorie=Laboratories::findOrFail($id);
        // dd( $laboratorie);
        $laboratorie->delete();

       return redirect()->route('laboratorie.index')
                        ->with('success','laboratorie deleted successfully');
    }
}
