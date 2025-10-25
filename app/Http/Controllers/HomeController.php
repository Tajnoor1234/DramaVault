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
        $featuredDramas = Drama::with(['genres', 'ratings'])
            ->where('is_featured', true)
            ->latest()
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

        return view('home', compact('featuredDramas', 'latestNews', 'totalDramas', 'totalUsers', 'totalRatings', 'totalComments'));
    }
}