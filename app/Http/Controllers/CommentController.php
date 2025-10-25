<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Drama;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
            'drama_id' => 'required_without:news_id|nullable|exists:dramas,id',
            'news_id' => 'required_without:drama_id|nullable|exists:news,id',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $data = [
            'user_id' => auth()->id(),
            'drama_id' => $request->drama_id ? (int)$request->drama_id : null,
            'news_id' => $request->news_id ? (int)$request->news_id : null,
            'parent_id' => $request->parent_id ? (int)$request->parent_id : null,
            'body' => $request->body,
        ];

        // Use Query Builder to bypass any Eloquent issues
        $commentId = DB::table('comments')->insertGetId([
            'user_id' => $data['user_id'],
            'drama_id' => $data['drama_id'],
            'news_id' => $data['news_id'],
            'parent_id' => $data['parent_id'],
            'body' => $data['body'],
            'likes_count' => 0,
            'dislikes_count' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $comment = Comment::find($commentId);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'comment' => $comment->load('user'),
            ]);
        }

        return back()->with('success', 'Comment added successfully!');
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $comment->update([
            'body' => $request->body,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'comment' => $comment,
            ]);
        }

        return back()->with('success', 'Comment updated successfully!');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Comment deleted successfully!');
    }

    public function like(Comment $comment)
    {
        $like = $comment->likes()->where('user_id', auth()->id())->first();

        if ($like) {
            if ($like->is_like) {
                $like->delete();
                $comment->decrement('likes_count');
            } else {
                $like->update(['is_like' => true]);
                $comment->decrement('dislikes_count');
                $comment->increment('likes_count');
            }
        } else {
            $comment->likes()->create([
                'user_id' => auth()->id(),
                'is_like' => true,
            ]);
            $comment->increment('likes_count');
        }

        return response()->json([
            'success' => true,
            'likes_count' => $comment->likes_count,
            'dislikes_count' => $comment->dislikes_count,
        ]);
    }

    public function dislike(Comment $comment)
    {
        $like = $comment->likes()->where('user_id', auth()->id())->first();

        if ($like) {
            if (!$like->is_like) {
                $like->delete();
                $comment->decrement('dislikes_count');
            } else {
                $like->update(['is_like' => false]);
                $comment->decrement('likes_count');
                $comment->increment('dislikes_count');
            }
        } else {
            $comment->likes()->create([
                'user_id' => auth()->id(),
                'is_like' => false,
            ]);
            $comment->increment('dislikes_count');
        }

        return response()->json([
            'success' => true,
            'likes_count' => $comment->likes_count,
            'dislikes_count' => $comment->dislikes_count,
        ]);
    }

    public function getUserLikes(Request $request)
    {
        $commentIds = $request->input('comment_ids', []);
        
        if (empty($commentIds) || !auth()->check()) {
            return response()->json([]);
        }

        $likes = \DB::table('comment_likes')
            ->whereIn('comment_id', $commentIds)
            ->where('user_id', auth()->id())
            ->get(['comment_id', 'is_like']);

        $result = [];
        foreach ($likes as $like) {
            $result[$like->comment_id] = $like->is_like ? 'like' : 'dislike';
        }

        return response()->json($result);
    }
}