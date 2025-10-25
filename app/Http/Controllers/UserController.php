<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(User $user)
    {
        $user->loadCount(['ratings', 'comments', 'followers', 'following']);
        
        $ratings = $user->ratings()
            ->with('drama')
            ->latest()
            ->limit(10)
            ->get();
            
        $watchlists = $user->watchlists()
            ->with('drama')
            ->get()
            ->groupBy('status');

        return view('users.show', compact('user', 'ratings', 'watchlists'));
    }

    public function edit()
    {
        return view('users.edit');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|max:2048',
            'theme_preference' => 'required|in:light,dark',
        ]);

        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->bio = $validated['bio'];
        $user->theme_preference = $validated['theme_preference'];

        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $user->avatar_path = $request->file('avatar')->store('avatars', 'public');
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    public function follow(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        auth()->user()->following()->syncWithoutDetaching([$user->id]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', "You are now following {$user->name}");
    }

    public function unfollow(User $user)
    {
        auth()->user()->following()->detach($user->id);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', "You have unfollowed {$user->name}");
    }
}