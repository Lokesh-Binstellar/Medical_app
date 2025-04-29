<?php

namespace App\Http\Controllers;

use App\Imports\LabTestImport;
use App\Models\LabTest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LabtestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tests = LabTest::all();
        return view('laboratorie.labtest.index', compact('tests'));
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
    public function import(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'file' => 'required|max:2048',
        ]);

        Excel::import(new LabTestImport, $request->file('file'));

        return back()->with('success', 'LabTest imported successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
      $tests=LabTest::find($id);
      return view('laboratorie.labtest.show', compact('tests'));

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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
