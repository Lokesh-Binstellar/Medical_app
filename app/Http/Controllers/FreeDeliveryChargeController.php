<?php

namespace App\Http\Controllers;

use App\Models\Laboratories;
use Illuminate\Http\Request;
use App\Models\Pharmacies;
use Illuminate\Support\Facades\Auth;

class FreeDeliveryChargeController extends Controller
{
    //     public function index()
    //     {
    //         $pharmacies = Pharmacies::select('id', 'pharmacies_name', 'free_delivery_charge')->get();
    //         $laboratories = Laboratories::select('id', 'lab_name', 'free_delivery_charge')->get();

    //         return view('free_delivery_charge.index', compact('pharmacies', 'laboratories'));
    //     }


    // public function update(Request $request)
    // {
    //     $request->validate([
    //         'free_delivery_charge' => 'required|numeric',
    //     ]);

    //     $user = Auth::user(); // Get the currently logged-in user

    //     if ($user && $user->pharmacies) {
    //         $user->pharmacies->update([
    //             'free_delivery_charge' => $request->free_delivery_charge,
    //         ]);
    //     }

    //     return redirect()->back()->with('success', 'Free delivery charge updated successfully.');
    // }



    public function freedeliveryindex()
    {
        $user = Auth::user();
        $pharmacy = $user->pharmacies;
        $lab = $user->laboratories;

        return view('freedeliverycharge', compact('pharmacy', 'lab'));
    }

    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'free_delivery_charge' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        $pharmacy = $user->pharmacies;
        $lab = $user->laboratories;

        if ($pharmacy) {
            $pharmacy->update([
                'free_delivery_charge' => $request->input('free_delivery_charge'),
            ]);
            return redirect()->route('free_delivery_charge')->with('success', 'Pharmacy Free delivery charge updated successfully.');
        }

        if ($lab) {
            $lab->update([
                'free_delivery_charge' => $request->input('free_delivery_charge'),
            ]);
            return redirect()->route('free_delivery_charge')->with('success', 'Laboratory Free delivery charge updated successfully.');
        }

        return redirect()->back()->with('error', 'Pharmacy or Laboratory not found for the logged-in user.');
    }
}
