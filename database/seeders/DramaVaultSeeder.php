<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Genre;
use App\Models\Drama;
use App\Models\Cast;
use App\Models\News;
use Illuminate\Support\Facades\Hash;

// class DramaVaultSeeder extends Seeder
// {
//     public function run()
//     {
//         // Create Admin User
//         $admin = User::create([
//             'name' => 'Admin User',
//             'email' => 'admin@dramavault.com',
//             'password' => Hash::make('password'),
//             'is_admin' => true,
//             'email_verified_at' => now(),
//         ]);

//         // Create Regular User
//         $user = User::create([
//             'name' => 'Test User',
//             'email' => 'user@dramavault.com',
//             'password' => Hash::make('password'),
//             'email_verified_at' => now(),
//         ]);

//         // Create Genres
//         $genres = [
//             ['name' => 'Romance', 'color' => '#e91e63', 'description' => 'Love and relationships'],
//             ['name' => 'Comedy', 'color' => '#ff9800', 'description' => 'Funny and humorous content'],
//             ['name' => 'Drama', 'color' => '#2196f3', 'description' => 'Serious, emotional stories'],
//             ['name' => 'Thriller', 'color' => '#f44336', 'description' => 'Suspenseful and exciting'],
//             ['name' => 'Action', 'color' => '#4caf50', 'description' => 'Physical action and stunts'],
//             ['name' => 'Fantasy', 'color' => '#9c27b0', 'description' => 'Magical and supernatural elements'],
//             ['name' => 'Historical', 'color' => '#795548', 'description' => 'Set in historical periods'],
//             ['name' => 'Sci-Fi', 'color' => '#00bcd4', 'description' => 'Science fiction elements'],
//         ];

//         foreach ($genres as $genre) {
//             Genre::create($genre);
//         }

//         // Create Sample Dramas
//         $dramas = [
//             [
//                 'title' => 'Goblin: The Lonely and Great God',
//                 'synopsis' => 'A modern-day goblin seeks to end his immortal life and needs a human bride to do so.',
//                 'type' => 'drama',
//                 'episodes' => 16,
//                 'duration' => 90,
//                 'country' => 'South Korea',
//                 'release_year' => 2016,
//                 'status' => 'completed',
//                 'rating' => 8.9,
//                 'rating_count' => 1500,
//                 'is_featured' => true,
//                 'genres' => ['Fantasy', 'Romance', 'Drama']
//             ],
//             [
//                 'title' => 'Crash Landing on You',
//                 'synopsis' => 'A South Korean heiress accidentally crash-lands in North Korea and meets an army officer.',
//                 'type' => 'drama',
//                 'episodes' => 16,
//                 'duration' => 85,
//                 'country' => 'South Korea',
//                 'release_year' => 2019,
//                 'status' => 'completed',
//                 'rating' => 9.1,
//                 'rating_count' => 2000,
//                 'is_featured' => true,
//                 'genres' => ['Romance', 'Comedy', 'Drama']
//             ],
//             // Add more sample dramas...
//         ];

//         foreach ($dramas as $dramaData) {
//             $drama = Drama::create([
//                 'title' => $dramaData['title'],
//                 'slug' => \Str::slug($dramaData['title']),
//                 'synopsis' => $dramaData['synopsis'],
//                 'type' => $dramaData['type'],
//                 'episodes' => $dramaData['episodes'],
//                 'duration' => $dramaData['duration'],
//                 'country' => $dramaData['country'],
//                 'release_year' => $dramaData['release_year'],
//                 'status' => $dramaData['status'],
//                 'rating' => $dramaData['rating'],
//                 'rating_count' => $dramaData['rating_count'],
//                 'is_featured' => $dramaData['is_featured'],
//             ]);

//             // Attach genres
//             $genreIds = Genre::whereIn('name', $dramaData['genres'])->pluck('id');
//             $drama->genres()->attach($genreIds);
//         }

//         // Create Sample News
//         News::create([
//             'title' => 'Welcome to DramaVault!',
//             'slug' => 'welcome-to-dramavault',
//             'excerpt' => 'We are excited to launch DramaVault, your ultimate drama database.',
//             'content' => 'This is the full content of our welcome news article...',
//             'category' => 'general',
//             'is_published' => true,
//             'user_id' => $admin->id,
//             'published_at' => now(),
//         ]);

//         $this->command->info('DramaVault sample data seeded successfully!');
//     }
// }