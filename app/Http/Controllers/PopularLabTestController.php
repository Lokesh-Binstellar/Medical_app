<?php

// app/Http/Controllers/PopularLabTestController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LabTest;
use App\Models\PopularLabTest;
use Yajra\DataTables\Facades\DataTables;

class PopularLabTestController extends Controller
{
    // Show page with dropdown
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PopularLabTest::latest()->get();
            return DataTables :: of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
       
                    return '
                    <div class="dropdown">
                      <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="dropdown">Action</button>
                      <ul class="dropdown-menu">
                       
                        
                        <li>
                          <form action="' . route('popular_lab_test.destroy', $row->id) . '" method="POST" onsubmit="return confirm(\'Are you sure?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button class="dropdown-item" type="submit">Delete</button>
                          </form>
                        </li>
                      </ul>
                    </div>';
                })
                ->rawColumns(['action'])
                ->make(true);   
        }

        $labTests = LabTest::all(['id', 'name', 'contains']);
        return view('popular_lab_test.index', compact('labTests'));
    }

    public function store(Request $request)
    {
        $labTest = LabTest::findOrFail($request->name);

        $exists = PopularLabTest::where('name', $labTest->name)->exists();
    
        if ($exists) {
            return redirect()->back()->with('error', 'This lab test is already in the popular list.');
        }
    
        PopularLabTest::create([
            'name' => $labTest->name,
            'contains' => $labTest->contains,
        ]);
    
        return redirect()->back()->with('success', 'Popular Lab Test added!');
    }
    

    public function destroy(string $id)
    {
        $labtest = PopularLabTest::findOrFail($id);
        
        $labtest->delete();
    
        return redirect()->route('popular_lab_test.index')->with('success', 'Popular Lab Test deleted successfully.');
    }



    public function getAll()
    {
        try {
            $data = PopularLabTest::select('id', 'name', 'contains')->get();
            return response()->json([
                'status' => true,
                // 'message' => 'Popular lab tests fetched successfully.',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}
