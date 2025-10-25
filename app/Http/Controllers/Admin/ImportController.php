<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Drama;
use App\Models\Genre;
use App\Models\Cast;
use App\Services\OMDbService;
use App\Services\TraktService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{
    protected $omdb;
    protected $trakt;

    public function __construct(OMDbService $omdb, TraktService $trakt)
    {
        $this->omdb = $omdb;
        $this->trakt = $trakt;
    }

    /**
     * Show import form
     */
    public function index()
    {
        return view('admin.import.index');
    }

    /**
     * Search for content to import
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $source = $request->input('source', 'omdb'); // omdb or trakt

        if ($source === 'trakt') {
            $results = $this->trakt->search($query, 'show,movie', 10);
            
            // Transform Trakt results for display
            $transformed = collect($results)->map(function ($item) {
                $show = $item['show'] ?? $item['movie'];
                return [
                    'title' => $show['title'],
                    'year' => $show['year'] ?? 'N/A',
                    'type' => $item['type'],
                    'imdb_id' => $show['ids']['imdb'] ?? null,
                    'trakt_id' => $show['ids']['trakt'] ?? null,
                    'overview' => $show['overview'] ?? '',
                ];
            })->filter(fn($item) => $item['imdb_id'] !== null);

            return response()->json([
                'success' => true,
                'results' => $transformed,
                'source' => 'trakt'
            ]);
        } else {
            // OMDb search
            $results = $this->omdb->search($query);
            
            if (!$results || $results['Response'] === 'False') {
                return response()->json([
                    'success' => false,
                    'message' => 'No results found'
                ]);
            }

            $transformed = collect($results['Search'] ?? [])->map(function ($item) {
                return [
                    'title' => $item['Title'],
                    'year' => $item['Year'] ?? 'N/A',
                    'type' => $item['Type'],
                    'imdb_id' => $item['imdbID'],
                    'poster' => $item['Poster'] !== 'N/A' ? $item['Poster'] : null,
                ];
            });

            return response()->json([
                'success' => true,
                'results' => $transformed,
                'source' => 'omdb'
            ]);
        }
    }

    /**
     * Import drama by IMDb ID
     */
    public function import(Request $request)
    {
        $request->validate([
            'imdb_id' => 'required|string',
        ]);

        $imdbId = $request->input('imdb_id');

        // Check if already exists
        $existing = Drama::where('imdb_id', $imdbId)->first();
        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'This drama already exists in the database.',
                'drama_id' => $existing->id
            ]);
        }

        // Get data from OMDb
        $data = $this->omdb->importDrama($imdbId);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch data from OMDb API'
            ]);
        }

        DB::beginTransaction();
        try {
            // Create drama
            $drama = Drama::create([
                'title' => $data['title'],
                'slug' => \Illuminate\Support\Str::slug($data['title']),
                'synopsis' => $data['synopsis'],
                'release_year' => $data['release_year'],
                'country' => $data['country'],
                'type' => $data['type'],
                'episodes' => $data['episodes'],
                'duration' => $data['duration'],
                'avg_rating' => $data['rating'],
                'poster_path' => $data['poster_url'],
                'banner_path' => $data['backdrop_url'],
                'imdb_id' => $data['imdb_id'],
                'status' => 'completed',
            ]);

            // Attach genres
            if (!empty($data['genres'])) {
                foreach ($data['genres'] as $genreName) {
                    $genre = Genre::firstOrCreate(
                        ['name' => trim($genreName)],
                        ['slug' => \Illuminate\Support\Str::slug($genreName)]
                    );
                    $drama->genres()->attach($genre->id);
                }
            }

            // Create cast members
            if (!empty($data['actors'])) {
                foreach (array_slice($data['actors'], 0, 5) as $actorName) { // Limit to 5 actors
                    $cast = Cast::firstOrCreate(
                        ['name' => trim($actorName)],
                        [
                            'slug' => \Illuminate\Support\Str::slug($actorName),
                            'bio' => '',
                            'birth_date' => null,
                        ]
                    );
                    $drama->cast()->attach($cast->id, ['character_name' => 'Unknown', 'role_type' => 'Actor']);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Drama imported successfully!',
                'drama' => [
                    'id' => $drama->id,
                    'title' => $drama->title,
                    'year' => $drama->release_year,
                    'rating' => $drama->avg_rating,
                    'poster' => $drama->poster_url,
                    'url' => route('dramas.show', $drama),
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to import drama: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Import trending shows from Trakt
     */
    public function importTrending(Request $request)
    {
        $limit = $request->input('limit', 10);
        $type = $request->input('type', 'shows'); // shows or movies

        $results = $type === 'movies' 
            ? $this->trakt->getTrendingMovies($limit)
            : $this->trakt->getTrendingShows($limit);

        $imported = [];
        $skipped = [];
        $failed = [];

        foreach ($results as $item) {
            $show = $item['show'] ?? $item['movie'];
            $imdbId = $show['ids']['imdb'] ?? null;

            if (!$imdbId) {
                $skipped[] = $show['title'] . ' (No IMDb ID)';
                continue;
            }

            // Check if already exists
            if (Drama::where('imdb_id', $imdbId)->exists()) {
                $skipped[] = $show['title'] . ' (Already exists)';
                continue;
            }

            // Import using OMDb
            $data = $this->omdb->importDrama($imdbId);
            
            if (!$data) {
                $failed[] = $show['title'] . ' (OMDb fetch failed)';
                continue;
            }

            try {
                DB::beginTransaction();

                $drama = Drama::create([
                    'title' => $data['title'],
                    'slug' => \Illuminate\Support\Str::slug($data['title']),
                    'synopsis' => $data['synopsis'],
                    'release_year' => $data['release_year'],
                    'country' => $data['country'],
                    'type' => $data['type'],
                    'episodes' => $data['episodes'],
                    'duration' => $data['duration'],
                    'avg_rating' => $data['rating'],
                    'poster_path' => $data['poster_url'],
                    'banner_path' => $data['backdrop_url'],
                    'imdb_id' => $data['imdb_id'],
                    'status' => 'completed',
                ]);

                // Attach genres
                if (!empty($data['genres'])) {
                    foreach ($data['genres'] as $genreName) {
                        $genre = Genre::firstOrCreate(
                            ['name' => trim($genreName)],
                            ['slug' => \Illuminate\Support\Str::slug($genreName)]
                        );
                        $drama->genres()->attach($genre->id);
                    }
                }

                DB::commit();
                $imported[] = $drama->title;

            } catch (\Exception $e) {
                DB::rollBack();
                $failed[] = $show['title'] . ' (Database error)';
            }

            // Rate limiting: sleep for 200ms between requests (max 5 per second for free tier)
            usleep(200000);
        }

        return response()->json([
            'success' => true,
            'imported' => $imported,
            'skipped' => $skipped,
            'failed' => $failed,
            'summary' => [
                'imported_count' => count($imported),
                'skipped_count' => count($skipped),
                'failed_count' => count($failed),
            ]
        ]);
    }
}
