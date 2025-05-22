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
                    'id' => $item->id, // use product_id instead of id
                    'text' => "{$item->name} "
                   
                ];
            });



        return response()->json([
            'results' => $results,
        ]);
    }




public function getContains(Request $request)
{
    $id = $request->input('id');
    try {
        if (!$id) {
            return response()->json([
                'status' => false,
                'message' => 'Lab Test ID is required.'
            ], 400);
        }
        
        $labTest = LabTest::select('contains')->find($id);
       
        // echo $labTest;die; 

        if ($labTest) {
            return response()->json([
                'status' => true,
                'contains' => $labTest->contains ?? ''
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Lab test not found'
        ], 404);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Something went wrong',
            'error' => $e->getMessage()
        ], 500);
    }
}







}
