<?php

namespace App\Http\Controllers;

use App\Imports\LabTestImport;
use App\Models\LabTest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class LabtestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = LabTest::query();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
                        <div class="dropdown">
                          <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="dropdown">Action</button>
                          <ul class="dropdown-menu">
                           <li>
                        <a href="' .
                        route('labtest.show', $row->id) .
                        '" class="dropdown-item">View</a>
                        </li>
                              
                          </ul>
                        </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        // $tests = LabTest::all();
        return view('laboratorie.labtest.index');
    }

    public function import(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'file' => 'required|max:2048',
        ]);

        Excel::import(new LabTestImport(), $request->file('file'));

        return back()->with('success', 'LabTest imported successfully.');
    }

    public function show(string $id)
    {
        $tests = LabTest::find($id);
        return view('laboratorie.labtest.show', compact('tests'));
    }

    //get labtest  Api
    public function labTestDetails()
    {
        try {
            $labtest = LabTest::all();

            return response()->json(
                [
                    'success' => true,
                    'data' => $labtest,
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Something went wrong while fetching lab test details.',
                    'error' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function destroy(string $id)
    {
        //
    }
}
