<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description'];

    public function dramas()
    {
        return $this->belongsToMany(Drama::class, 'drama_genre');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}