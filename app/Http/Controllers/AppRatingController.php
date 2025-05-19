<?php

namespace App\Http\Controllers;

use App\Models\AppRating;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AppRatingController extends Controller
{
   public function index(Request $request)
{
    if ($request->ajax()) {
        $ratings = AppRating::with('customer:id,firstname,lastname')->latest();

        return DataTables::of($ratings)
            ->addColumn('reviewer', function ($rating) {
                return $rating->customer->firstname . ' ' . $rating->customer->lastname;
            })
            ->addColumn('review', function ($rating) {
                $stars = '';
                for ($i = 1; $i <= 5; $i++) {
                    $stars .= '<i class="mdi mdi-star' . ($i <= $rating->rating ? '' : '-outline') . '" style="color:' . ($i <= $rating->rating ? 'gold' : 'gray') . '"></i>';
                }

                $tags = '';
                if (!empty($rating->tags)) {
                    $tagList = is_array($rating->tags) ? implode(', ', $rating->tags) : $rating->tags;
                    $tags = "<div> {$tagList}</div>";
                }

                $comment = '';
                if ($rating->comment) {
                    $comment = "<div> {$rating->comment}</div>";
                }

                return "<div>{$stars}</div>{$tags}{$comment}";
            })
            ->addColumn('created_at', function ($rating) {
                return $rating->created_at->format('M d, Y');
            })
            ->addIndexColumn()
            ->rawColumns(['review'])
            ->make(true);
    }

    return view('app_ratings.index');
}

     public function store(Request $request)
    {
        $userId = $request->get('user_id');
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:100',
            'comment' => 'nullable|string|max:1000',
        ]);

        AppRating::create([
            'customer_id' => $userId,
            'rating' => $request->rating,
            'tags' => $request->tags,
            'comment' => $request->comment,
        ]);

        return response()->json(['message' => 'Rating submitted successfully']);
    }
}
