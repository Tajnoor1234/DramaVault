<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class OMDbService
{
    protected $apiKey;
    protected $baseUrl = 'https://www.omdbapi.com/';

    public function __construct()
    {
        $this->apiKey = config('services.omdb.api_key');
    }

    /**
     * Search for movies/series by title
     */
    public function search($query, $type = null, $year = null, $page = 1)
    {
        $cacheKey = "omdb_search_{$query}_{$type}_{$year}_{$page}";
        
        return Cache::remember($cacheKey, now()->addHours(24), function () use ($query, $type, $year, $page) {
            $params = [
                'apikey' => $this->apiKey,
                's' => $query,
                'page' => $page,
            ];

            if ($type) {
                $params['type'] = $type; // movie, series, episode
            }

            if ($year) {
                $params['y'] = $year;
            }

            $response = Http::get($this->baseUrl, $params);

            return $response->successful() ? $response->json() : null;
        });
    }

    /**
     * Get details by IMDb ID
     */
    public function getByImdbId($imdbId, $plot = 'full')
    {
        $cacheKey = "omdb_imdb_{$imdbId}_{$plot}";
        
        return Cache::remember($cacheKey, now()->addDays(30), function () use ($imdbId, $plot) {
            $response = Http::get($this->baseUrl, [
                'apikey' => $this->apiKey,
                'i' => $imdbId,
                'plot' => $plot, // short or full
            ]);

            return $response->successful() ? $response->json() : null;
        });
    }

    /**
     * Get details by title
     */
    public function getByTitle($title, $type = null, $year = null, $plot = 'full')
    {
        $cacheKey = "omdb_title_{$title}_{$type}_{$year}_{$plot}";
        
        return Cache::remember($cacheKey, now()->addDays(7), function () use ($title, $type, $year, $plot) {
            $params = [
                'apikey' => $this->apiKey,
                't' => $title,
                'plot' => $plot,
            ];

            if ($type) {
                $params['type'] = $type;
            }

            if ($year) {
                $params['y'] = $year;
            }

            $response = Http::get($this->baseUrl, $params);

            return $response->successful() ? $response->json() : null;
        });
    }

    /**
     * Import drama from OMDb
     */
    public function importDrama($imdbId)
    {
        $data = $this->getByImdbId($imdbId);

        if (!$data || $data['Response'] === 'False') {
            return null;
        }

        // Parse ratings
        $ratings = [];
        if (isset($data['Ratings'])) {
            foreach ($data['Ratings'] as $rating) {
                $ratings[$rating['Source']] = $rating['Value'];
            }
        }

        return [
            'title' => $data['Title'] ?? 'Unknown',
            'original_title' => $data['Title'] ?? null,
            'synopsis' => $data['Plot'] ?? '',
            'release_year' => isset($data['Year']) ? (int)substr($data['Year'], 0, 4) : null,
            'country' => $data['Country'] ?? 'Unknown',
            'type' => $this->mapType($data['Type'] ?? 'movie'),
            'episodes' => $data['Type'] === 'series' ? ($data['totalSeasons'] ?? 1) * 10 : 1,
            'duration' => $this->parseDuration($data['Runtime'] ?? ''),
            'rating' => $this->parseRating($data['imdbRating'] ?? '0'),
            'poster_url' => $data['Poster'] !== 'N/A' ? $data['Poster'] : asset('images/default-poster.png'),
            'backdrop_url' => $data['Poster'] !== 'N/A' ? $data['Poster'] : asset('images/default-poster.png'),
            'imdb_id' => $imdbId,
            'imdb_rating' => $data['imdbRating'] ?? 'N/A',
            'imdb_votes' => $data['imdbVotes'] ?? '0',
            'genres' => isset($data['Genre']) ? explode(', ', $data['Genre']) : [],
            'director' => $data['Director'] ?? '',
            'writer' => $data['Writer'] ?? '',
            'actors' => isset($data['Actors']) ? explode(', ', $data['Actors']) : [],
            'language' => $data['Language'] ?? '',
            'awards' => $data['Awards'] ?? '',
            'metascore' => $data['Metascore'] ?? null,
            'box_office' => $data['BoxOffice'] ?? null,
            'ratings' => $ratings,
        ];
    }

    /**
     * Map OMDb type to our type
     */
    protected function mapType($type)
    {
        $typeMap = [
            'movie' => 'movie',
            'series' => 'series',
            'episode' => 'series',
        ];

        return $typeMap[$type] ?? 'drama';
    }

    /**
     * Parse duration from OMDb format (e.g., "120 min")
     */
    protected function parseDuration($runtime)
    {
        if (empty($runtime) || $runtime === 'N/A') {
            return null;
        }

        preg_match('/(\d+)/', $runtime, $matches);
        return isset($matches[1]) ? (int)$matches[1] : null;
    }

    /**
     * Parse rating to float
     */
    protected function parseRating($rating)
    {
        if ($rating === 'N/A' || empty($rating)) {
            return 0;
        }

        return (float)$rating;
    }
}
