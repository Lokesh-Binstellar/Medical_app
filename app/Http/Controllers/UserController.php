<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('role')->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('role', function ($row) {
                    return $row->role->name ?? '';
                })
                ->addColumn('action', function ($row) {
                    return '
    <div class="dropdown">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="dropdown">Action</button>
        <ul class="dropdown-menu">
            <li>
                <a class="dropdown-item" href="' . route('user.edit', $row->id) . '">Edit</a>
            </li>
            <li>
                <button class="dropdown-item btn-delete-user" data-id="' . $row->id . '" data-url="' . route('user.destroy', $row->id) . '">Delete</button>
            </li>
        </ul>
    </div>';
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('user.index'); // Assuming this is the blade filename
    }
    // public function index()
    // {
    //    $user=User::all();

    //    return view('user.index',compact('user'));
    // }

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
            'email' => 'required|email|unique:users,email',
            'role_id' => '',
        ]);
        User::create($request->all());
        return redirect()->route('user.index')

            ->with('success', 'Pharmacist created successfully.');
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

        return view('user.edit', compact('user', 'roles'));
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
    public function destroy(string $id, Request $request)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            if ($request->ajax()) {
                return response()->json([
                    'status' => true,
                    'message' => 'User deleted successfully'
                ]);
            }

            return redirect()->route('user.index')->with('success', 'User deleted successfully');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to delete user. Error: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('user.index')->with('error', 'Failed to delete user');
        }
    }
}
