<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use App\Models\Drama;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $watchlists = Watchlist::with(['drama.genres'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        // Group by status
        $statuses = [
            'watching' => $watchlists->where('status', 'watching'),
            'completed' => $watchlists->where('status', 'completed'),
            'plan_to_watch' => $watchlists->where('status', 'plan_to_watch'),
            'on_hold' => $watchlists->where('status', 'on_hold'),
            'dropped' => $watchlists->where('status', 'dropped'),
        ];

        return view('users.watchlist', compact('statuses'));
    }

    public function store(Request $request, Drama $drama)
    {
        $request->validate([
            'status' => 'required|in:watching,completed,on_hold,dropped,plan_to_watch',
        ]);

        $watchlist = Watchlist::where('user_id', auth()->id())
            ->where('drama_id', $drama->id)
            ->first();

        if ($watchlist) {
            $watchlist->update(['status' => $request->status]);
            $message = 'Watchlist updated successfully!';
        } else {
            Watchlist::create([
                'user_id' => auth()->id(),
                'drama_id' => $drama->id,
                'status' => $request->status,
            ]);
            $message = 'Added to watchlist successfully!';
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return back()->with('success', $message);
    }

    public function update(Request $request, Watchlist $watchlist)
    {
        $this->authorize('update', $watchlist);

        $request->validate([
            'status' => 'required|in:watching,completed,on_hold,dropped,plan_to_watch',
        ]);

        $watchlist->update(['status' => $request->status]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Watchlist updated successfully!');
    }

    public function destroy(Watchlist $watchlist)
    {
        $this->authorize('delete', $watchlist);

        $watchlist->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Removed from watchlist!');
    }

    public function removeByDrama(Drama $drama)
    {
        $watchlist = Watchlist::where('user_id', auth()->id())
            ->where('drama_id', $drama->id)
            ->first();

        if ($watchlist) {
            $watchlist->delete();
            
            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Removed from watchlist!']);
            }

            return back()->with('success', 'Removed from watchlist!');
        }

        if (request()->expectsJson()) {
            return response()->json(['success' => false, 'message' => 'Drama not in watchlist'], 404);
        }

        return back()->with('error', 'Drama not in watchlist');
    }
}