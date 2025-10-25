<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'drama_id', 
        'news_id', 
        'parent_id', 
        'body', 
        'likes_count', 
        'dislikes_count'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function drama()
    {
        return $this->belongsTo(Drama::class);
    }

    public function news()
    {
        return $this->belongsTo(News::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function likes()
    {
        return $this->hasMany(CommentLike::class);
    }

    // Methods
    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->where('is_like', true)->exists();
    }

    public function isDislikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->where('is_like', false)->exists();
    }
}