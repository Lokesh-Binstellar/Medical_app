<?php

namespace App\Http\Controllers;

use App\Models\Otcmedicine;
use App\Models\PopularCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PopularCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $popularCategory = Otcmedicine::all()->pluck('category')->unique();
        $AddedCategory = PopularCategory::all();
        if ($request->ajax()) {
            $data = PopularCategory::all();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('logo', function ($product_brand) {
                    return '<img src="' . asset('popular/category/' . $product_brand->logo) . '" border="0" width="40" class="img-rounded" align="center" />';
                })

                ->addColumn('action', function ($row) {
                    return '
    <div class="dropdown">
      <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="dropdown">Action</button>
      <ul class="dropdown-menu">
        <li>
          <a href="' .
                        route('popular_category.edit', $row->id) .
                        '" class="dropdown-item">Edit</a>
        </li>
        <li>
          <button class="dropdown-item text-danger" type="button" onclick="deletePOPcate(' .
                        $row->id .
                        ')">Delete</button>
        </li>
      </ul>
    </div>';
                })

                ->rawColumns(['action', 'logo'])
                ->make(true);
        }

        return view('popular_category.index', compact('popularCategory', 'AddedCategory'));
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
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $category = new PopularCategory();
        $category->name = $request->name;

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $originalName = $file->getClientOriginalName(); 
            $destinationPath = public_path('popular/category'); 

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $originalName);
            $category->logo = $originalName;
        }

        $category->save();

        return redirect()->route('popular_category.index')->with('success', 'category added successfully.');
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
        $category = PopularCategory::findOrFail($id);
        $popularCategory = Otcmedicine::all()->pluck('category')->unique();
        return view('popular_category.edit', compact('category', 'popularCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $category = PopularCategory::findOrFail($id);
        $category->name = $request->name;

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $originalName = $file->getClientOriginalName(); 
            $destinationPath = public_path('popular/category');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            if ($category->logo && file_exists(public_path('popular/category/' . $category->logo))) {
                unlink(public_path('popular/category/' . $category->logo));
            }

            $file->move($destinationPath, $originalName);
            $category->logo = $originalName;
        }

        $category->save();

        return redirect()->route('popular_category.index')->with('success', 'category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function categorySearch(Request $request)
    {
        $query = $request->get('q', '');
        $category = Otcmedicine::where('category', 'LIKE', "%$query%")
            ->select('marketer')
            ->distinct()
            ->limit(10)
            ->get();

        return response()->json($category);
    }
    public function getCategory()
    {
        $categories = PopularCategory::all();

        $categoryData = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'logo' => $category->logo ? url('popular/category/' . basename($category->logo)) : [],
            ];
        });

        return response()->json(
            [
                'status' => true,
                'data' => $categoryData,
            ],
            200,
        );
    }

    public function destroy(string $id)
    {
        try {
            $category = PopularCategory::findOrFail($id);

            // Delete the logo from storage if it exists
            if ($category->logo && Storage::disk('public')->exists($category->logo)) {
                Storage::disk('public')->delete($category->logo);
            }

            // Delete the category from the database
            $category->delete();

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Category deleted successfully.',
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Failed to delete category. Please try again.',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }
}
