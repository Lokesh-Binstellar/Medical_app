<?php

namespace App\Http\Controllers;

use App\Models\Otcmedicine;
use App\Models\PopularBrand;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PopularBrandController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PopularBrand::all();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('logo', function ($brand) {
                    return '<img src="' . asset('storage/brands/' . $brand->logo) . '" border="0" width="40" class="img-rounded" align="center" />';
                })
                ->addColumn('action', function ($row) {
                    return '
                    <div class="dropdown">
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="dropdown">Action</button>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="' . route('popular.edit', $row->id) . '" class="dropdown-item">Edit</a>
                            </li>
                            <li>
                                <button type="button" onclick="deleteUser(' . $row->id . ')" class="dropdown-item text-danger">Delete</button>
                            </li>
                        </ul>
                    </div>';
                })
                ->rawColumns(['action', 'logo'])
                ->make(true);
        }

        $medicineMarketers = Medicine::select('marketer')
            ->whereNotNull('marketer')
            ->distinct()
            ->pluck('marketer');

        $otcManufacturers = Otcmedicine::select('manufacturers')
            ->whereNotNull('manufacturers')
            ->distinct()
            ->pluck('manufacturers');

        $popularBrands = $medicineMarketers->merge($otcManufacturers)->unique()->values();
        $AddedBrands = PopularBrand::all();

        return view('popular.index', compact('popularBrands', 'AddedBrands'));
    }




    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $brand = new PopularBrand();
        $brand->name = $request->name;

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $originalName = $file->getClientOriginalName();
            $destinationPath = public_path('popular/brands'); // Store directly in /public/brands

            // Make sure directory exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $saveName = $originalName;

            // If file already exists, optionally overwrite or skip (your choice)
            $file->move($destinationPath, $saveName);

            // ✅ Save only the original filename
            $brand->logo = $saveName;
        }


        $brand->save();

        return redirect()->route('popular.index')->with('success', 'Brand added successfully.');
    }

    public function edit($id)
    {
        // Fetch the brand data for editing
        $brand = PopularBrand::findOrFail($id);
        $popularBrands = Medicine::all()->pluck('marketer')->unique();
        return view('popular.edit', compact('brand', 'popularBrands'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $brand = PopularBrand::findOrFail($id);
        $brand->name = $request->name;

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
                Storage::disk('public')->delete($brand->logo);
            }

            $path = $request->file('logo')->store('brands', 'public');

            $brand->logo = $path;
        }

        $brand->save();

        return redirect()->route('popular.index')->with('success', 'Brand updated successfully.');
    }

    public function brandSearch(Request $request)
    {
        $query = $request->get('q', '');
        $brands = Medicine::where('marketer', 'LIKE', "%$query%")
            ->select('marketer')
            ->distinct()
            ->limit(10)
            ->get();

        return response()->json($brands);
    }

    public function getBrand()
    {
        $brands = PopularBrand::all();

        $brandsData = $brands->map(function ($brand) {
            return [
                'id' => $brand->id,
                'name' => $brand->name,
                'logo' => $brand->logo ? url('storage/brands/' . basename($brand->logo)) : [],

            ];
        });

        return response()->json([
            'status' => true,
            'data' => $brandsData
        ], 200);
    }

    public function productListByBrand(Request $request, $brandName)
    {

        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 20);

        $packageFilter = $request->query('package');
        $productFormFilter = $request->query('product_form');

        $packageArray = $packageFilter ? array_map('trim', explode(',', $packageFilter)) : null;
        $productFormArray = $productFormFilter ? array_map('trim', explode(',', $productFormFilter)) : null;

        if (!$brandName) {
            return response()->json([
                'status' => false,
                'message' => 'Brand parameter is required.'
            ], 400);
        }

        $baseUrl = url('medicines');
        $defaultImage = "{$baseUrl}/placeholder.png";



        // --- Fetch and transform Medicines ---
        $medicines = Medicine::where('marketer', $brandName)
            ->when($productFormArray, function ($q) use ($productFormArray) {
                return $q->whereIn('product_form', $productFormArray);
            })
            ->when($packageArray, function ($q) use ($packageArray) {
                return $q->whereIn('package', $packageArray);
            })
            ->select('product_id', 'product_name', 'salt_composition', 'package', 'image_url', 'marketer', 'product_form')
            ->get()
            ->map(function ($item) use ($baseUrl, $defaultImage) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product_name,
                    'salt_composition' => $item->salt_composition,
                    'packaging_detail' => $item->package,
                    'product_form' => $item->product_form,
                    'image_url' => $item->image_url
                        ? collect(explode(',', $item->image_url))->map(fn($img) => "{$baseUrl}/" . trim(basename($img)))
                        : [$defaultImage],
                    'type' => 'medicine',
                    'brand' => $item->marketer ?? '',
                ];
            });


        // --- Fetch and transform OTC Medicines ---
        $otc = Otcmedicine::where('manufacturers', $brandName)
            ->when($packageArray, fn($q) => $q->whereIn('package', $packageArray))
            ->when($productFormArray, fn($q) => $q->whereIn('product_form', $productFormArray))
            ->select('otc_id', 'name', 'package', 'image_url', 'manufacturers', 'product_form')
            ->get()
            ->map(function ($item) use ($baseUrl, $defaultImage) {
                return [
                    'product_id' => $item->otc_id,
                    'product_name' => $item->name,
                    'salt_composition' => null,
                    'packaging_detail' => $item->package,
                    'product_form' => $item->product_form,
                    'image_url' => $item->image_url
                        ? collect(explode(',', $item->image_url))->map(fn($img) => "{$baseUrl}/" . trim(basename($img)))
                        : [$defaultImage],
                    'type' => 'otc',
                    'brand' => $item->manufacturers ?? '',
                ];
            });


        // --- Merge the collections ---
        $merged = $medicines->concat($otc)->values(); // ensures fresh indexes

        $total = $merged->count();
        $paginated = $merged->slice(($page - 1) * $perPage, $perPage)->values();

        // --- Paginate manually ---
        $paginator = new LengthAwarePaginator(
            $paginated,
            $total,
            $perPage,
            $page,
            ['path' => url()->current(), 'query' => $request->query()]
        );

        // --- Return JSON response ---
        return response()->json([
            'success' => true,
            'data' => $paginator->items(),
            'meta' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
            ]
        ]);


    }

    public function destroy($id)
    {
        $brand = PopularBrand::findOrFail($id);

        // Delete logo from storage if exists
        if ($brand->logo && Storage::disk('public')->exists('brands/' . $brand->logo)) {
            Storage::disk('public')->delete('brands/' . $brand->logo);
        }

        $brand->delete();

        return response()->json(['status' => true, 'message' => 'Brand deleted successfully.']);
    }

    public function getFilters(Request $request)
    {
        $brandName = $request->name;

        // Filtered query based on brand name if provided
        $medicineQuery = Medicine::query();
        $otcQuery = Otcmedicine::query();

        if ($brandName) {
            $medicineQuery->where('marketer', 'LIKE', "%$brandName%");
            $otcQuery->where('manufacturers', 'LIKE', "%$brandName%");
        }

        // Unique product forms
        $medicineForms = (clone $medicineQuery)
            ->whereNotNull('product_form')
            ->where('product_form', '!=', '')
            ->distinct()
            ->pluck('product_form');

        $otcForms = (clone $otcQuery)
            ->whereNotNull('product_form')
            ->where('product_form', '!=', '')
            ->distinct()
            ->pluck('product_form');

        $productForms = $medicineForms->merge($otcForms)->unique()->values();

        // Unique package details
        $medicinePackages = (clone $medicineQuery)
            ->whereNotNull('package')
            ->where('package', '!=', '')
            ->distinct()
            ->pluck('package');

        $otcPackages = (clone $otcQuery)
            ->whereNotNull('package')
            ->where('package', '!=', '')
            ->distinct()
            ->pluck('package');

        $package = $medicinePackages->merge($otcPackages)->unique()->values();

        return response()->json([
            'status' => true,
            'filters' => [
                'product_forms' => $productForms,
                'package' => $package
            ]
        ]);
    }
}
