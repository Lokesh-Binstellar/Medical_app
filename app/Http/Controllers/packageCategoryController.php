<?php

namespace App\Http\Controllers;

use App\Models\PackageCategory;
use Illuminate\Http\Request;

class packageCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      
        $packageCategory = PackageCategory::all();
        return view('laboratorie.package_categories.index', compact( 'packageCategory'));
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
            'package_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $package = new packageCategory();
        $package->name = $request->name;

        if ($request->hasFile('package_image')) {
            $file = $request->file('package_image');
            $originalName = $file->getClientOriginalName(); // e.g. logo.png
            $destinationPath = storage_path('app/public/packageCategory');

            // Check if file with same name exists
            $filename = time() . '_' . $originalName;
            if (file_exists($destinationPath . '/' . $originalName)) {
                $file->move($destinationPath, $filename); // Unique name
            } else {
                $file->move($destinationPath, $originalName); // Same name
                $filename = $originalName;
            }

            // Save full URL and original name
            $package->package_image = url('storage/packageCategory/' . $filename); // Base URL + file path
            $package->package_image = $originalName; // Just original file name
        }

        $package->save();

        return redirect()->route('popular.index')->with('success', 'packageCategory added successfully.');
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
