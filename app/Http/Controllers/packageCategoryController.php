<?php

namespace App\Http\Controllers;

use App\Models\PackageCategory;
use Illuminate\Http\Request;
use DataTables;

class packageCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PackageCategory::query();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '
                    <div class="form-button-action d-flex gap-2">
                          <a href="' . route('packageCategory.edit', $row->id) . '" class="btn  btn-warning btn-sm" data-bs-toggle="tooltip" title="Edit"> Edit </a>
                            <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteCategory">Delete</a>
                          </div>';
                    return $btn;
                })
                ->addColumn('package_image', function ($product_brand) {
                    return '<img src="' . asset('assets/package_image/' . $product_brand->package_image) . '" border="0" width="40" class="img-rounded" align="center" />';
                })
                ->rawColumns(['action', 'package_image'])
                ->make(true);
        }

        return view('laboratorie.package_categories.index');

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
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'package_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Step 2: Handle file if present
        if ($request->hasFile('package_image')) {
            $image = $request->file('package_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('assets/package_image');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $fullImagePath = $destinationPath . '/' . $imageName;
            if (!file_exists($fullImagePath)) {
                $image->move($destinationPath, $imageName);
                $validatedData['package_image'] = $imageName;
            } else {

                $validatedData['package_image'] = $imageName;
            }
        }
        PackageCategory::create($validatedData);
        return redirect()->route('packageCategory.index')->with('success', 'Package Category added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $packageCategory = PackageCategory::findOrFail($id);
        return view('laboratorie.package_categories.show', compact('packageCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $packageCategory = PackageCategory::findOrFail($id);
        return view('laboratorie.package_categories.edit', compact('packageCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $package = PackageCategory::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'package_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('package_image')) {
            $image = $request->file('package_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('assets/package_image');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $fullImagePath = $destinationPath . '/' . $imageName;
            if (!file_exists($fullImagePath)) {
                $image->move($destinationPath, $imageName);
            }

            $validatedData['package_image'] = $imageName;
        }

        $package->update($validatedData);

        return redirect()->route('packageCategory.index')->with('success', 'Package Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        PackageCategory::find($id)->delete();

        return response()->json(['success' => 'PackageCategory deleted successfully.']);
    }
}
