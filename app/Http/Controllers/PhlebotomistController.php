<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Phlebotomist;
use App\Models\Laboratories;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PhlebotomistController extends Controller
{

    public function index(Request $request)
    {
        $userId = Auth::user();
        if ($request->ajax()) {
            $laboratory = Laboratories::where('user_id', $userId->id)->first();
            // dd($userId->id);



            $data = Phlebotomist::where('laboratory_id', $laboratory->id)->latest();

            return DataTables::of($data)
                ->addIndexColumn()


                ->addColumn('action', function ($row) {
                    return '
                    <div class="dropdown">
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="dropdown">Action</button>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="' . route('phlebotomist.edit', $row->id) . '" class="dropdown-item">Edit</a>
                            </li>
                            <li>
                                <button type="button" onclick="deleteUser(' . $row->id . ')" class="dropdown-item text-danger">Delete</button>
                            </li>
                        </ul>
                    </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('phlebotomist.index');
    }


    public function create()
    {
        return view('phlebotomist.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $laboratory = Laboratories::where('user_id', $user->id)->first();

        $request->validate([
            'phlebotomists_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'contact_number' => 'required|string',
            'city' => 'nullable',
            'state' => 'nullable',
            'pincode' => 'nullable',
            'address' => 'nullable',
            'username' => 'required',
            'password' => 'required',
        ]);


        // Create user
        $roleId = \App\Models\Role::where('name', 'delivery_person')->value('id');

        $createdUser = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $roleId,
        ]);

        // Create phlebotomist
        Phlebotomist::create([
            'laboratory_id' => $laboratory->id,
            'phlebotomists_name' => $request->phlebotomists_name,
            'contact_number' => $request->contact_number,
            'email' => $request->email,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'address' => $request->address,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);


        return redirect()->route('phlebotomist.index')->with('success', 'Phlebotomist created successfully!');
    }




    public function edit($id)
    {
        $laboratory = Laboratories::where('user_id', Auth::user()->id)->firstOrFail();
        $phlebotomist = phlebotomist::where('laboratory_id', $laboratory->id)->findOrFail($id);

        return view('phlebotomist.edit', compact('phlebotomist'));
    }

    public function update(Request $request,string $id)
{
   $laboratory = Laboratories::where('user_id', Auth::user()->id)->firstOrFail();
        $phlebotomist = phlebotomist::where('laboratory_id', $laboratory->id)->findOrFail($id);

    // 2️⃣ Validate, using correct user_id
   $validation = validator($request->all(), [
        'phlebotomists_name' => 'required',
       'email' => 'required|email',
        'contact_number' => 'required|string',
        'city' => 'required',
        'state' => 'required',
        'pincode' => 'required',
        'address' => 'required',
        'username' => 'required',
        'password' => 'nullable',
    ]);

    if ($validation->fails()) {
            return back()->withErrors($validation)->withInput();
        }
    // 3️⃣ Update related User
    $user = User::find($phlebotomist->user_id);
    if ($user) {
        $user->name = $request->username;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
    }

    // 4️⃣ Update phlebotomist record
    $phlebotomist->update([
        'phlebotomists_name' => $request->phlebotomists_name,
        'contact_number' => $request->contact_number,
        'email' => $request->email,
        'city' => $request->city,
        'state' => $request->state,
        'pincode' => $request->pincode,
        'address' => $request->address,
        'username' => $request->username,
        // 'password' => $request->filled('password') ? Hash::make($request->password) : $phlebotomist->password,
    ]);

    return redirect()->route('phlebotomist.index')->with('success', 'Phlebotomist updated successfully!');
}



    public function destroy($id)
    {
        $laboratory = Laboratories::where('user_id', Auth::user()->id)->firstOrFail();
        $phlebotomist = phlebotomist::where('laboratory_id', $laboratory->id)->findOrFail($id);
        // $phlebotomist = phlebotomist::where('laboratory_id', auth()->user()->id)->findOrFail($id);
        $phlebotomist->delete();

        return response()->json(['status' => true, 'message' => 'phlebotomist deleted successfully.']);
    }
}
