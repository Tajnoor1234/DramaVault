<?php

namespace App\Http\Controllers;

use App\Models\Drama;
use App\Models\Genre;
use App\Models\Cast;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class DramaController extends Controller
{
    // Admin: Manage all dramas
    public function adminIndex(Request $request)
    {
        $query = Drama::with(['genres']);

        // Search
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'rating':
                $query->orderBy('avg_rating', 'desc');
                break;
            case 'views':
                $query->orderBy('total_views', 'desc');
                break;
            case 'title':
                $query->orderBy('title');
                break;
            default:
                $query->latest();
        }

        $dramas = $query->paginate(20)->withQueryString();
        
        return view('admin.dramas.index', compact('dramas'));
    }

    // Public: Browse dramas
    public function index(Request $request)
    {
        $query = Drama::with(['genres', 'ratings']);

        // Search
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by genre
        if ($request->has('genre')) {
            $query->whereHas('genres', function ($q) use ($request) {
                $q->where('slug', $request->genre);
            });
        }

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filter by country
        if ($request->has('country')) {
            $query->where('country', $request->country);
        }

        // Filter by year
        if ($request->has('year')) {
            $query->where('release_year', $request->year);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'rating':
                $query->orderBy('avg_rating', 'desc');
                break;
            case 'popular':
                $query->orderBy('total_views', 'desc');
                break;
            case 'title':
                $query->orderBy('title');
                break;
            default:
                $query->latest();
        }

        $dramas = $query->paginate(12);
        $genres = Genre::all();
        
        return view('dramas.index', compact('dramas', 'genres'));
    }

    public function show(Drama $drama)
    {
        // Increment views
        $drama->increment('total_views');
        
        $drama->load([
            'genres', 
            'cast', 
            'comments' => function($query) {
                $query->latest()->with(['user', 'replies' => function($q) {
                    $q->with(['user', 'replies' => function($q2) {
                        $q2->with(['user', 'replies.user']);
                    }]);
                }]);
            },
            'allComments',
            'ratings.user'
        ]);
        
        // Get user's rating if authenticated
        $userRating = null;
        $watchlistStatus = null;
        if (auth()->check()) {
            $userRating = $drama->ratings()->where('user_id', auth()->id())->first();
            $watchlistItem = $drama->watchlists()->where('user_id', auth()->id())->first();
            $watchlistStatus = $watchlistItem ? $watchlistItem->status : null;
        }
        
        // Get similar dramas (same genres)
        $similarDramas = Drama::whereHas('genres', function ($query) use ($drama) {
            $query->whereIn('genres.id', $drama->genres->pluck('id'));
        })
        ->where('id', '!=', $drama->id)
        ->withCount('ratings')
        ->orderBy('avg_rating', 'desc')
        ->limit(6)
        ->get();

        return view('dramas.show', compact('drama', 'similarDramas', 'userRating', 'watchlistStatus'));
    }

    public function create()
    {
        $genres = Genre::all();
        $casts = Cast::all();
        return view('admin.dramas.create', compact('genres', 'casts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'synopsis' => 'required|string',
            'type' => 'required|in:drama,movie,series',
            'episodes' => 'required|integer|min:1',
            'duration' => 'nullable|integer|min:1',
            'country' => 'required|string',
            'release_year' => 'required|integer|min:1900|max:' . (date('Y') + 5),
            'airing_date' => 'nullable|date',
            'status' => 'required|in:ongoing,completed,upcoming',
            'genres' => 'required|array|min:1',
            'genres.*' => 'exists:genres,id',
            'cast_id' => 'required|array|min:1',
            'cast_id.*' => 'exists:cast_members,id',
            'character_name' => 'required|array',
            'character_name.*' => 'required|string|max:255',
            'role_type' => 'required|array',
            'role_type.*' => 'required|in:main,supporting,guest',
            'poster' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:5120',
        ]);

        $drama = new Drama();
        $drama->title = $validated['title'];
        $drama->slug = Str::slug($validated['title']) . '-' . time();
        $drama->synopsis = $validated['synopsis'];
        $drama->type = $validated['type'];
        $drama->episodes = $validated['episodes'];
        $drama->duration = $validated['duration'];
        $drama->country = $validated['country'];
        $drama->release_year = $validated['release_year'];
        $drama->airing_date = $validated['airing_date'];
        $drama->status = $validated['status'];

        // Handle file uploads
        if ($request->hasFile('poster')) {
            $drama->poster_path = $request->file('poster')->store('dramas/posters', 'public');
        }

        if ($request->hasFile('banner')) {
            $drama->banner_path = $request->file('banner')->store('dramas/banners', 'public');
        }

        $drama->save();

        // Attach genres
        $drama->genres()->attach($validated['genres']);
        
        // Attach cast with their roles
        $castData = [];
        foreach ($validated['cast_id'] as $index => $castId) {
            if (!empty($validated['character_name'][$index])) {
                $castData[$castId] = [
                    'character_name' => $validated['character_name'][$index],
                    'role_type' => $validated['role_type'][$index] ?? 'supporting'
                ];
            }
        }
        
        if (!empty($castData)) {
            $drama->cast()->attach($castData);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Drama/Movie created successfully!');
    }

    public function edit(Drama $drama)
    {
        $genres = Genre::all();
        $casts = Cast::all();
        return view('admin.dramas.edit', compact('drama', 'genres', 'casts'));
    }

    public function update(Request $request, Drama $drama)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'synopsis' => 'required|string',
            'type' => 'required|in:drama,movie,series',
            'episodes' => 'required|integer|min:1',
            'duration' => 'nullable|integer|min:1',
            'country' => 'required|string',
            'release_year' => 'required|integer|min:1900|max:' . (date('Y') + 5),
            'airing_date' => 'nullable|date',
            'status' => 'required|in:ongoing,completed,upcoming',
            'genres' => 'required|array|min:1',
            'genres.*' => 'exists:genres,id',
            'cast_id' => 'required|array|min:1',
            'cast_id.*' => 'exists:cast_members,id',
            'character_name' => 'required|array',
            'character_name.*' => 'required|string|max:255',
            'role_type' => 'required|array',
            'role_type.*' => 'required|in:main,supporting,guest',
            'poster' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:5120',
        ]);

        $drama->title = $validated['title'];
        $drama->synopsis = $validated['synopsis'];
        $drama->type = $validated['type'];
        $drama->episodes = $validated['episodes'];
        $drama->duration = $validated['duration'];
        $drama->country = $validated['country'];
        $drama->release_year = $validated['release_year'];
        $drama->airing_date = $validated['airing_date'];
        $drama->status = $validated['status'];

        // Handle file uploads
        if ($request->hasFile('poster')) {
            // Delete old poster if it's a local file
            if ($drama->poster_path && !str_starts_with($drama->poster_path, 'http')) {
                Storage::disk('public')->delete($drama->poster_path);
            }
            $drama->poster_path = $request->file('poster')->store('dramas/posters', 'public');
        }

        if ($request->hasFile('banner')) {
            // Delete old banner if it's a local file
            if ($drama->banner_path && !str_starts_with($drama->banner_path, 'http')) {
                Storage::disk('public')->delete($drama->banner_path);
            }
            $drama->banner_path = $request->file('banner')->store('dramas/banners', 'public');
        }

        $drama->save();

        // Sync genres
        $drama->genres()->sync($validated['genres']);
        
        // Sync cast with their roles
        $castData = [];
        foreach ($validated['cast_id'] as $index => $castId) {
            if (!empty($validated['character_name'][$index])) {
                $castData[$castId] = [
                    'character_name' => $validated['character_name'][$index],
                    'role_type' => $validated['role_type'][$index] ?? 'supporting'
                ];
            }
        }
        
        if (!empty($castData)) {
            $drama->cast()->sync($castData);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Drama/Movie updated successfully!');
    }

    public function destroy(Drama $drama)
    {
        // Delete associated files only if they're local
        if ($drama->poster_path && !str_starts_with($drama->poster_path, 'http')) {
            Storage::disk('public')->delete($drama->poster_path);
        }
        if ($drama->banner_path && !str_starts_with($drama->banner_path, 'http')) {
            Storage::disk('public')->delete($drama->banner_path);
        }

        $drama->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Drama/Movie deleted successfully!'
            ]);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Drama/Movie deleted successfully!');
    }
}