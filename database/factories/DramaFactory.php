<?php

namespace Database\Factories;

use App\Models\Drama;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Drama>
 */
class DramaFactory extends Factory
{
    protected $model = Drama::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);
        $types = ['drama', 'movie', 'series'];
        $countries = ['South Korea', 'Japan', 'China', 'Taiwan', 'Thailand'];
        $statuses = ['ongoing', 'completed', 'upcoming'];
        
        // Generate a unique poster using placeholder service
        $posterUrl = 'https://placehold.co/300x450/1a1a2e/fff?text=' . urlencode(substr(rtrim($title, '.'), 0, 20));
        
        return [
            'title' => rtrim($title, '.'),
            'slug' => Str::slug($title),
            'synopsis' => fake()->paragraphs(3, true),
            'poster_path' => $posterUrl,
            'banner_path' => 'https://placehold.co/1920x1080/16213e/fff?text=Banner',
            'type' => fake()->randomElement($types),
            'episodes' => fake()->numberBetween(1, 50),
            'duration' => fake()->numberBetween(40, 120),
            'country' => fake()->randomElement($countries),
            'release_year' => fake()->numberBetween(2015, 2025),
            'status' => fake()->randomElement($statuses),
            'avg_rating' => fake()->randomFloat(1, 6, 10),
            'total_ratings' => fake()->numberBetween(0, 1000),
            'total_views' => fake()->numberBetween(0, 100000),
            'is_featured' => fake()->boolean(20), // 20% chance of being featured
        ];
    }
}
