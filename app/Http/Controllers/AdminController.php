<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Drama;
use App\Models\News;
use App\Models\Comment;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_dramas' => Drama::count(),
            'total_news' => News::count(),
            'total_comments' => Comment::count(),
            'total_ratings' => Rating::count(),
            'drama_count' => Drama::where('type', 'drama')->count(),
            'movie_count' => Drama::where('type', 'movie')->count(),
            'series_count' => Drama::where('type', 'series')->count(),
        ];

        // Get monthly ratings data for the last 6 months
        $monthlyRatings = Rating::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Fill in missing months with 0
        $ratingsData = [];
        $ratingsLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthKey = $month->format('Y-m');
            $monthLabel = $month->format('M');
            
            $count = $monthlyRatings->firstWhere('month', $monthKey)?->count ?? 0;
            
            $ratingsLabels[] = $monthLabel;
            $ratingsData[] = $count;
        }

        // Get monthly new users for the last 6 months
        $monthlyUsers = User::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        $usersData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthKey = $month->format('Y-m');
            $count = $monthlyUsers->firstWhere('month', $monthKey)?->count ?? 0;
            $usersData[] = $count;
        }

        $recentDramas = Drama::with(['genres', 'ratings'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentDramas', 'ratingsLabels', 'ratingsData', 'usersData'));
    }

    public function users(Request $request)
    {
        $query = User::withCount(['ratings', 'comments', 'watchlists']);

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'name':
                $query->orderBy('name');
                break;
            case 'most_active':
                $query->orderBy('ratings_count', 'desc');
                break;
            default:
                $query->latest();
        }

        $users = $query->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function banUser(Request $request, User $user)
    {
        if ($user->role === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot ban admin users'
            ], 403);
        }

        $user->update(['is_active' => false]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "User '{$user->name}' has been banned successfully"
            ]);
        }

        return back()->with('success', "User '{$user->name}' has been banned");
    }

    public function unbanUser(Request $request, User $user)
    {
        $user->update(['is_active' => true]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "User '{$user->name}' has been unbanned successfully"
            ]);
        }

        return back()->with('success', "User '{$user->name}' has been unbanned");
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,moderator,user',
        ]);

        $user->update(['role' => $request->role]);

        return back()->with('success', "User role updated to {$request->role}");
    }

    public function news()
    {
        $news = News::with('author')
            ->latest('published_at')
            ->paginate(20);

        return view('admin.news.index', compact('news'));
    }

    public function casts(Request $request)
    {
        $query = \App\Models\Cast::withCount('dramas');

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('bio', 'like', '%' . $request->search . '%');
        }

        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Sort
        $sort = $request->get('sort', 'name');
        switch ($sort) {
            case 'latest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'most_dramas':
                $query->orderBy('dramas_count', 'desc');
                break;
            default:
                $query->orderBy('name');
        }

        $casts = $query->paginate(20)->withQueryString();

        return view('admin.casts.index', compact('casts'));
    }
}