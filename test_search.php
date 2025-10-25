<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Drama;

echo "Testing Search Functionality\n";
echo str_repeat("=", 50) . "\n\n";

// Test 1: All dramas
echo "Test 1: Total dramas in database\n";
$total = Drama::count();
echo "Total: {$total}\n\n";

// Test 2: Search for 'bon'
echo "Test 2: Searching for 'bon'\n";
$results = Drama::where('title', 'like', '%bon%')->get();
echo "Found: {$results->count()} results\n";
foreach ($results as $drama) {
    echo "  - {$drama->title}\n";
}
echo "\n";

// Test 3: List first 10 dramas
echo "Test 3: First 10 dramas\n";
$dramas = Drama::take(10)->get();
foreach ($dramas as $drama) {
    echo "  - {$drama->title} ({$drama->type}, {$drama->country})\n";
}
echo "\n";

// Test 4: Test filters
echo "Test 4: Testing filters\n";
echo "  Series: " . Drama::where('type', 'series')->count() . "\n";
echo "  Movies: " . Drama::where('type', 'movie')->count() . "\n";
echo "  South Korea: " . Drama::where('country', 'South Korea')->count() . "\n";
echo "\n";

echo str_repeat("=", 50) . "\n";
echo "Tests completed!\n";
