<?php

namespace App\Http\Controllers;

use App\Models\Laboratories;
use App\Models\Pharmacies;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $userId = $request->get('user_id');

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'rateable_id' => 'required|integer',
        ]);



        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Determine the type: Laboratory or Pharmacy
        $type = null;

        if (Laboratories::where('user_id', $request->rateable_id)->exists()) {
            $type = 'Laboratory';
        } elseif (Pharmacies::where('user_id', $request->rateable_id)->exists()) {
            $type = 'Pharmacy';
        } else {
            return response()->json(['error' => 'Invalid rateable ID'], 404);
        }

        // Store the rating
        $rating = Rating::create([
            'customer_id'    => $userId,
            'rating'         => $request->rating,
            'rateable_id'    => $request->rateable_id,
            'rateable_type'  => $type,
        ]);

        return response()->json([
            'message' => 'Rating submitted successfully',
            'data'    => $rating
        ], 201);
    }
   public function popularPharmacies()
{
    $popularPharmacies = Rating::select('rateable_id')
        ->where('rateable_type', 'Pharmacy')
        ->groupBy('rateable_id')
        ->selectRaw('rateable_id, AVG(rating) as rating, COUNT(*) as total_ratings')
        ->orderByDesc('rating')
        ->take(10)
        ->get()
        ->map(function($item) {
            return [
                'pharmacy_id'   => $item->rateable_id,
                'rating'        => $item->rating,
                'total_ratings' => $item->total_ratings,
            ];
        });

    return response()->json([
        'message' => 'Popular pharmacies fetched successfully',
        'success' => true,
        'data'    => $popularPharmacies
    ]);
}

public function popularLaboratories()
{
    $popularLabs = Rating::select('rateable_id')
        ->where('rateable_type', 'Laboratory')
        ->groupBy('rateable_id')
        ->selectRaw('rateable_id, AVG(rating) as rating, COUNT(*) as total_ratings')
        ->orderByDesc('rating')
        ->take(10)
        ->get()
        ->map(function($item) {
            return [
                'laboratory_id' => $item->rateable_id,
                'rating'       => $item->rating,
                'total_ratings'=> $item->total_ratings,
            ];
        });

    return response()->json([
        'message' => 'Popular laboratories fetched successfully',
        'success' => true,
        'data'    => $popularLabs
    ]);
}

}
