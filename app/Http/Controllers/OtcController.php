<?php

namespace App\Http\Controllers;

use App\Imports\OtcImport;
use App\Models\Otcmedicine;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OtcController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


        $query = Otcmedicine::query();

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;

            // Search both product_id and product_name
            $query->where(function ($q) use ($searchTerm) {
                $q->where('otc_id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('name', 'like', '%' . $searchTerm . '%');
            });
        }

        // Paginate the results, you can change 100 to whatever number you want per page
        $otc = $query->paginate(100);
        return view('otcmedicine.index', compact('otc'));
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
    public function show(string $id)
    {
        $otcmedicine = Otcmedicine::find($id);

        return view('otcmedicine.show', compact('otcmedicine'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function import(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'file' => 'required|max:2048',
        ]);

        $file = Excel::import(new OtcImport, $request->file('file'));

        return back()->with('success', 'OtcMedicine imported successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
