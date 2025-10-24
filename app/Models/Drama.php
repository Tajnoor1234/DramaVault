<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drama extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'synopsis', 'poster_path', 'banner_path', 'type',
        'episodes', 'duration', 'country', 'release_year', 'status',
        'rating', 'rating_count', 'views', 'is_featured', 'trailer_url'
    ];

    protected $casts = [
        'trailer_url' => 'array',
        'is_featured' => 'boolean',
    ];

    // Relationships
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'drama_genre');
    }

    public function cast()
    {
        return $this->belongsToMany(Cast::class, 'drama_cast')
                    ->withPivot('character_name', 'role_type')
                    ->withTimestamps();
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    public function allComments()
    {
        return $this->hasMany(Comment::class);
    }

    public function watchlists()
    {
        return $this->hasMany(Watchlist::class);
    }

    // Scopes
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('views', 'desc');
    }

    public function scopeTopRated($query)
    {
        return $query->where('rating_count', '>=', 10)
                    ->orderBy('rating', 'desc');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Helpers
    public function getPosterUrl()
    {
        return $this->poster_path ? asset('storage/' . $this->poster_path) : asset('images/default-poster.jpg');
    }

    public function getBannerUrl()
    {
        return $this->banner_path ? asset('storage/' . $this->banner_path) : asset('images/default-banner.jpg');
    }

    public function updateRating()
    {
        $avgRating = $this->ratings()->avg('rating');
        $count = $this->ratings()->count();
        
        $this->update([
            'rating' => round($avgRating, 1),
            'rating_count' => $count
        ]);
    }
}