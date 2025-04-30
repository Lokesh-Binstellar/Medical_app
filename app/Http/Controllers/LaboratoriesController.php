<?php

namespace App\Http\Controllers;

use App\Models\Laboratories;
use App\Models\LabTest;
use App\Models\User;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LaboratoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $laboratorie = Laboratories::all();
        return view('laboratorie.index', compact('laboratorie'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tests = LabTest::all();
        return view('laboratorie.create', compact('tests'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->all();

        // Validate
        $validation = validator($params, [
            'lab_name' => 'required',
            'owner_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|min:11|numeric',
            'city' => 'required',
            'state' => 'required',
            'pincode' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'image' => 'nullable|file|image|max:10240',
            'username' => 'required',
            'password' => 'required',
            'license' => 'required',
            'nabl_iso_certified' => 'required|boolean',
            'pickup' => 'required',
            'gstno' => 'nullable|string|max:20',
            'test' => 'nullable|array',
            'test.*' => 'string',
            'price' => 'nullable|array',
            'homeprice' => 'nullable|array',
        ]);

        if ($validation->fails()) {
            return back()->withErrors($validation)->withInput();
        }

        $roleId = \App\Models\Role::where('name', 'laboratory')->value('id');

        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $roleId
        ]);

        $params['user_id'] = $user->id;

        // Image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('assets/image/');
            $image->move($destinationPath, $imageName);
            $params['image'] = $imageName;
        }

        $testData = [];
        $tests = $request->test ?? [];
        $prices = $request->price ?? [];
        $homeprices = $request->homeprice ?? [];

        foreach ($tests as $key => $test) {
            $testData[] = [
                'test' => $test,
                'price' => $prices[$key] ?? null,
                'homeprice' => $homeprices[$key] ?? null,
            ];
        }

        $params['test'] = json_encode($testData);
        unset($params['price'], $params['homeprice']);

        Laboratories::create($params);

        // dd( );
        return redirect()->route('laboratorie.index')
            ->with('success', 'Laboratory created successfully.');
    }

    /**
     * Display the specified resource.
     */public function show($id)
{
    // Fetch the laboratory details
    $lab = Laboratories::findOrFail($id);

    // Decode the JSON data from the 'test' column
    $testData = json_decode($lab->test, true); // Decode the JSON into an array

    // Prepare the lab tests with their names and prices
    $labTests = [];

    foreach ($testData as $test) {
        // Fetch the test name from lab_tests table based on test ID
        $testInfo = \DB::table('lab_tests')
            ->where('id', $test['test']) // Match test ID
            ->select('name as test_name')
            ->first();

        // Append the test info to the labTests array
        $labTests[] = [
            'test_name' => $testInfo ? $testInfo->test_name : 'Unknown',
            'price' => $test['price'],
            'homeprice' => $test['homeprice'],
        ];
    }

    // Pass the laboratory details and the parsed tests to the view
    return view('laboratorie.show', compact('lab', 'labTests'));
}

    
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $laboratorie = Laboratories::findOrFail($id);


        $labTests = json_decode($laboratorie->test, true) ?? [];


        $allTests = LabTest::all();

        return view('laboratorie.edit', compact('laboratorie', 'labTests', 'allTests'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $laboratorie = Laboratories::findOrFail($id);

        // Validate input
        $data = $request->validate([
            'lab_name' => 'required|string',
            'owner_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|min:11|numeric',
            'city' => 'required|string',
            'state' => 'required|string',
            'pincode' => 'required|max:9',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'username' => 'required|string',
            'license' => 'required|string',
            'pickup' => 'required|string',
            'gstno' => 'nullable|string',
            'nabl_iso_certified' => 'required|boolean',
            'image' => 'nullable|image|max:10240',
            'test.*' => 'nullable|string',
            'price.*' => 'nullable|numeric',
            'homeprice.*' => 'nullable|numeric',
        ]);

        // Update related user
        $user = User::find($laboratorie->user_id);
        if ($user) {
            $user->name = $request->username;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();
        }


        $data = $request->only([
            'lab_name',
            'owner_name',
            'email',
            'phone',
            'city',
            'state',
            'pincode',
            'address',
            'latitude',
            'longitude',
            'username',
            'license',
            'pickup',
            'gstno',
            'nabl_iso_certified'
        ]);

        // Image upload and old delete
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('assets/image/');
            $image->move($destinationPath, $imageName);
            $data['image'] = $imageName;

            if ($laboratorie->image && File::exists($destinationPath . $laboratorie->image)) {
                File::delete($destinationPath . $laboratorie->image);
            }
        }


        $existingTests = json_decode($laboratorie->test, true) ?? [];

        // Prepare test data to update
        $testData = [];
        $tests = $request->test ?? [];
        $prices = $request->price ?? [];
        $homeprices = $request->homeprice ?? [];

        foreach ($tests as $key => $testId) {
            if (!empty($testId)) {

                $found = false;
                foreach ($existingTests as &$existingTest) {
                    if ($existingTest['test'] == $testId) {
                        $existingTest['price'] = $prices[$key] ?? 0;
                        $existingTest['homeprice'] = $homeprices[$key] ?? 0;
                        $found = true;
                        break;
                    }
                }

                // If the test is not found, add a new one
                if (!$found) {
                    $testData[] = [
                        'test' => $testId,
                        'price' => $prices[$key] ?? 0,
                        'homeprice' => $homeprices[$key] ?? 0,
                    ];
                }
            }
        }


        $mergedTests = array_merge($existingTests, $testData);


        $uniqueTests = [];
        foreach ($mergedTests as $test) {
            $uniqueTests[$test['test']] = $test;
        }

        // Re-index the array
        $testData = array_values($uniqueTests);


        $data['test'] = json_encode($testData);


        $laboratorie->update($data);
        // dd($laboratorie);

        return redirect()->route('laboratorie.index')
            ->with('success', 'Laboratory updated successfully!');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $laboratorie = Laboratories::findOrFail($id);
        // dd( $laboratorie);
        $laboratorie->delete();

        return redirect()->route('laboratorie.index')
            ->with('success', 'laboratorie deleted successfully');
    }
}
