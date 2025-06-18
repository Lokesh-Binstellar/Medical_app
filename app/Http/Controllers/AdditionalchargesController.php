<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Additionalcharges;
use Illuminate\Support\Facades\Auth;

class AdditionalchargesController extends Controller
{
    public function index()
    {
        // dd('index');
        $charge = AdditionalCharges::first();
        return view('additionalcharges');
    }

    public function storeOrUpdate(Request $request, $id = null)
    {
        $request->validate([
            'platfrom_fee' => 'required|numeric',
        ]);

        $charge = AdditionalCharges::first();

        if ($charge) {
            // Update existing record
            $charge->update([
                'platfrom_fee' => $request->input('platfrom_fee'),
            ]);
        } else {
            // Create new record if none exists
            AdditionalCharges::create([
                'platfrom_fee' => $request->input('platfrom_fee'),
            ]);
        }

        return redirect()->route('additionalcharges')->with('success', 'Platform fee saved successfully!');
    }

    public function showForm($id = null)
    {
        $charge = AdditionalCharges::first();
        return view('additionalcharges', compact('charge'));
    }

}
