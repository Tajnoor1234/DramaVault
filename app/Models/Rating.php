<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'drama_id', 'rating', 'review'];

    protected $casts = [
        'rating' => 'integer',
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

    // Events
    protected static function booted()
    {
        static::saved(function ($rating) {
            $rating->drama->updateRatingStats();
        });

        static::deleted(function ($rating) {
            $rating->drama->updateRatingStats();
        });
    }
}