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
    public function index()
    { 
        $medicines=Medicine::all();
        return view('medicine.index',compact('medicines'));
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
        //
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

        Excel::import(new MedicineImport, $request->file('file'));

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


    // search medicine 
    public function search(Request $request)
    {
        $query = $request->query('query');

        if (!$query) {
            return response()->json(['message' => 'Query parameter is required.'], 400);
        }

        $results = Medicine::where('salt_composition', 'LIKE', "%$query%")
            ->orWhere('product_name', 'LIKE', "%$query%")
            ->select('id', 'product_id', 'product_name', 'salt_composition', 'packaging_detail', 'image_url')
            ->get()
            ->map(function ($item) {
                $baseUrl = url('storage/medicines');

                $item->image_url = collect(explode(',', $item->image_url))
                    ->map(function ($img) use ($baseUrl) {
                        $imgName = trim(basename($img)); // ensures no double URL prefix
                        return "{$baseUrl}/{$imgName}";
                    });

                return $item;
            });
        return response()->json($results);
    }


    //     public function search($salt_composition){
    // return ['result'=>'serching working '.$salt_composition];
    //     }



    public function medicineByProductId($productId)
    {
        $medicine = Medicine::where('product_id', $productId)->first();
    
        if (!$medicine) {
            return response()->json(['message' => 'Medicine not found.'], 404);
        }
    
        // Explode image_url into array with full URLs
        $baseUrl = url('storage/medicines');
        $medicine->image_url = collect(explode(',', $medicine->image_url))
            ->map(function ($img) use ($baseUrl) {
                $imgName = trim(basename($img));
                return "{$baseUrl}/{$imgName}";
            });
    
        return response()->json([
            'data' => $medicine]);
    }
    


}
