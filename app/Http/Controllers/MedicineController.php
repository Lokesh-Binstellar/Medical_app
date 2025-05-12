<?php

namespace App\Http\Controllers;

use App\Imports\MedicineImport;
use App\Models\Medicine;
use App\Models\Otcmedicine;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Medicine::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
                <div class="dropdown">
                  <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="dropdown">Action</button>
  <ul class="dropdown-menu">
                    <li>
                     <a href="' . route('medicine.show', $row->id) . '"class="dropdown-item" >View
    </a>
                    </li>

                    
                  </ul>
                </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        // $tests = LabTest::all();
        return view('medicine.index');
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

        // Check if the query parameter is present
        if (!$query) {
            return response()->json([
                'status'=>false,
                'message' => 'Query parameter is required.'
            ],
                 400);
        }

        // Search from Medicine
        $medicines = Medicine::where('salt_composition', 'LIKE', "%$query%")
            ->orWhere('product_name', 'LIKE', "%$query%")
            ->select('id', 'product_id', 'product_name', 'salt_composition', 'packaging_detail', 'image_url')
            ->get()
            ->map(function ($item) {
                $baseUrl = url('storage/medicines');
                $item->image_url = $item->image_url
                    ? collect(explode(',', $item->image_url))->map(fn($img) => "{$baseUrl}/" . trim(basename($img)))
                    : [];
                $item->type = 'medicine';
                return $item;
            });

        // Search from OtcMedicine
      $otc = Otcmedicine::where('name', 'LIKE', "%$query%")
    ->select('id', 'otc_id', 'name', 'packaging', 'image_url')
    ->get()
    ->map(function ($item) {
        $baseUrl = url('storage/medicines');
        $item->image_url = $item->image_url
            ? collect(explode(',', $item->image_url))->map(fn($img) => "{$baseUrl}/" . trim(basename($img)))
            : [];

        // Remap fields to match Medicine structure
        $item->product_id = $item->otc_id;
        $item->product_name = $item->name;
        $item->packaging_detail = $item->packaging;
        $item->type = 'otc';

        // Optionally remove unnecessary original fields
        unset($item->otc_id, $item->name, $item->packaging);

        return $item;
    });


        // Merge both collections
        $results = $medicines->merge($otc);

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }

    public function medicineByProductId(Request $request, $id)
    {
        // echo $id;die;
        // Step 1: Check if 'id' parameter is passed
        if (empty($id)) {

            return response()->json([
                'success' => false,
                'message' => 'ID parameter is required.'
            ], 400);
        }

        // Step 2: Get the product ID from the query
        $productId = $id;

        // Step 3: Search in both medicines and otcmedicines
        $medicine = Medicine::where('product_id', $productId)->first();
        $otc = Otcmedicine::where('otc_id', $productId)->first();

        // Step 4: If neither found
        if (!$medicine && !$otc) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in either table.'
            ], 404);
        }

        // Step 5: Prepare base URL
        $baseUrl = url('storage/medicines');

        // Step 6: Format image URLs if present
        if ($medicine) {
            $medicine->image_url = $medicine->image_url
                ? collect(explode(',', $medicine->image_url))->map(fn($img) => $baseUrl . '/' . trim(basename($img)))->toArray()
                : [];
        }

        if ($otc) {
            $otc->image_url = $otc->image_url
                ? collect(explode(',', $otc->image_url))->map(fn($img) => $baseUrl . '/' . trim(basename($img)))->toArray()
                : [];
        }

        // Step 7: Return whichever was found
        return response()->json([
            'success' => true,
            'data' => $medicine ?? $otc,
            'source' => $medicine ? 'medicines' : 'otcmedicines'
        ], 200);
    }





}
