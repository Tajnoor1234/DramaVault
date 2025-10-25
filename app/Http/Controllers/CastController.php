<?php

namespace App\Http\Controllers;

use App\Models\Cast;
use App\Models\Drama;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CastController extends Controller
{
    public function index()
    {
        $casts = Cast::withCount('dramas')->orderBy('name')->paginate(24);
        return view('casts.index', compact('casts'));
    }

    public function show(Cast $cast)
    {
        $cast->load(['dramas' => function ($query) {
            $query->orderBy('release_year', 'desc');
        }]);
        
        return view('casts.show', compact('cast'));
    }

    public function create()
    {
        return view('admin.casts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'image' => 'nullable|image|max:2048',
            'social_links' => 'nullable|array',
        ]);

        $cast = new Cast();
        $cast->name = $validated['name'];
        $cast->slug = Str::slug($validated['name']) . '-' . time();
        $cast->bio = $validated['bio'] ?? null;
        $cast->birth_date = $validated['birth_date'] ?? null;
        $cast->birth_place = $validated['birth_place'] ?? null;
        $cast->gender = $validated['gender'] ?? null;
        $cast->social_links = $validated['social_links'] ?? null;

        if ($request->hasFile('image')) {
            $cast->image_path = $request->file('image')->store('casts', 'public');
        }

        $cast->save();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Cast member "' . $cast->name . '" added successfully!');
    }

    public function edit(Cast $cast)
    {
        return view('admin.casts.edit', compact('cast'));
    }

    public function update(Request $request, Cast $cast)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'birth_place' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'image' => 'nullable|image|max:2048',
            'social_links' => 'nullable|array',
        ]);

        $cast->name = $validated['name'];
        $cast->bio = $validated['bio'] ?? null;
        $cast->birth_date = $validated['birth_date'] ?? null;
        $cast->birth_place = $validated['birth_place'] ?? null;
        $cast->gender = $validated['gender'] ?? null;
        $cast->social_links = $validated['social_links'] ?? null;

        if ($request->hasFile('image')) {
            // Delete old image if it's a local file
            if ($cast->image_path && !str_starts_with($cast->image_path, 'http')) {
                Storage::disk('public')->delete($cast->image_path);
            }
            $cast->image_path = $request->file('image')->store('casts', 'public');
        }

        $cast->save();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Cast member "' . $cast->name . '" updated successfully!');
    }

    public function destroy(Cast $cast)
    {
        // Delete image only if it's a local file
        if ($cast->image_path && !str_starts_with($cast->image_path, 'http')) {
            Storage::disk('public')->delete($cast->image_path);
        }

        $cast->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cast member deleted successfully!'
            ]);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Cast member deleted successfully!');
    }
}