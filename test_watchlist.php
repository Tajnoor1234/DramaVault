<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Watchlist;
use App\Models\Drama;
use App\Models\User;

echo "Testing Watchlist Functionality\n";
echo str_repeat("=", 50) . "\n\n";

// Get a test user
$user = User::first();
if (!$user) {
    echo "❌ No users found in database\n";
    exit;
}

echo "✓ Test User: {$user->name} (ID: {$user->id})\n";

// Get a test drama
$drama = Drama::first();
if (!$drama) {
    echo "❌ No dramas found in database\n";
    exit;
}

echo "✓ Test Drama: {$drama->title} (ID: {$drama->id})\n\n";

// Test 1: Create watchlist entry
echo "Test 1: Adding drama to watchlist...\n";
$watchlist = Watchlist::updateOrCreate(
    ['user_id' => $user->id, 'drama_id' => $drama->id],
    ['status' => 'watching']
);
echo "✓ Created/Updated watchlist entry (ID: {$watchlist->id})\n";
echo "  Status: {$watchlist->status}\n\n";

// Test 2: Update watchlist status
echo "Test 2: Updating watchlist status to 'completed'...\n";
$watchlist->update(['status' => 'completed']);
echo "✓ Updated status to: {$watchlist->status}\n\n";

// Test 3: Retrieve watchlist by user
echo "Test 3: Retrieving user's watchlist...\n";
$userWatchlist = Watchlist::where('user_id', $user->id)->count();
echo "✓ User has {$userWatchlist} item(s) in watchlist\n\n";

// Test 4: Retrieve by drama
echo "Test 4: Finding watchlist entry by drama ID...\n";
$found = Watchlist::where('user_id', $user->id)
    ->where('drama_id', $drama->id)
    ->first();
if ($found) {
    echo "✓ Found watchlist entry\n";
    echo "  Drama: {$found->drama->title}\n";
    echo "  Status: {$found->status}\n\n";
} else {
    echo "❌ Watchlist entry not found\n\n";
}

// Test 5: Delete watchlist entry
echo "Test 5: Removing drama from watchlist...\n";
$watchlist->delete();
$check = Watchlist::where('user_id', $user->id)
    ->where('drama_id', $drama->id)
    ->exists();
    
if (!$check) {
    echo "✓ Successfully deleted watchlist entry\n\n";
} else {
    echo "❌ Failed to delete watchlist entry\n\n";
}

echo str_repeat("=", 50) . "\n";
echo "✓ All tests completed!\n";
echo "\nYou can now:\n";
echo "1. Go to the drama page: http://localhost:8000/dramas/{$drama->slug}\n";
echo "2. Select a status from the dropdown\n";
echo "3. Click 'Add to Watchlist' or 'Update'\n";
echo "4. View your watchlist: http://localhost:8000/watchlist\n";
echo "5. Click 'Remove' to remove from watchlist\n";
