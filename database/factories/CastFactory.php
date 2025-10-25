<?php

namespace Database\Factories;

use App\Models\Cast;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CastFactory extends Factory
{
    protected $model = Cast::class;

    public function definition(): array
    {
        $name = fake()->name();
        $genders = ['male', 'female', 'other'];
        
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'bio' => fake()->paragraphs(2, true),
            'birth_date' => fake()->date('Y-m-d', '-20 years'),
            'birth_place' => fake()->city() . ', ' . fake()->country(),
            'gender' => fake()->randomElement($genders),
        ];
    }
}
