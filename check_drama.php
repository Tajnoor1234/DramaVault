<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$drama = App\Models\Drama::where('title', 'LIKE', '%Goblin%')->first();

if ($drama) {
    echo "Title: " . $drama->title . "\n";
    echo "Slug: " . $drama->slug . "\n";
    echo "Poster Path: " . ($drama->poster_path ?? 'NULL') . "\n";
    echo "Poster URL: " . $drama->poster_url . "\n";
    echo "\n";
    
    // Check if file exists
    if ($drama->poster_path) {
        $fullPath = public_path('storage/' . $drama->poster_path);
        echo "Full Path: " . $fullPath . "\n";
        echo "File Exists: " . (file_exists($fullPath) ? 'YES' : 'NO') . "\n";
        
        // Check storage path too
        $storagePath = storage_path('app/public/' . $drama->poster_path);
        echo "Storage Path: " . $storagePath . "\n";
        echo "Storage File Exists: " . (file_exists($storagePath) ? 'YES' : 'NO') . "\n";
    }
} else {
    echo "Drama not found\n";
}
