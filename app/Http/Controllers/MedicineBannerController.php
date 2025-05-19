<?php

namespace App\Http\Controllers;

use App\Models\MedicineBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class MedicineBannerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables()->of(MedicineBanner::latest()->get())
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    return '<img src="' . asset('storage/banners/' . $row->image) . '" width="100">';
                })
                ->addColumn('action', function ($row) {
    return '
        <div class="dropdown">
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="dropdown">Action</button>
            <ul class="dropdown-menu">
                <li>
                    <a href="' . route('medicinebanner.show', $row->id) . '" class="dropdown-item">View</a>
                </li>
                <li>
                    <a href="' . route('medicinebanner.edit', $row->id) . '" class="dropdown-item">Edit</a>
                </li>
                <li>
                    <button class="dropdown-item text-danger" onclick="deleteBanner(' . $row->id . ')">Delete</button>
                </li>
            </ul>
        </div>';
})

                ->rawColumns(['image', 'action'])
                ->make(true);
        }

        return view('medicinebanner.index');
    }

    public function store(Request $request)
    {

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'priority' => 'nullable|integer|min:0'
        ]);


        $fileName = time() . '.' . $request->image->extension();


        Storage::disk('uploads')->put($fileName, file_get_contents($request->file('image')));


        MedicineBanner::create(['image' => $fileName, 'priority' => $request->priority ?? 0]);


        return redirect()->back()->with('success', 'Banner Added!');
    }

    public function show($id)
    {
        $banner = MedicineBanner::findOrFail($id);
        return view('medicinebanner.show', compact('banner'));
    }

    public function edit($id)
    {
        $banner = MedicineBanner::findOrFail($id);
        return view('medicinebanner.edit', compact('banner'));
    }


    public function update(Request $request, $id)
    {
        $banner = MedicineBanner::findOrFail($id);

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg|max:2048'
            ]);

            // Delete old image if exists
            if ($banner->image && Storage::disk('uploads')->exists($banner->image)) {
                Storage::disk('uploads')->delete($banner->image);
            }

            // Generate new file name
            $fileName = time() . '.' . $request->image->extension();

            // Save new image using 'uploads' disk
            Storage::disk('uploads')->put($fileName, file_get_contents($request->file('image')));

            // Update DB
            $banner->image = $fileName;
        }

        $banner->priority = $request->priority ?? 0;
        $banner->save();

        return redirect()->route('medicinebanner.index')->with('success', 'Banner updated successfully!');
    }

    public function destroy($id)
{
    $banner = MedicineBanner::findOrFail($id);

    if ($banner->image && Storage::exists('public/banners/' . $banner->image)) {
        Storage::delete('public/banners/' . $banner->image);
    }

    $banner->delete();

    if (request()->ajax()) {
        return response()->json(['success' => 'Banner deleted successfully']);
    }

    return redirect()->route('medicinebanner.index')
        ->with('success', 'Medicine banner deleted successfully');
}


    public function getAllBanners()
    {
        $banners = MedicineBanner::orderBy('priority', 'asc')->get(['id', 'image', 'priority']);

        $result = $banners->map(function ($banner) {
            return [
                'id' => $banner->id,
                'image_url' => asset('storage/banners/' . $banner->image),
                // 'priority' => $banner->priority
            ];
        });

        return response()->json([
            'status' => true,
            // 'message' => 'Banner list fetched successfully',
            'data' => $result
        ]);
    }
}
