<?php

namespace App\Http\Controllers;

use App\Imports\OtcImport;
use App\Models\Otcmedicine;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Pagination\LengthAwarePaginator;

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
    public function productListByCategory(Request $request, $categoryName)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 20);

        $packageFilter = $request->query('package'); // Now using 'package'
        $productFormFilter = $request->query('product_form'); // e.g., 'Oil'

        $packageArray = $packageFilter ? array_map('trim', explode(',', $packageFilter)) : null;
        $productFormArray = $productFormFilter ? array_map('trim', explode(',', $productFormFilter)) : null;

        // Filter products in category
        $products = Otcmedicine::where('category', $categoryName)
            ->when($packageArray, function ($query) use ($packageArray) {
                $query->whereIn('package', $packageArray);
            })
            ->when($productFormArray, function ($query) use ($productFormArray) {
                $query->whereIn('product_form', $productFormArray);
            })
            ->get();


        if ($products->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No products found in this category.',
            ], 404);
        }

        // Format results
        $baseUrl = url('medicines');
        $defaultImage = "{$baseUrl}/placeholder.png";

        $formatted = $products->map(function ($item) use ($baseUrl, $defaultImage) {
            return [
                'product_id' => $item->otc_id,
                'product_name' => $item->name,
                'category' => $item->category,
                'packaging' => $item->packaging,
                'product_form' => $item->product_form,
                'imageUrls' => $item->image_url
                    ? collect(explode(',', $item->image_url))->map(fn($img) => "{$baseUrl}/" . trim(basename($img)))
                    : [$defaultImage],
            ];
        });

        // Paginate
        $total = $formatted->count();
        $paginated = $formatted->forPage($page, $perPage)->values();

        $paginator = new LengthAwarePaginator(
            $paginated,
            $total,
            $perPage,
            $page,
            ['path' => url()->current(), 'query' => $request->query()]
        );

        return response()->json([
            'status' => true,
            'category' => $categoryName,
            'products' => $paginator->items(),
            'meta' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
            ]
        ]);
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getFilters(Request $request)
    {
        $categoryName = $request->name;

        // Base query with optional category filter
        $otcQuery = Otcmedicine::query();

        if ($categoryName) {
            $otcQuery->where('category', 'LIKE', "%$categoryName%");
        }

        // Unique product forms from OTC
        $productForms = (clone $otcQuery)
            ->whereNotNull('product_form')
            ->where('product_form', '!=', '')
            ->distinct()
            ->pluck('product_form')
            ->unique()
            ->values();

        // Unique packages from OTC
        $package = (clone $otcQuery)
            ->whereNotNull('package')
            ->where('package', '!=', '')
            ->distinct()
            ->pluck('package')
            ->unique()
            ->values();

        return response()->json([
            'status' => true,
            'filters' => [
                'product_forms' => $productForms,
                'package' => $package,
            ]
        ]);

    }
}
