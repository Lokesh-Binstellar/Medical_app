<?php

namespace App\Http\Controllers;

use App\Models\PopularBrand;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PopularBrandController extends Controller
{
    public function index()
    {
        $popularBrands = Medicine::all()->pluck('marketer')->unique();
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
            $path = $request->file('logo')->store('brands', 'public');
            $brand->logo = $path;
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


    public function destroy($id)
    {
        $brand = PopularBrand::findOrFail($id);

        // Delete the logo from storage if it exists
        if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
            Storage::disk('public')->delete($brand->logo);
        }

        // Delete the brand from the database
        $brand->delete();

        return redirect()->route('popular.index')->with('success', 'Brand deleted successfully.');
    }
}
