<?php

namespace Database\Factories;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NewsFactory extends Factory
{
    protected $model = News::class;

    public function definition(): array
    {
        $title = fake()->sentence();
        $categories = ['K-Drama', 'J-Drama', 'C-Drama', 'Industry News', 'Awards', 'Casting'];
        
        return [
            'title' => rtrim($title, '.'),
            'slug' => Str::slug($title),
            'excerpt' => fake()->sentence(15),
            'content' => fake()->paragraphs(5, true),
            'category' => fake()->randomElement($categories),
            'is_published' => true,
            'user_id' => User::factory(),
            'published_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
