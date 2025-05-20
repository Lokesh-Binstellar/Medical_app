<?php

namespace App\Http\Controllers;

use App\Models\HomeBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class HomeBannerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables()->of(HomeBanner::latest()->get())
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    return '<img src="' . asset('banners/' . $row->image) . '" width="100">';
                })
                ->addColumn('action', function ($row) {
    return '
        <div class="dropdown">
          <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="dropdown">Action</button>
          <ul class="dropdown-menu">
            <li><a href="' . route('homebanner.show', $row->id) . '" class="dropdown-item">View</a></li>
            <li><a href="' . route('homebanner.edit', $row->id) . '" class="dropdown-item">Edit</a></li>
            <li>
              <button onclick="deleteBanner(' . $row->id . ')" class="dropdown-item text-danger">Delete</button>
            </li>
          </ul>
        </div>';
})

                ->rawColumns(['image', 'action'])
                ->make(true);
        }

        return view('homebanner.index');
    }

   public function store(Request $request)
{
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        'priority' => 'nullable|integer|min:0'
    ]);

   $uploadPath = public_path('banners');
    if (!file_exists($uploadPath)) {
        mkdir($uploadPath, 0755, true);
    }

    $fileName = $request->image->getClientOriginalName();

    $request->file('image')->move($uploadPath, $fileName);

    HomeBanner::create([
        'image' => $fileName,
        'priority' => $request->priority ?? 0
    ]);

    return redirect()->back()->with('success', 'Banner Added!');
}


    public function show($id)
    {
        $banner = HomeBanner::findOrFail($id);
        return view('homebanner.show', compact('banner'));
    }

    public function edit($id)
    {
        $banner = HomeBanner::findOrFail($id);
        return view('homebanner.edit', compact('banner'));
    }


    public function update(Request $request, $id)
{
    $banner = HomeBanner::findOrFail($id);

    if ($request->hasFile('image')) {
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $uploadPath = public_path('banners');

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        if ($banner->image && file_exists($uploadPath . '/' . $banner->image)) {
            unlink($uploadPath . '/' . $banner->image);
        }

        $fileName = $request->image->getClientOriginalName();
        $request->file('image')->move($uploadPath, $fileName);

        $banner->image = $fileName;
    }

    $banner->priority = $request->priority ?? 0;
    $banner->save();

    return redirect()->route('homebanner.index')->with('success', 'Banner updated successfully!');
}


public function destroy($id)
{
    
  $banner = HomeBanner::findOrFail($id);

   $imagePath = public_path('banners/' . $banner->image);

if ($banner->image && file_exists($imagePath)) {
    unlink($imagePath);
}

$banner->delete();
 if (request()->ajax()) {
        return response()->json(['success' => 'Banner deleted successfully']);
    }

 return redirect()->route('homebanner.index')
        ->with('success', 'Home banner deleted successfully');
}


public function getAllBanners()
 {
        $banners = HomeBanner::orderBy('priority', 'asc')->get(['id', 'image', 'priority']);

        $result = $banners->map(function ($banner) {
            return [
                'id' => $banner->id,
                'image_url' => asset('banners/' . $banner->image),
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
