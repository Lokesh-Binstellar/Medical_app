<?php

// app/Http/Controllers/PopularLabTestController.php

namespace App\Http\Controllers;

use App\Helpers\LocationHelper;
use App\Models\Laboratories;
use Illuminate\Http\Request;
use App\Models\LabTest;
use App\Models\PopularLabTest;
use Yajra\DataTables\Facades\DataTables;

class PopularLabTestController extends Controller
{
    // Show page with dropdown
    public function index(Request $request)
    {

        // dd($request);
        if ($request->ajax()) {
            $data = PopularLabTest::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
        <div class="dropdown">
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="dropdown">Action</button>
            <ul class="dropdown-menu">
                <li>
                    <a href="javascript:void(0);" onclick="deleteTest(' . $row->id . ')" class="dropdown-item">Delete</a>
                </li>
            </ul>
        </div>
    ';
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
            'test_id' => $labTest->id,
            'name' => $labTest->name,
            'contains' => $labTest->contains,
        ]);

        return redirect()->back()->with('success', 'Popular Lab Test added!');
    }


    public function destroy(string $id, Request $request)
    {
        try {
            $labtest = PopularLabTest::findOrFail($id);
            $labtest->delete();

            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }

            return redirect()->route('popular_lab_test.index')->with('success', 'Popular Lab Test deleted successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete.'], 500);
            }
            return redirect()->route('popular_lab_test.index')->with('error', 'Failed to delete popular lab test.');
        }
    }



    public function getAll()
    {
        try {
            $data = PopularLabTest::select('id', 'name', 'contains', 'test_id')->get();
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

    public function listLabTest(Request $request, $test_id)
    {
        $city = LocationHelper::getCityofCurrentUser($request);
        $labTest = LabTest::find($test_id);

        if (!$labTest) {
            return response()->json([
                'status' => false,
                'message' => 'Lab test not found.',
                'data' => null
            ], 404);
        }

        // Hide unwanted columns
        $labTest->makeHidden(['created_at', 'updated_at', 'reports_in']);

        // Fetch labs offering this test
        $labs = Laboratories::where('city', $city)->get();

        $matchingLabs = [];

        foreach ($labs as $lab) {
            // Manually decode in case test is stored as JSON string
            $tests = is_string($lab->test) ? json_decode($lab->test, true) : $lab->test;

            // Ensure it's a valid array before proceeding
            if (is_array($tests)) {
                foreach ($tests as $testEntry) {
                    if ((string) $testEntry['test'] === (string) $test_id) {
                        $matchingLabs[] = array_merge($testEntry, [
                            'lab_id' => $lab->id,
                            'lab_name' => $lab->lab_name,
                        ]);
                        break;
                    }
                }
            }
        }

        // echo "<pre>";
        // print_r($matchingLabs);
        // die;

        // 👇 Hide the columns you don’t want to expose
        $labTest->makeHidden(['created_at', 'updated_at', 'reports_in']);   // add any column names here

        return response()->json([
            'status' => true,
            'data' => [
                'test' => $labTest,
                'labs' => $matchingLabs
            ]
        ]);
    }
}
