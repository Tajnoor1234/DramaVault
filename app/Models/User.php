<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'bio',
        'theme',
        'is_admin',
        'is_moderator',
        'preferences',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'preferences' => 'array',
        'is_admin' => 'boolean',
        'is_moderator' => 'boolean',
    ];

    // Relationships
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function watchlists()
    {
        return $this->hasMany(Watchlist::class);
    }

    public function followers()
    {
        return $this->hasMany(Follow::class, 'following_id');
    }

    public function following()
    {
        return $this->hasMany(Follow::class, 'follower_id');
    }

    public function news()
    {
        return $this->hasMany(News::class);
    }

    // Helpers
    public function isFollowing(User $user)
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    public function hasRated(Drama $drama)
    {
        return $this->ratings()->where('drama_id', $drama->id)->exists();
    }

    public function getRatingForDrama(Drama $drama)
    {
        $rating = $this->ratings()->where('drama_id', $drama->id)->first();
        return $rating ? $rating->rating : null;
    }

    public function getWatchlistStatus(Drama $drama)
    {
        $watchlist = $this->watchlists()->where('drama_id', $drama->id)->first();
        return $watchlist ? $watchlist->status : null;
    }
}