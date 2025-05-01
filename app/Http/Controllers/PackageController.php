<?php

namespace App\Http\Controllers;

use App\Models\Laboratories;
use App\Models\LabPackages;
use App\Models\PackageCategory;
use Illuminate\Http\Request;
use DataTables;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = LabPackages::with(['laboratory', 'packageCategory']);
    
            return DataTables::of($data)

                ->addIndexColumn()
    
                ->editColumn('lab_id', function ($row) {
                    return $row->laboratory->lab_name ?? '-';
                })
    
                ->editColumn('package_category_id', function ($row) {
                    return $row->packageCategory->name ?? '-';
                })
    
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex gap-2">
                    <a href="' . route('labPackage.show', $row->id) . '" class="btn btn-sm btn-info">View</a>
                    <a href="' . route('labPackage.edit', $row->id) . '" class="btn  btn-warning btn-sm" data-bs-toggle="tooltip" title="Edit">Edit</a>
                     <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deletePackage">Delete</a>
                    </div>';
    return $btn;
                })
    
                ->rawColumns(['action'])
                ->make(true);
        }
       return view('laboratorie.labpackages.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $labData=Laboratories::all();
        $packageCategory=PackageCategory::all();
        return view('laboratorie.labpackages.create',compact('labData','packageCategory'));
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      
            $validator=$request->validate([
                'lab_id' => 'required',
                'package_category_id' => 'required',
                'package_name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'home_price' => 'required|numeric|min:0',
                'description' => 'nullable|string',
            ]);
        
            LabPackages::create($validator);
        
            return redirect()->route('labPackage.index')->with('success', 'Lab package assigned successfully.');
      
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $labPackages = LabPackages::findOrFail($id);
        return view('laboratorie.labpackages.show', compact('labPackages'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $labPackage = LabPackages::findOrFail($id);
        $labData = Laboratories::all();
        $packageCategory = PackageCategory::all();
        // dd($pharmacies);

        return view('laboratorie.labpackages.edit', compact('labPackage','labData','packageCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
      
            $validator = $request->validate([
                'lab_id' => 'required',
                'package_category_id' => 'required',
                'package_name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'home_price' => 'required|numeric|min:0',
                'description' => 'nullable|string',
            ]);
        
            $labPackage = LabPackages::findOrFail($id);
        
            $labPackage->update($validator);
        
            return redirect()->route('labPackage.index')->with('success', 'Lab package updated successfully.');
     
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        LabPackages::find($id)->delete();

        return response()->json(['success' => 'LabPackages deleted successfully.']);
    }
}
