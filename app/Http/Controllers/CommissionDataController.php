<?php

namespace App\Http\Controllers;

use App\Models\CommissionData;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CommissionDataController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = CommissionData::all();
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
            <li><a href="' . route('commission_data.edit', $row->id) . '" class="dropdown-item">Edit</a></li>
            <li>
                <button class="dropdown-item btn-delete-commission" data-id="' . $row->id . '" data-url="' . route('commission_data.destroy', $row->id) . '">
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
        return view('commission_data.index');
    }

    public function create()
    {
        return view('commission_data.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'commonAmount' => 'required|numeric',
            'gstRate' => 'required|numeric',
            'commissionBelowAmount' => 'required|numeric',
            'commissionAboveAmount' => 'required|numeric',
        ]);

        CommissionData::create($request->all());

        return redirect()->route('commission_data.index')->with('success', 'Commission setting created.');
    }

    public function edit($id)
    {
        $commission_data = CommissionData::findOrFail($id);
        return view('commission_data.edit', compact('commission_data'));
    }

    public function update(Request $request, $id)
    {
        $setting = CommissionData::findOrFail($id);

        $request->validate([
            'commonAmount' => 'required|numeric',
            'gstRate' => 'required|numeric',
            'commissionBelowAmount' => 'required|numeric',
            'commissionAboveAmount' => 'required|numeric',
        ]);

        $setting->update($request->all());

        return redirect()->route('commission_data.index')->with('success', 'Updated successfully.');
    }

public function destroy($id)
{
    $commission = CommissionData::findOrFail($id);
    $commission->delete();

    return response()->json([
        'status' => true,
        'message' => 'Commission deleted successfully.'
    ], 200); // ✅ important: HTTP 200
}


}
