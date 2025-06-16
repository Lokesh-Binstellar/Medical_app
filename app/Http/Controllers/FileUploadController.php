<?php

namespace App\Http\Controllers;

use App\Events\SendMessageEvent;
use App\Models\Customers;
use App\Events\AdminEvent;
use App\Events\MyEvent;
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

                    if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        return '<a href="' .
                            asset('uploads/' . $fileUrl) .
                            '" target="_blank">
                                        <img src="' .
                            asset('uploads/' . $fileUrl) .
                            '" alt="Prescription Image" style="max-width: 60px; height: auto;" class="img-thumbnail">
                                    </a>';
                    } elseif (strtolower($extension) === 'pdf') {
                        return '<a href="' .
                            asset('uploads/' . $fileUrl) .
                            '" target="_blank">
                                        <img src="' .
                            asset('assets/pdf-icon.png') .
                            '" style="width: 40px;" alt="PDF Preview">
                                    </a>';
                    }
                    // For other file types
                    else {
                        return '<a href="' . asset('uploads/' . $fileUrl) . '" target="_blank">View File</a>';
                    }
                })
                ->editColumn('prescription_status', function ($row) {
                    $selectedValue = $row->prescription_status;

                    $disabled = $selectedValue === 1 ? 'disabled' : '';

                    return '<select class="form-control custom-dropdown rounded"
                                    onchange="updateStatus(this, ' .
                        $row->id .
                        ')"
                                    onfocus="this.setAttribute(\'data-prev\', this.value)"
                                    ' .
                        $disabled .
                        '>
                                <option value="" disabled ' .
                        (is_null($selectedValue) ? 'selected' : '') .
                        '>Please select</option>
                                <option value="0" ' .
                        ($selectedValue === 0 ? 'selected' : '') .
                        '>Yes</option>
                                <option value="1" ' .
                        ($selectedValue === 1 ? 'selected' : '') .
                        '>No</option>
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
        // Set timezone if needed (optional)
        date_default_timezone_set('Asia/Kolkata'); // Replace with your timezone

        // Get current time (24-hour format) as integer hour (0-23)
        $currentHour = (int) date('H');

        // Allowed time: from 9 AM (9) to 9 PM (21)
        if ($currentHour < 9  || $currentHour > 21) {
            return response()->json([
                'status' => false,
                'message' => 'You can only upload prescriptions between 9 AM and 9 PM.',
            ], 403); 
        }

        $userId = $request->get('user_id');

        if ($request->hasFile('file')) {
            $originalFileName = $request->file('file')->getClientOriginalName();
            $path = $request->file('file')->move(public_path('uploads'), $originalFileName);

            $prescription_status = $request->get('prescription_status', null);

            $prescription = Prescription::create([
                'customer_id' => $userId,
                'prescription_file' => $originalFileName,
                'prescription_status' => $prescription_status,
            ]);

            event(new MyEvent('admin', null, 'New Prescription Received'));

            return response()->json([
                'status' => true,
                'message' => 'File uploaded successfully',
                'file_name' => $originalFileName,
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

            // ✅ Prepare message
            $message = [
                'status' => true,
                'prescription_id' => $prescription->id,
                'prescription_status' => 'No',
                'reason' => $prescription->reason,
                'message' => 'Prescription marked as No by user',
            ];

            // ✅ Use actual user_id who created the prescription
            $receiverId = $prescription->user_id;

            if ($receiverId) {
                event(new SendMessageEvent($message, $receiverId));
            }
        } else {
            $prescription->status = 1;
            $prescription->reason = null;
        }

        $prescription->save();

        return response()->json([
            'status' => true,
            'message' => 'Status updated successfully.',
        ]);
    }

    public function uploadprescription()
    {
        $selectedCustomers = Customers::all();
        return view('prescriptions.upload',compact('selectedCustomers'));
    }

//     public function search(Request $request)
// {
//     $customers = Customers::select('id', 'firstName', 'lastName', 'mobile_no')->get();

//     $results = $customers->map(function ($customer) {
//         return [
//             'id' => $customer->id,
//             'text' => "{$customer->firstName} {$customer->lastName} - {$customer->mobile_no}",
//         ];
//     });

//     return response()->json(['results' => $results]);
// }




    
    
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'prescription' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $file = $request->file('prescription');
        $filename = time() . '_' . $file->getClientOriginalName();
        $destinationPath = public_path('uploads'); // => {{root}}/public/uploads/

        // Ensure the uploads folder exists
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $file->move($destinationPath, $filename);

        // Save relative path in DB
        $prescription = Prescription::create([
            'customer_id' => $request->customer_id,
            'prescription_file' => $filename,
        ]);

        return redirect()->back()->with('success', 'Prescription uploaded successfully!');
    }
}
