<?php

namespace App\Http\Controllers;

use App\Imports\MedicineImport;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Medicine::query();
    
        // Check if search parameter exists and is not empty
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            
            // Search both product_id and product_name
            $query->where(function ($q) use ($searchTerm) {
                $q->where('product_id', 'like', '%' . $searchTerm . '%')
                  ->orWhere('product_name', 'like', '%' . $searchTerm . '%');
            });
        }
    
        // Paginate the results, you can change 100 to whatever number you want per page
        $medicines = $query->paginate(100);
    
        // Return view with medicines data
        return view('medicine.index', compact('medicines'));
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
        $medicines = Medicine::find($id);
       
        return view('medicine.show', compact('medicines'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    public function import(Request $request) 
    {
        // Validate incoming request data
        $request->validate([
            'file' => 'required|max:2048',
        ]);
   
      Excel::import(new MedicineImport, $request->file('file')) ;
                 
        return back()->with('success', 'Medicine imported successfully.');
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
