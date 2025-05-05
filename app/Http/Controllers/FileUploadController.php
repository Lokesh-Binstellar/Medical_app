<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        // Save the file in storage/app/public/uploads
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('uploads', 'public');

            return response()->json([
                'status' => true,
                'message' => 'File uploaded successfully',
                'path' => asset('storage/' . $path)
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'File not uploaded'
        ]);
    }
}
