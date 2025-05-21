<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\public;
use Yajra\DataTables\Facades\DataTables;

class FileUploadController extends Controller
{
    public function index(Request $request)
    {


        if ($request->ajax()) {
            $data = Prescription::with('customers');


            //dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('customer_id', function ($row) {
                    return $row->customers ? $row->customers->firstName : '-';
                })
                ->addColumn('customer_phone', function ($row) {
                    return $row->customers ? $row->customers->mobile_no : '-';
                })
                ->editColumn('prescription_file', function ($row) {
                    $fileUrl = $row->prescription_file;
                    $extension = pathinfo($fileUrl, PATHINFO_EXTENSION);

                    // Check if the file is an image
                    if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        return '<a href="' .asset('uploads/' . $fileUrl). '" target="_blank">
                                        <img src="' . asset('uploads/' . $fileUrl). '" alt="Prescription Image" style="max-width: 60px; height: auto;" class="img-thumbnail">
                                    </a>';
                    }
                    // Check if the file is a PDF
                    elseif (strtolower($extension) === 'pdf') {
                        return '<a href="' . asset('uploads/' . $fileUrl) . '" target="_blank">
                                        <img src="' . asset('assets/pdf-icon.png') . '" style="width: 40px;" alt="PDF Preview">
                                    </a>';
                    }
                    // For other file types
                    else {
                        return '<a href="' . asset('uploads/' . $fileUrl). '" target="_blank">View File</a>';
                    }
                })
                ->editColumn('prescription_status', function ($row) {
                    $selectedValue = $row->prescription_status;

                    $disabled = $selectedValue === 1 ? 'disabled' : '';

                    return '<select class="form-control custom-dropdown rounded"
                                    onchange="updateStatus(this, ' . $row->id . ')"
                                    onfocus="this.setAttribute(\'data-prev\', this.value)"
                                    ' . $disabled . '>
                                <option value="" disabled ' . (is_null($selectedValue) ? 'selected' : '') . '>Please select</option>
                                <option value="0" ' . ($selectedValue === 0 ? 'selected' : '') . '>Yes</option>
                                <option value="1" ' . ($selectedValue === 1 ? 'selected' : '') . '>No</option>
                            </select>';
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<span class="badge bg-warning">Pending</span>';
                    } elseif ($row->status == 0) {
                        return '<span class="badge bg-success">Completed</span>';
                    } else {
                        return '<span class="badge bg-danger">Rejected</span>';
                    }
                })
                ->rawColumns(['prescription_file', 'prescription_status', 'status'])
                ->make(true);
        }
        return view('prescriptions.index');
    }
    public function upload(Request $request)
    {
        $userId = $request->get('user_id');

        if ($request->hasFile('file')) {

            // Get the original file name
            $originalFileName = $request->file('file')->getClientOriginalName();
            $path = $request->file('file')->move(public_path('uploads'), $originalFileName);

            // Get the prescription status from the request
            $prescription_status = $request->get('prescription_status', null);

            // Create a new prescription record, storing only the file name
            $prescription = Prescription::create([
                'customer_id' => $userId,
                'prescription_file' => $originalFileName,  // Store only file name
                'prescription_status' => $prescription_status,
            ]);

            // Return response with file name
            return response()->json([
                'status' => true,
                'message' => 'File uploaded successfully',
                'file_name' => $originalFileName, // Return the file name
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'No file found in request',
            ], 400);
        }
    }




    public function updateStatus(Request $request, $id)
    {

        $request->validate([
            'prescription_status' => 'required|in:0,1',
            'reason' => 'required_if:prescription_status,1|string|nullable',
        ]);
        $prescription = Prescription::findOrFail($id);
        $prescription->prescription_status = $request->prescription_status;
        if ($request->prescription_status == 1) {
            $prescription->status = -1;
            $prescription->reason = $request->reason ?? null;
        } else {
            $prescription->status = 1;
            $prescription->reason = null;
        }
        $prescription->save();

        return response()->json([
            'status' => true,
            'message' => 'Status updated successfully.'
        ]);
    }
}
