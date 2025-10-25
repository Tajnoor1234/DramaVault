<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cast extends Model
{
    use HasFactory;

    protected $table = 'cast_members';

    protected $fillable = [
        'name', 
        'slug', 
        'bio', 
        'birth_date', 
        'birth_place', 
        'image_path', 
        'gender', 
        'social_links'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'social_links' => 'array',
    ];

    public function dramas()
    {
        return $this->belongsToMany(Drama::class, 'drama_cast', 'cast_member_id', 'drama_id')
                    ->withPivot('character_name', 'role_type')
                    ->withTimestamps();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getImageUrlAttribute()
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : asset('images/default-cast.png');
    }

    public function getAgeAttribute()
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }
}