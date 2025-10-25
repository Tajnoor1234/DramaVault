<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$drama = App\Models\Drama::where('slug', 'goblin-1761414024')->first();

if ($drama) {
    // Restore the original poster path
    $drama->poster_path = 'dramas/posters/I2a89kcfmmUevQtZpqvp8HIx0H251DjmPqMPUdwX.jpg';
    $drama->save();
    
    echo "✓ Restored original poster path\n";
    echo "Poster Path: " . $drama->poster_path . "\n";
    echo "Poster URL: " . $drama->poster_url . "\n";
    
    $fullPath = storage_path('app/public/' . $drama->poster_path);
    echo "\nFile exists: " . (file_exists($fullPath) ? 'YES' : 'NO') . "\n";
    echo "Full path: " . $fullPath . "\n";
} else {
    echo "Drama not found\n";
}
