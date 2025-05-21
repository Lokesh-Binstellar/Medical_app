<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LabTest;

class AddLabTestController extends Controller
{
    public function index()
    {
        return view('laboratorie.addLabTest.index');
    }



    public function search(Request $request)
    {
        $query = $request->input('query');

        // Search in medicines table using product_id
        $results = LabTest::where('name', 'like', "%{$query}%")
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->product_id, // use product_id instead of id
                    'text' => "{$item->name} "
                   
                ];
            });



        return response()->json([
            'results' => $results,
        ]);
    }
}
