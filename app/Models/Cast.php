<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cast extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'bio', 'birth_date', 'birth_place', 
        'photo_path', 'gender', 'social_links'
    ];

    protected $casts = [
        'social_links' => 'array',
        'birth_date' => 'date',
    ];

    public function dramas()
    {
        return $this->belongsToMany(Drama::class, 'drama_cast')
                    ->withPivot('character_name', 'role_type')
                    ->withTimestamps();
    }

    public function getPhotoUrl()
    {
        return $this->photo_path ? asset('storage/' . $this->photo_path) : asset('images/default-avatar.jpg');
    }

    public function getAgeAttribute()
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }
}