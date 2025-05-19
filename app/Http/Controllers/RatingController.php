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
            return response()->json([
                'message' => 'Unauthorized',
                'success' => false
            ], 401);
        }

        // Detect the type (Laboratory or Pharmacy)
        $type = null;

        if (Laboratories::where('user_id', $request->rateable_id)->exists()) {
            $type = 'Laboratory';
        } elseif (Pharmacies::where('user_id', $request->rateable_id)->exists()) {
            $type = 'Pharmacy';
        } else {
            return response()->json([
                'message' => 'Invalid rateable ID',
                'success' => false
            ], 404);
        }

        // Check if rating already exists
        $existing = Rating::where('customer_id', $userId)
            ->where('rateable_id', $request->rateable_id)
            ->where('rateable_type', $type)
            ->first();

        if ($existing) {
            // Update existing rating
            $existing->rating = $request->rating;
            $existing->save();

            return response()->json([
                'message' => 'Rating updated successfully',
                'success' => true
            ]);
        }

        // Create new rating
        Rating::create([
            'customer_id'   => $userId,
            'rating'        => $request->rating,
            'rateable_id'   => $request->rateable_id,
            'rateable_type' => $type,
        ]);

        return response()->json([
            'message' => 'Rating submitted successfully',
            'success' => true
        ]);
    }
}
