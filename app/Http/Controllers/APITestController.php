<?php

namespace App\Http\Controllers;

use App\Services\OMDbService;
use App\Services\TraktService;
use App\Services\NewsAPIService;
use Illuminate\Http\Request;

class APITestController extends Controller
{
    /**
     * Test OMDb API integration
     */
    public function testOMDb(OMDbService $omdb)
    {
        $results = [];

        // Test 1: Search by title
        $results['search'] = $omdb->search('Breaking Bad', 'series');

        // Test 2: Get details by IMDb ID
        $results['byImdbId'] = $omdb->getByImdbId('tt0903747'); // Breaking Bad

        // Test 3: Get details by title
        $results['byTitle'] = $omdb->getByTitle('The Dark Knight', 'movie', 2008);

        // Test 4: Import drama
        $results['import'] = $omdb->importDrama('tt0903747');

        return view('api-test.omdb', compact('results'));
    }

    /**
     * Test Trakt API integration
     */
    public function testTrakt(TraktService $trakt)
    {
        $results = [];

        // Test 1: Trending shows
        $results['trendingShows'] = $trakt->getTrendingShows(5);

        // Test 2: Trending movies
        $results['trendingMovies'] = $trakt->getTrendingMovies(5);

        // Test 3: Popular shows
        $results['popularShows'] = $trakt->getPopularShows(5);

        // Test 4: Search
        $results['search'] = $trakt->search('Breaking Bad', 'show', 5);

        // Test 5: Show details
        $results['showDetails'] = $trakt->getShowDetails('breaking-bad');

        // Test 6: Weekly watched
        $results['weeklyWatched'] = $trakt->getWeeklyWatched(5);

        return view('api-test.trakt', compact('results'));
    }

    /**
     * Test NewsAPI integration
     */
    public function testNews(NewsAPIService $news)
    {
        $results = [];

        // Test 1: Entertainment news
        $results['entertainment'] = $news->getEntertainmentNews();

        // Test 2: Drama news
        $results['drama'] = $news->getDramaNews();

        // Test 3: Search news
        $results['search'] = $news->searchNews('Netflix', 'publishedAt');

        return view('api-test.news', compact('results'));
    }

    /**
     * Combined API demo - shows real-world usage
     */
    public function demo(OMDbService $omdb, TraktService $trakt, NewsAPIService $news)
    {
        $data = [];

        // Get trending shows from Trakt
        $trendingShows = $trakt->getTrendingShows(5);
        
        // For each trending show, get detailed info from OMDb using IMDb ID
        $enrichedShows = [];
        foreach ($trendingShows as $item) {
            $show = $item['show'];
            $imdbId = $show['ids']['imdb'] ?? null;
            
            if ($imdbId) {
                $omdbData = $omdb->getByImdbId($imdbId);
                $enrichedShows[] = [
                    'trakt' => $show,
                    'omdb' => $omdbData,
                    'combined' => [
                        'title' => $show['title'],
                        'year' => $show['year'],
                        'rating' => $omdbData['imdbRating'] ?? 'N/A',
                        'poster' => $omdbData['Poster'] ?? null,
                        'plot' => $omdbData['Plot'] ?? $show['overview'],
                        'watchers' => $item['watchers'] ?? 0,
                    ]
                ];
            }
        }
        
        $data['enrichedShows'] = $enrichedShows;
        
        // Get entertainment news
        $newsResults = $news->getEntertainmentNews();
        $data['news'] = isset($newsResults['articles']) ? array_slice($newsResults['articles'], 0, 3) : [];
        
        // Get popular movies from Trakt
        $data['popularMovies'] = $trakt->getPopularMovies(5);

        return view('api-test.demo', compact('data'));
    }
}
