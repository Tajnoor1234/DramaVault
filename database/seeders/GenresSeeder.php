<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenresSeeder extends Seeder
{
    public function run()
    {
        $genres = [
            'Romance', 'Comedy', 'Drama', 'Action', 'Thriller',
            'Mystery', 'Fantasy', 'Sci-Fi', 'Horror', 'Historical',
            'Medical', 'Legal', 'School', 'Family', 'Musical',
            'Sports', 'Crime', 'Adventure', 'Supernatural', 'Slice of Life'
        ];

        foreach ($genres as $genre) {
            Genre::create([
                'name' => $genre,
                'slug' => \Illuminate\Support\Str::slug($genre),
                'description' => "Explore the best {$genre} dramas and movies.",
            ]);
        }
    }
}