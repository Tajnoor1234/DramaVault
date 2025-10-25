<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'drama_id', 'status'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function drama()
    {
        return $this->belongsTo(Drama::class);
    }

    // Scopes
    public function scopeWatching($query)
    {
        return $query->where('status', 'watching');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePlanToWatch($query)
    {
        return $query->where('status', 'plan_to_watch');
    }
}