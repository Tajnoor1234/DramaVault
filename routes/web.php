<?php

use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\APITestController;
use App\Http\Controllers\CastController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DramaController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dramas', [DramaController::class, 'index'])->name('dramas.index');
Route::get('/dramas/{drama}', [DramaController::class, 'show'])->name('dramas.show');

// Test NewsData.io
Route::get('/test-newsapi', function() {
    $response = Http::get('https://newsdata.io/api/1/news', [
        'apikey' => config('services.newsdata.api_key'),
        'category' => 'entertainment',
        'language' => 'en',
        'size' => 5
    ]);
    return response()->json([
        'status' => $response->status(),
        'data' => $response->json()
    ]);
});

Route::get('/casts', [CastController::class, 'index'])->name('casts.index');
Route::get('/casts/{cast}', [CastController::class, 'show'])->name('casts.show');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');

// API Test Routes (Development Only)
Route::prefix('api-test')->name('api-test.')->group(function () {
    Route::get('/omdb', [APITestController::class, 'testOMDb'])->name('omdb');
    Route::get('/trakt', [APITestController::class, 'testTrakt'])->name('trakt');
    Route::get('/news', [APITestController::class, 'testNews'])->name('news');
    Route::get('/demo', [APITestController::class, 'demo'])->name('demo');
});

// Authentication Routes
Auth::routes();

// Authenticated User Routes
Route::middleware(['auth'])->group(function () {
    // User Profile
    Route::get('/profile', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'update'])->name('profile.update');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    
    // Follow System
    Route::post('/users/{user}/follow', [UserController::class, 'follow'])->name('users.follow');
    Route::post('/users/{user}/unfollow', [UserController::class, 'unfollow'])->name('users.unfollow');
    
    // Ratings
    Route::post('/dramas/{drama}/ratings', [RatingController::class, 'store'])->name('ratings.store');
    Route::put('/ratings/{rating}', [RatingController::class, 'update'])->name('ratings.update');
    Route::delete('/ratings/{rating}', [RatingController::class, 'destroy'])->name('ratings.destroy');
    
    // Comments
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{comment}/like', [CommentController::class, 'like'])->name('comments.like');
    Route::post('/comments/{comment}/dislike', [CommentController::class, 'dislike'])->name('comments.dislike');
    Route::post('/comments/user-likes', [CommentController::class, 'getUserLikes'])->name('comments.user-likes');
    
    // Watchlist
    Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
    Route::post('/dramas/{drama}/watchlist', [WatchlistController::class, 'store'])->name('watchlist.store');
    Route::delete('/dramas/{drama}/watchlist', [WatchlistController::class, 'removeByDrama'])->name('watchlist.remove');
    Route::put('/watchlist/{watchlist}', [WatchlistController::class, 'update'])->name('watchlist.update');
    Route::delete('/watchlist/{watchlist}', [WatchlistController::class, 'destroy'])->name('watchlist.destroy');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::put('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.role');
    Route::post('/users/{user}/ban', [AdminController::class, 'banUser'])->name('users.ban');
    Route::post('/users/{user}/unban', [AdminController::class, 'unbanUser'])->name('users.unban');
    
    // Import Routes
    Route::get('/import', [ImportController::class, 'index'])->name('import.index');
    Route::get('/import/search', [ImportController::class, 'search'])->name('import.search');
    Route::post('/import', [ImportController::class, 'import'])->name('import.store');
    Route::post('/import/trending', [ImportController::class, 'importTrending'])->name('import.trending');
    
    // Drama Management
    Route::get('/dramas', [DramaController::class, 'adminIndex'])->name('dramas.index');
    Route::get('/dramas/create', [DramaController::class, 'create'])->name('dramas.create');
    Route::post('/dramas', [DramaController::class, 'store'])->name('dramas.store');
    Route::get('/dramas/{drama}/edit', [DramaController::class, 'edit'])->name('dramas.edit');
    Route::put('/dramas/{drama}', [DramaController::class, 'update'])->name('dramas.update');
    Route::delete('/dramas/{drama}', [DramaController::class, 'destroy'])->name('dramas.destroy');
    
    // Cast Management
    Route::get('/casts/create', [CastController::class, 'create'])->name('casts.create');
    Route::post('/casts', [CastController::class, 'store'])->name('casts.store');
    Route::get('/casts/{cast}/edit', [CastController::class, 'edit'])->name('casts.edit');
    Route::put('/casts/{cast}', [CastController::class, 'update'])->name('casts.update');
    Route::delete('/casts/{cast}', [CastController::class, 'destroy'])->name('casts.destroy');
    
    // News Management
    Route::get('/news', [AdminController::class, 'news'])->name('news.index');
    Route::get('/news/create', [NewsController::class, 'create'])->name('news.create');
    Route::post('/news', [NewsController::class, 'store'])->name('news.store');
    Route::get('/news/{news}/edit', [NewsController::class, 'edit'])->name('news.edit');
    Route::put('/news/{news}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('/news/{news}', [NewsController::class, 'destroy'])->name('news.destroy');
    
    // News API Import
    Route::get('/news/import/api', [NewsController::class, 'fetchAPINews'])->name('news.fetch-api');
    Route::post('/news/import/api', [NewsController::class, 'importAPINews'])->name('news.import-api');
});