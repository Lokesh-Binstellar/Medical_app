<?php
namespace App\Http\Controllers;

use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
                        return '<a href="' . $fileUrl . '" target="_blank">
                                        <img src="' . $fileUrl . '" alt="Prescription Image" style="max-width: 60px; height: auto;" class="img-thumbnail">
                                    </a>';
                    }
                    // Check if the file is a PDF
                    elseif (strtolower($extension) === 'pdf') {
                        return '<a href="' . $fileUrl . '" target="_blank">
                                        <img src="' . asset('assets/pdf-icon.png') . '" style="width: 40px;" alt="PDF Preview">
                                    </a>';
                    }
                    // For other file types
                    else {
                        return '<a href="' . $fileUrl . '" target="_blank">View File</a>';
                    }
                })
                ->editColumn('prescription_status', function ($row) {
                    return '<select class="form-control custom-dropdown rounded" onchange="updateStatus(this, ' . $row->id . ')">
                                    <option value="0" ' . ($row->prescription_status == 0 ? 'selected' : '') . '>Yes</option>
                                    <option value="1" ' . ($row->prescription_status == 1 ? 'selected' : '') . '>No</option>
                                </select>';
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<span class="badge bg-warning">Pending</span>';
                    } else {
                        return '<span class="badge bg-success">Completed</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    return '
                        <div class="dropdown">
                          <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="dropdown">Action</button>
          <ul class="dropdown-menu">
                            <li>
                             <a href="' . route('laboratorie.show', $row->id) . '"class="dropdown-item" >View
            </a>
                            </li>
        
                            <li>
                            <a href="' . route('laboratorie.edit', $row->id) . '" class="dropdown-item" >Edit</a>
                            </li>
                            
                            <li>
                              <form action="' . route('laboratorie.destroy', $row->id) . '" method="POST" onsubmit="return confirm(\'Are you sure?\')">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button class="dropdown-item " type="submit">Delete</button>
                              </form>
                            </li>
                          </ul>
                        </div>';
                })
                ->rawColumns(['action', 'prescription_file', 'prescription_status', 'status'])
                ->make(true);
        }
        return view('prescriptions.index');
    }
    public function upload(Request $request)
    {
        $userId = $request->get('user_id');
        // echo $userId;die;

        //print_r( $request->file);die;




        // Save the file in storage/app/public/uploads
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('uploads', 'public');

            $prescription = Prescription::create([

                'customer_id' => $userId,
                'prescription_file' => asset('storage/' . $path),
                'prescription_status' => 1,
            ]);


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

    public function updateStatus(Request $request, $id)
    {
        $prescription = Prescription::findOrFail($id);
        $prescription->prescription_status = $request->prescription_status;
        $prescription->save();

        return response()->json(['status' => true, 'message' => 'Status updated']);
    }


}
