<?php

namespace App\Http\Controllers;

use App\Models\ZipCodeViceDelivery;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ZipCodeViceDeliveryController extends Controller
{
   public function index(Request $request)
{
    if ($request->ajax()) {
        $data = ZipCodeViceDelivery::select(['id', 'zipcode']);
        return DataTables::of($data)->make(true);
    }

    return view('zip_code_vise_delivery.index');
}

public function uploadZipcodes(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls,csv',
    ]);

    $file = $request->file('file');
    $data = Excel::toArray([], $file);

    foreach ($data[0] as $row) {
        if (!empty($row[0])) {
            ZipCodeViceDelivery::create([
                'zipcode' => $row[0]
            ]);
        }
    }

    return redirect()->back()->with('success', 'Zipcodes imported successfully!');
}

public function getZipcodes()
{
    $zipcodes = ZipCodeViceDelivery::pluck('zipcode');
    return response()->json([
        'success' => true,
        'data' => $zipcodes
    ]);
}


public function deleteAll()
{
    ZipCodeViceDelivery::truncate();

    return redirect()->back()->with('success', 'All zip codes deleted successfully!');
}

}
