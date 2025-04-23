<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $user=User::all();
       
       return view('user.index',compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' =>'required|email|unique:users,email' , 
           'role_id' => '',        
        ]); 
      User::create($request->all());
        return redirect()->route('user.index')

                        ->with('success','Pharmacist created successfully.');
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
        $roles = Role::all();
        $user = User::find($id);
        // dd($pharmacies);
        
        return view('user.edit',compact('user','roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     $user= User::find($id);
    // $validatedData=$request->validate([
    //     'name' => 'required',
    //     'email' =>'required' ,
    //     'role_id' => 'required', 
    // ]); 

    // // Update the record
    // $user->update($validatedData);

    // // Redirect back with success message
    // return redirect()->route('user.index')
    //                  ->with('success', 'Pharmacy updated successfully!');
    // }
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
    
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role_id' => '',
            'password' => 'nullable|min:6|confirmed', // optional password field
        ]);
    
        // If password is filled, hash it and add to update data
        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($request->password);
        } else {
            unset($validatedData['password']); // Don't override with null
        }
    
        // Update the user with validated data
        $user->update($validatedData);
    
        return redirect()->route('user.index')
                         ->with('success', 'User updated successfully!');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user=User::find($id);
        // dd( $user->delete());
        $user->delete();

       return redirect()->route('user.index')
                        ->with('success','user deleted successfully');
    }
}
