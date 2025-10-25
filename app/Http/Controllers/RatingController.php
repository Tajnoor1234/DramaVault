<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Drama;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Drama $drama)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:10',
            'review' => 'nullable|string|max:1000',
        ]);

        // Check if user already rated this drama
        $existingRating = Rating::where('user_id', auth()->id())
            ->where('drama_id', $drama->id)
            ->first();

        if ($existingRating) {
            return back()->with('error', 'You have already rated this drama.');
        }

        Rating::create([
            'user_id' => auth()->id(),
            'drama_id' => $drama->id,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        // Update drama rating stats
        $drama->updateRatingStats();

        return back()->with('success', 'Rating submitted successfully!');
    }

    public function update(Request $request, Rating $rating)
    {
        $this->authorize('update', $rating);

        $request->validate([
            'rating' => 'required|integer|min:1|max:10',
            'review' => 'nullable|string|max:1000',
        ]);

        $rating->update([
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        // Update drama rating stats
        $rating->drama->updateRatingStats();

        return back()->with('success', 'Rating updated successfully!');
    }

    public function destroy(Rating $rating)
    {
        $this->authorize('delete', $rating);

        $drama = $rating->drama;
        $rating->delete();

        // Update drama rating stats
        $drama->updateRatingStats();

        return back()->with('success', 'Rating deleted successfully!');
    }
}