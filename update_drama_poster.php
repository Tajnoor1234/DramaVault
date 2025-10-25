<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$drama = App\Models\Drama::where('slug', 'goblin-1761414024')->first();

if ($drama) {
    echo "Current poster: " . ($drama->poster_path ?? 'NULL') . "\n\n";
    echo "Choose an option:\n";
    echo "1. Use a Goblin K-drama poster URL\n";
    echo "2. Use placeholder image\n";
    echo "3. Enter custom URL\n";
    echo "\nNote: You can also go to http://localhost:8000/admin/dramas/{$drama->slug}/edit to upload your own image\n\n";
    
    // For now, use a high-quality placeholder that looks like a drama poster
    $posterUrl = 'https://dummyimage.com/500x750/0066cc/ffffff&text=Goblin+(2016)';
    
    // Or use an actual image URL if you have one - just replace the line above with:
    // $posterUrl = 'YOUR_IMAGE_URL_HERE';
    
    $drama->poster_path = $posterUrl;
    $drama->save();
    
    echo "✓ Updated Goblin drama with poster URL\n";
    echo "Poster URL: " . $drama->poster_url . "\n";
    echo "\nRefresh the drama page to see the poster!\n";
} else {
    echo "Drama not found\n";
}
