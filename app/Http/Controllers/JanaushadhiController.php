<?php

namespace App\Http\Controllers;

use App\Imports\janushadhiImport;
use App\Models\Janaushadhi;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class JanaushadhiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $data = Janaushadhi::orderBy('id', 'desc');
            $allData = $data->get();

            return datatables()->of($allData)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = "";
                    $readCheck = Permission::checkCRUDPermissionToUser("Role", "read");
                    $updateCheck = Permission::checkCRUDPermissionToUser("Role", "update");
                    $deleteCheck = Permission::checkCRUDPermissionToUser("Role", "delete");
                    $isSuperAdmin = Permission::isSuperAdmin();
                    if ($updateCheck) {
                        $html .= '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="janaushadhi/' . $row->id . '/edit">Edit</a></li>';
                    }
                    if ($readCheck) {
                        $html .= '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="janaushadhi/' . $row->id . '">View</a></li>';
                    }
                    if ($isSuperAdmin || $deleteCheck) {
                        $html .= '<li><a class="dropdown-item dropdown-trigger-17500btn waves-effect" href="javascript:void(0)" onclick="deleteJanaushadhi(' . $row->id . ', \'' . $row->name . '\')">Delete</a></li>';
                    }
                    if (!$isSuperAdmin && !$updateCheck && !$readCheck && !$deleteCheck) {
                        return '';
                    }
                    return
                        '<div class="dropdown">
                            <button type="button" class="btn btn-primary px-1 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                Action
                            </button>
                            <div class="dropdown-menu">
                                ' . $html . '
                            </div>
                        </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('janaushadhi.index');
    }

    public function import(request $request)
    {
        $request->validate([
            'file' => 'required|max:2048',
        ]);

        if ($request->fails()) {
            return redirect()->back()->withErrors($request)->withInput();
        }

        try {
            Excel::import(new janushadhiImport, $request->file('file'));
            return back()->with(['status' => 'success', 'message' => 'Imported successfully!']);
        } catch (\Exception $e) {
            Log::error('Import Error: ' . $e->getMessage());
            return back()->with(['status' => 'danger', 'message' => 'Import Failed!']);
        }
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($janaushadhi)
    {
        $janaushadhies = Janaushadhi::find($janaushadhi);
        // dd($janaushadhies);
        return view('janaushadhi.show', compact('janaushadhies'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($janaushadhi)
    {
        $janaushadhies = Janaushadhi::find($janaushadhi);
        return view('janaushadhi.update', compact('janaushadhies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $janaushadhi)
    {
        $janaushadhies = Janaushadhi::find($janaushadhi);
        $params = $request->all();

        $validation = Validator::make($params, [
            'drug_code' => ['required'],
            'generic_name' => ['required'],
            'unit_size' => ['required',],
            'mrp' => ['required'],
            'group_name' => ['required']
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        $janaushadhies->update($params);

        return redirect()->route('janaushadhi.index')->with('success', 'Janaushadhi updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($janaushadhi)
    {
        $janaushadhies = Janaushadhi::find($janaushadhi);

        if (!$janaushadhies) {
            return response()->json([
                'status' => false,
                'message' => 'Janaushadhi not found.'
            ], 404);
        }

        try {
            $janaushadhies->delete();

            return response()->json([
                'status' => true,
                'message' => 'Janaushadhi deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete janaushadhi. ' . $e->getMessage()
            ], 500);
        }
    }
}
