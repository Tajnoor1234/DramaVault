<?php

namespace App\Http\Controllers;

use App\Models\Drama;
use App\Models\User;
use App\Models\News;
use App\Models\Rating;
use App\Models\Comment;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch latest added/imported dramas, series, and movies
        $latestDramas = Drama::with(['genres', 'ratings'])
            ->latest('created_at')
            ->limit(12)
            ->get();

        // Fetch trending dramas (most viewed)
        $trendingDramas = Drama::with(['genres', 'ratings'])
            ->orderBy('total_views', 'desc')
            ->orderBy('avg_rating', 'desc')
            ->limit(8)
            ->get();

        // Fetch top rated dramas
        $topRatedDramas = Drama::with(['genres', 'ratings'])
            ->where('avg_rating', '>', 0)
            ->orderBy('avg_rating', 'desc')
            ->orderBy('total_ratings', 'desc')
            ->limit(8)
            ->get();

        $latestNews = News::where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->limit(3)
            ->get();

        $totalDramas = Drama::count();
        $totalUsers = User::count();
        $totalRatings = Rating::count();
        $totalComments = Comment::count();

        return view('home', compact(
            'latestDramas', 
            'trendingDramas', 
            'topRatedDramas',
            'latestNews', 
            'totalDramas', 
            'totalUsers', 
            'totalRatings', 
            'totalComments'
        ));
    }
}