<?php

namespace App\Http\Controllers;

use App\Models\DeliveryPerson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class DeliveryPersonController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DeliveryPerson::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="' . route('delivery_person.show', $row->id) . '" class="dropdown-item">View</a></li>
                                <li><a href="' . route('delivery_person.edit', $row->id) . '" class="dropdown-item">Edit</a></li>
                                <li>
                                    <button class="dropdown-item btn-delete-delivery" data-id="' . $row->id . '" data-url="' . route('delivery_person.destroy', $row->id) . '">
                                        Delete
                                    </button>
                                </li>
                            </ul>
                        </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('delivery_person.index');
    }

    public function create()
    {
        return view('delivery_person.create');
    }

    public function store(Request $request)
    {
        $params = $request->all();

        $validation = validator($params, [
            'delivery_person_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|min:11|numeric',
            'city' => 'required',
            'state' => 'required',
            'pincode' => 'required',
            'address' => 'required',
            // 'latitude' => 'required',
            // 'longitude' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validation->fails()) {
            return back()->withErrors($validation)->withInput();
        }

        $roleId = \App\Models\Role::where('name', 'Delivery person')->value('id');

        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $roleId
        ]);

        $params['user_id'] = $user->id;

        DeliveryPerson::create($params);

        return redirect()->route('delivery_person.index')->with('success', 'Delivery Person created successfully!');
    }

    public function show($id)
    {
        $delivery = DeliveryPerson::with('user')->findOrFail($id);
        return view('delivery_person.show', compact('delivery'));
    }

    public function edit($id)
    {
        $delivery = DeliveryPerson::findOrFail($id);
        return view('delivery_person.edit', compact('delivery'));
    }

    public function update(Request $request, string $id)
    {
        $delivery = DeliveryPerson::findOrFail($id);

        $validation = validator($request->all(), [
            'delivery_person_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|min:11|numeric',
            'city' => 'required',
            'state' => 'required',
            'pincode' => 'required',
            'address' => 'required',
            // 'latitude' => 'required',
            // 'longitude' => 'required',
            'username' => 'required',
        ]);

        if ($validation->fails()) {
            return back()->withErrors($validation)->withInput();
        }

        $user = User::find($delivery->user_id);
        if ($user) {
            $user->name = $request->username;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();
        }

        $data = $request->only([
            'delivery_person_name',
            'email',
            'phone',
            'city',
            'state',
            'pincode',
            'address',
            // 'latitude',
            // 'longitude',
            'username',
        ]);

        $delivery->update($data);

        return redirect()->route('delivery_person.index')->with('success', 'Delivery Person updated successfully!');
    }

    public function destroy($id, Request $request)
    {
        try {
            $delivery = DeliveryPerson::findOrFail($id);
            $delivery->delete();

            if ($request->ajax()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Delivery Person deleted successfully'
                ]);
            }

            return redirect()->route('delivery_person.index')->with('success', 'Delivery Person deleted successfully');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to delete delivery person. Error: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('delivery_person.index')->with('error', 'Failed to delete delivery person');
        }
    }

    // Optional API
    public function getDeliveryPersons()
    {
        $deliveries = DeliveryPerson::all();

        return response()->json([
            'success' => true,
            'data' => $deliveries
        ], 200);
    }
}
