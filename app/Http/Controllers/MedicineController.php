<?php

namespace App\Http\Controllers;

use App\Imports\MedicineImport;
use App\Models\Medicine;
use App\Models\Otcmedicine;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Pagination\LengthAwarePaginator;

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
                     <a href="' .
                        route('medicine.show', $row->id) .
                        '"class="dropdown-item" >View
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
        $request->validate([
            'file' => 'required|max:2048',
        ]);

        Excel::import(new MedicineImport(), $request->file('file'));

        return back()->with('success', 'Medicine imported successfully.');
    }

    public function search(Request $request)
    {
        $query = $request->query('query');
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 20);

        if (!$query) {
            return response()->json([
                'status' => false,
                'message' => 'Query parameter is required.',
            ], 400);
        }

        $baseUrl = url('medicines/');
        $defaultImage = "{$baseUrl}/placeholder.png";

        // 1. Medicines by product_name
        $medicinesByName = Medicine::where('product_name', 'LIKE', "%$query%")
            ->select('id', 'product_id', 'product_name', 'salt_composition', 'packaging_detail', 'image_url')
            ->get()
            ->map(function ($item) use ($baseUrl, $defaultImage) {
                $item->image_url = $item->image_url
                    ? collect(explode(',', $item->image_url))->map(fn($img) => "{$baseUrl}/" . trim(basename($img)))
                    : [$defaultImage];
                $item->type = 'medicine';
                return $item;
            });

        // 2. OTC Medicines by name
        $otc = Otcmedicine::where('name', 'LIKE', "%$query%")
            ->select('id', 'otc_id', 'name', 'packaging', 'image_url')
            ->get()
            ->map(function ($item) use ($baseUrl, $defaultImage) {
                $item->image_url = $item->image_url
                    ? collect(explode(',', $item->image_url))->map(fn($img) => "{$baseUrl}/" . trim(basename($img)))
                    : [$defaultImage];
                $item->product_id = $item->otc_id;
                $item->product_name = $item->name;
                $item->packaging_detail = $item->packaging;
                $item->type = 'otc';
                unset($item->otc_id, $item->name, $item->packaging);
                return $item;
            });

        // 3. Medicines by salt_composition, excluding already included product_ids
        $excludedIds = $medicinesByName->pluck('product_id');
        $medicinesBySalt = Medicine::where('salt_composition', 'LIKE', "%$query%")
            ->whereNotIn('product_id', $excludedIds)
            ->select('id', 'product_id', 'product_name', 'salt_composition', 'packaging_detail', 'image_url')
            ->get()
            ->map(function ($item) use ($baseUrl, $defaultImage) {
                $item->image_url = $item->image_url
                    ? collect(explode(',', $item->image_url))->map(fn($img) => "{$baseUrl}/" . trim(basename($img)))
                    : [$defaultImage];
                $item->type = 'medicine';
                return $item;
            });

        // 4. Merge in desired order
        $merged = $medicinesByName->merge($otc)->merge($medicinesBySalt);

        // 5. Manual Pagination
        $total = $merged->count();
        $items = $merged->forPage($page, $perPage)->values();

        $paginator = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => url()->current(), 'query' => $request->query()]
        );

        return response()->json([
            'status' => true,
            'data' => $paginator->items(),
            'meta' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
            ],
        ]);
    }

    public function medicineByProductId(Request $request, $id)
    {
        // echo $id;die;
        if (empty($id)) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'ID parameter is required.',
                ],
                400,
            );
        }
        $productId = $id;
        $medicine = Medicine::where('product_id', $productId)->first();
        $otc = Otcmedicine::where('otc_id', $productId)->first();

        // Step 4: If neither found
        if (!$medicine && !$otc) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Product not found in either table.',
                ],
                404,
            );
        }

        $baseUrl = url('medicines/');
        $defaultImage = "{$baseUrl}/placeholder.png";
        if ($medicine) {
            $medicine->image_url = $medicine->image_url
                ? collect(explode(',', $medicine->image_url))
                    ->map(fn($img) => $baseUrl . '/' . trim(basename($img)))
                    ->toArray()
                : [$defaultImage];
        }

        if ($otc) {
            $otc->image_url = $otc->image_url
                ? collect(explode(',', $otc->image_url))
                    ->map(fn($img) => $baseUrl . '/' . trim(basename($img)))
                    ->toArray()
                : [$defaultImage];
        }
        return response()->json(
            [
                'success' => true,
                'data' => $medicine ?? $otc,
                'source' => $medicine ? 'medicines' : 'otcmedicines',
            ],
            200,
        );
    }

    public function medicineBySaltComposition(Request $request)
    {
        $productId = $request->input('product_id');

        $product = Medicine::where('product_id', $productId)->first();

        if (!$product) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Product not found.',
                ],
                404,
            );
        }

        $salt = $product->salt_composition;

        $relatedProducts = Medicine::where('salt_composition', $salt)
            ->where('product_id', '!=', $productId)
            ->get(['product_id', 'product_name', 'packaging_detail', 'prescription_required']);

        $formattedProducts = $relatedProducts->map(function ($item) {
            return [
                'product_id' => $item->product_id,
                'product_name' => $item->product_name,
                'packaging_detail' => $item->packaging_detail,
                'prescription_required' => $item->prescription_required === 'Prescription Required' ? true : false,
            ];
        });

        return response()->json([
            'status' => true,
            'salt_composition' => $salt,
            'substitute_products' => $formattedProducts,
        ]);
    }
}
