<?php

namespace App\Http\Controllers;

use App\Imports\OtcImport;
use App\Models\Otcmedicine;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class OtcController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Otcmedicine::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
                <div class="dropdown">
                  <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="dropdown">Action</button>
  <ul class="dropdown-menu">
                    <li>
                     <a href="' . route('otcmedicine.show', $row->id) . '"class="dropdown-item" >View
    </a>
                    </li>

                    
                  </ul>
                </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        // $tests = LabTest::all();
        return view('otcmedicine.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $otcmedicine = Otcmedicine::find($id);

        return view('otcmedicine.show', compact('otcmedicine'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function import(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'file' => 'required|max:2048',
        ]);

        $file = Excel::import(new OtcImport, $request->file('file'));

        return back()->with('success', 'OtcMedicine imported successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
