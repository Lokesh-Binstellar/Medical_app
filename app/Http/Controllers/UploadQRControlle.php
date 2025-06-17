<?php

namespace App\Http\Controllers;

use App\Models\UploadQR;
use Illuminate\Http\Request;

use File;
use Yajra\DataTables\Facades\DataTables;

class UploadQRControlle extends Controller
{

    public function index(Request $request)
    {
        if (auth()->user()->role->name === 'delivery_person' || 'phlebotomists') {
            // dd(auth()->user()->role );
            return redirect()->route('upload_qr.show', 1);
        }
        
        if ($request->ajax()) {
            return datatables()->of(UploadQR::latest()->get())
                ->addIndexColumn()
                ->addColumn('qr_image', function ($row) {
                    return '<img src="' . asset('uploadQR/' . $row->qr_image) . '" width="100">';
                })
                ->addColumn('action', function ($row) {
                    return '
        <div class="dropdown">
          <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="dropdown">Action</button>
          <ul class="dropdown-menu">
            <li><a href="' . route('upload_qr.show', $row->id) . '" class="dropdown-item">View</a></li>
            <li><a href="' . route('upload_qr.edit', $row->id) . '" class="dropdown-item">Edit</a></li>
            <li>
              <button onclick="deleteuploadQR(' . $row->id . ')" class="dropdown-item text-danger">Delete</button>
            </li>
          </ul>
        </div>';
                })

                ->rawColumns(['qr_image', 'action'])
                ->make(true);
        }

        return view('upload_qr.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'qr_image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $uploadPath = public_path('uploadQR');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $fileName = $request->qr_image->getClientOriginalName();

        $request->file('qr_image')->move($uploadPath, $fileName);

        UploadQR::create([
            'qr_image' => $fileName,
        ]);

        return redirect()->back()->with('success', 'uploadQR Added!');
    }


    public function show($id)
    {
        $uploadQR = UploadQR::findOrFail($id);
        return view('upload_qr.show', compact('uploadQR'));
    }

    public function edit($id)
    {
        $uploadQR = UploadQR::findOrFail($id);
        return view('upload_qr.edit', compact('uploadQR'));
    }


    public function update(Request $request, $id)
    {
        $uploadQR = UploadQR::findOrFail($id);

        if ($request->hasFile('qr_image')) {
            $request->validate([
                'qr_image' => 'image|mimes:jpeg,png,jpg|max:2048'
            ]);

            $uploadPath = public_path('uploadQR');

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            if ($uploadQR->qr_image && file_exists($uploadPath . '/' . $uploadQR->qr_image)) {
                unlink($uploadPath . '/' . $uploadQR->qr_image);
            }

            $fileName = $request->qr_image->getClientOriginalName();
            $request->file('qr_image')->move($uploadPath, $fileName);

            $uploadQR->qr_image = $fileName;
        }


        $uploadQR->save();

        return redirect()->route('upload_qr.index')->with('success', 'uploadQR updated successfully!');
    }


    public function destroy($id)
    {

        $uploadQR = UploadQR::findOrFail($id);

        $qr_imagePath = public_path('uploadQR/' . $uploadQR->qr_image);

        if ($uploadQR->qr_image && file_exists($qr_imagePath)) {
            unlink($qr_imagePath);
        }

        $uploadQR->delete();
        if (request()->ajax()) {
            return response()->json(['success' => 'uploadQR deleted successfully']);
        }

        return redirect()->route('upload_qr.index')
            ->with('success', 'Home uploadQR deleted successfully');
    }


    public function getAlluploadQR()
    {
        $uploadQR = UploadQR::get(['id', 'qr_image']);

        $result = $uploadQR->map(function ($uploadQR) {
            return [
                'id' => $uploadQR->id,
                'qr_image_url' => asset('uploadQR/' . $uploadQR->qr_image),
            ];
        });

        return response()->json([
            'status' => true,
            // 'message' => 'uploadQR list fetched successfully',
            'data' => $result
        ]);
    }
}
