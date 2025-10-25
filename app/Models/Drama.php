<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drama extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'synopsis',
        'poster_path',
        'banner_path',
        'type',
        'episodes',
        'duration',
        'country',
        'release_year',
        'airing_date',
        'status',
        'avg_rating',
        'total_ratings',
        'total_views',
        'imdb_id',
        'tags',
        'is_featured',
    ];

    protected $casts = [
        'airing_date' => 'date',
        'tags' => 'array',
        'is_featured' => 'boolean',
        'avg_rating' => 'decimal:1',
        'total_ratings' => 'integer',
        'total_views' => 'integer',
    ];

    // Relationships
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'drama_genre');
    }

    public function cast()
    {
        return $this->belongsToMany(Cast::class, 'drama_cast', 'drama_id', 'cast_member_id')
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

    // Methods
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getPosterUrlAttribute()
    {
        if (!$this->poster_path) {
            return asset('images/default-poster.png');
        }
        
        // If it's already a full URL (from OMDb or placeholder), return it
        if (str_starts_with($this->poster_path, 'http://') || str_starts_with($this->poster_path, 'https://')) {
            return $this->poster_path;
        }
        
        // Otherwise it's a local path
        return asset('storage/' . $this->poster_path);
    }

    public function getBannerUrlAttribute()
    {
        if (!$this->banner_path) {
            return asset('images/default-banner.png');
        }
        
        // If it's already a full URL (from OMDb or placeholder), return it
        if (str_starts_with($this->banner_path, 'http://') || str_starts_with($this->banner_path, 'https://')) {
            return $this->banner_path;
        }
        
        // Otherwise it's a local path
        return asset('storage/' . $this->banner_path);
    }

    public function updateRatingStats()
    {
        $avgRating = $this->ratings()->avg('rating');
        $this->avg_rating = $avgRating ? round($avgRating, 1) : 0;
        $this->total_ratings = $this->ratings()->count();
        $this->save();
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeTrending($query)
    {
        return $query->orderBy('total_views', 'desc')
                    ->orderBy('avg_rating', 'desc');
    }

    public function getLeadCastAttribute()
    {
        return $this->cast()->wherePivot('role_type', 'lead')->get();
    }
}