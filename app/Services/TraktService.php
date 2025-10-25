<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class TraktService
{
    protected $clientId;
    protected $clientSecret;
    protected $baseUrl = 'https://api.trakt.tv';

    public function __construct()
    {
        $this->clientId = config('services.trakt.client_id');
        $this->clientSecret = config('services.trakt.client_secret');
    }

    /**
     * Get common headers for Trakt API
     */
    protected function getHeaders()
    {
        return [
            'Content-Type' => 'application/json',
            'trakt-api-version' => '2',
            'trakt-api-key' => $this->clientId,
        ];
    }

    /**
     * Get trending shows
     */
    public function getTrendingShows($limit = 10, $page = 1)
    {
        $cacheKey = "trakt_trending_shows_{$limit}_{$page}";
        
        return Cache::remember($cacheKey, now()->addHours(6), function () use ($limit, $page) {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->baseUrl}/shows/trending", [
                    'limit' => $limit,
                    'page' => $page,
                    'extended' => 'full',
                ]);

            return $response->successful() ? $response->json() : [];
        });
    }

    /**
     * Get trending movies
     */
    public function getTrendingMovies($limit = 10, $page = 1)
    {
        $cacheKey = "trakt_trending_movies_{$limit}_{$page}";
        
        return Cache::remember($cacheKey, now()->addHours(6), function () use ($limit, $page) {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->baseUrl}/movies/trending", [
                    'limit' => $limit,
                    'page' => $page,
                    'extended' => 'full',
                ]);

            return $response->successful() ? $response->json() : [];
        });
    }

    /**
     * Get popular shows
     */
    public function getPopularShows($limit = 10, $page = 1)
    {
        $cacheKey = "trakt_popular_shows_{$limit}_{$page}";
        
        return Cache::remember($cacheKey, now()->addHours(12), function () use ($limit, $page) {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->baseUrl}/shows/popular", [
                    'limit' => $limit,
                    'page' => $page,
                    'extended' => 'full',
                ]);

            return $response->successful() ? $response->json() : [];
        });
    }

    /**
     * Get popular movies
     */
    public function getPopularMovies($limit = 10, $page = 1)
    {
        $cacheKey = "trakt_popular_movies_{$limit}_{$page}";
        
        return Cache::remember($cacheKey, now()->addHours(12), function () use ($limit, $page) {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->baseUrl}/movies/popular", [
                    'limit' => $limit,
                    'page' => $page,
                    'extended' => 'full',
                ]);

            return $response->successful() ? $response->json() : [];
        });
    }

    /**
     * Get most anticipated shows
     */
    public function getAnticipatedShows($limit = 10, $page = 1)
    {
        $cacheKey = "trakt_anticipated_shows_{$limit}_{$page}";
        
        return Cache::remember($cacheKey, now()->addHours(24), function () use ($limit, $page) {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->baseUrl}/shows/anticipated", [
                    'limit' => $limit,
                    'page' => $page,
                    'extended' => 'full',
                ]);

            return $response->successful() ? $response->json() : [];
        });
    }

    /**
     * Search for shows and movies
     */
    public function search($query, $type = 'show,movie', $limit = 10, $page = 1)
    {
        $cacheKey = "trakt_search_{$query}_{$type}_{$limit}_{$page}";
        
        return Cache::remember($cacheKey, now()->addHours(24), function () use ($query, $type, $limit, $page) {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->baseUrl}/search/{$type}", [
                    'query' => $query,
                    'limit' => $limit,
                    'page' => $page,
                    'extended' => 'full',
                ]);

            return $response->successful() ? $response->json() : [];
        });
    }

    /**
     * Get show details by Trakt ID or slug
     */
    public function getShowDetails($id, $extended = 'full')
    {
        $cacheKey = "trakt_show_{$id}_{$extended}";
        
        return Cache::remember($cacheKey, now()->addDays(7), function () use ($id, $extended) {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->baseUrl}/shows/{$id}", [
                    'extended' => $extended,
                ]);

            return $response->successful() ? $response->json() : null;
        });
    }

    /**
     * Get movie details by Trakt ID or slug
     */
    public function getMovieDetails($id, $extended = 'full')
    {
        $cacheKey = "trakt_movie_{$id}_{$extended}";
        
        return Cache::remember($cacheKey, now()->addDays(7), function () use ($id, $extended) {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->baseUrl}/movies/{$id}", [
                    'extended' => $extended,
                ]);

            return $response->successful() ? $response->json() : null;
        });
    }

    /**
     * Get show ratings
     */
    public function getShowRatings($id)
    {
        $cacheKey = "trakt_show_ratings_{$id}";
        
        return Cache::remember($cacheKey, now()->addDays(1), function () use ($id) {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->baseUrl}/shows/{$id}/ratings");

            return $response->successful() ? $response->json() : null;
        });
    }

    /**
     * Get related shows
     */
    public function getRelatedShows($id, $limit = 10)
    {
        $cacheKey = "trakt_related_shows_{$id}_{$limit}";
        
        return Cache::remember($cacheKey, now()->addDays(7), function () use ($id, $limit) {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->baseUrl}/shows/{$id}/related", [
                    'limit' => $limit,
                    'extended' => 'full',
                ]);

            return $response->successful() ? $response->json() : [];
        });
    }

    /**
     * Get show statistics
     */
    public function getShowStats($id)
    {
        $cacheKey = "trakt_show_stats_{$id}";
        
        return Cache::remember($cacheKey, now()->addHours(6), function () use ($id) {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->baseUrl}/shows/{$id}/stats");

            return $response->successful() ? $response->json() : null;
        });
    }

    /**
     * Import show from Trakt
     */
    public function importShow($id)
    {
        $show = $this->getShowDetails($id);

        if (!$show) {
            return null;
        }

        $ratings = $this->getShowRatings($id);

        return [
            'title' => $show['title'] ?? 'Unknown',
            'original_title' => $show['title'] ?? null,
            'synopsis' => $show['overview'] ?? '',
            'release_year' => isset($show['year']) ? (int)$show['year'] : null,
            'country' => $show['country'] ?? 'Unknown',
            'type' => 'series',
            'status' => $show['status'] ?? 'Unknown',
            'episodes' => $show['aired_episodes'] ?? 0,
            'duration' => $show['runtime'] ?? null,
            'rating' => $ratings['rating'] ?? 0,
            'poster_url' => null, // Trakt doesn't provide images, use OMDb
            'backdrop_url' => null,
            'trakt_id' => $show['ids']['trakt'] ?? null,
            'imdb_id' => $show['ids']['imdb'] ?? null,
            'tmdb_id' => $show['ids']['tmdb'] ?? null,
            'tvdb_id' => $show['ids']['tvdb'] ?? null,
            'slug' => $show['ids']['slug'] ?? null,
            'genres' => $show['genres'] ?? [],
            'language' => $show['language'] ?? 'en',
            'network' => $show['network'] ?? '',
            'certification' => $show['certification'] ?? '',
            'trakt_rating' => $ratings['rating'] ?? 0,
            'trakt_votes' => $ratings['votes'] ?? 0,
        ];
    }

    /**
     * Get most watched shows this week
     */
    public function getWeeklyWatched($limit = 10)
    {
        $cacheKey = "trakt_weekly_watched_{$limit}";
        
        return Cache::remember($cacheKey, now()->addHours(24), function () use ($limit) {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->baseUrl}/shows/watched/weekly", [
                    'limit' => $limit,
                    'extended' => 'full',
                ]);

            return $response->successful() ? $response->json() : [];
        });
    }
}
