<?php

namespace App\Http\Controllers;

use App\Models\Otcmedicine;
use App\Models\PopularCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PopularCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $popularCategory = Otcmedicine::all()->pluck('category')->unique();
        $AddedCategory = PopularCategory::all();
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
            $originalName = $file->getClientOriginalName(); // e.g. logo.png
            $destinationPath = storage_path('app/public/category');

            // Check if file with same name exists
            $filename = time() . '_' . $originalName;
            if (file_exists($destinationPath . '/' . $originalName)) {
                $file->move($destinationPath, $filename); // Unique name
            } else {
                $file->move($destinationPath, $originalName); // Same name
                $filename = $originalName;
            }

            // Save full URL and original name
            $category->logo = url('storage/brand/' . $filename); // Base URL + file path
            $category->logo = $originalName; // Just original file name
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
            // Delete old logo if exists
            if ($category->logo && Storage::disk('public')->exists($category->logo)) {
                Storage::disk('public')->delete($category->logo);
            }

            $path = $request->file('logo')->store('brands', 'public');

            $category->logo = $path;
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
        $category = Otcmedicine::where('marketer', 'LIKE', "%$query%")
            ->select('marketer')
            ->distinct()
            ->limit(10)
            ->get();

        return response()->json($category);
    }
    public function getCategory()
    {
        $categories= PopularCategory::all();

        $categoryData = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'logo' => $category->logo ? url('storage/brand/' . basename($category->logo)) : [],

            ];
        });

        return response()->json([
            'success' => true,
            'data' => $categoryData
        ], 200);
    }
    public function destroy(string $id)
    {
        $category = PopularCategory::findOrFail($id);

        // Delete the logo from storage if it exists
        if ($category->logo && Storage::disk('public')->exists($category->logo)) {
            Storage::disk('public')->delete($category->logo);
        }

        // Delete the brand from the database
        $category->delete();

        return redirect()->route('popular_category.index')->with('success', 'category deleted successfully.');
    }
}
