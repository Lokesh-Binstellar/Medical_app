<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Phlebotomist;
use App\Models\Laboratories;

use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PhlebotomistController extends Controller
{

    public function index(Request $request)
    {
        $userId = Auth::user();
        if ($request->ajax()) {
            $laboratory = Laboratories::where('user_id', $userId->id)->first();


            // dd($laboratory->id);
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
            'name' => 'required|string',
            'contact_number' => 'required|string',
        ]);

        phlebotomist::create([
            'name' => $request->name,
            'contact_number' => $request->contact_number,
            'laboratory_id' => $laboratory->id, // ✅ fixed
        ]);

        return redirect()->route('phlebotomist.index')->with('success', 'phlebotomist added successfully.');
    }


    public function edit($id)
    {
        $laboratory = Laboratories::where('user_id', Auth::user()->id)->firstOrFail();
        $phlebotomist = phlebotomist::where('laboratory_id', $laboratory->id)->findOrFail($id);

        return view('phlebotomist.edit', compact('phlebotomist'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'contact_number' => 'required|string',
        ]);

        $laboratory = Laboratories::where('user_id', Auth::user()->id)->firstOrFail();
        $phlebotomist = phlebotomist::where('laboratory_id', $laboratory->id)->findOrFail($id);

        $phlebotomist->update($request->only('name', 'contact_number'));

        return redirect()->route('phlebotomist.index')->with('success', 'phlebotomist updated successfully.');
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
