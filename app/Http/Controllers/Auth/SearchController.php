<?php

namespace App\Http\Controllers;

use App\Models\Drama;
use Illuminate\Http\Request;
use ElasticScoutDriverPlus\Builders\SearchRequestBuilder;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q');
        $filters = $request->only(['type', 'genre', 'country', 'year_from', 'year_to', 'rating_min']);

        if (!$query && empty(array_filter($filters))) {
            return redirect()->route('dramas.index');
        }

        $searchQuery = Drama::searchQuery($query);

        // Apply filters
        if (!empty($filters['type'])) {
            $searchQuery->where('type', $filters['type']);
        }

        if (!empty($filters['genre'])) {
            $searchQuery->whereIn('genres', [$filters['genre']]);
        }

        if (!empty($filters['country'])) {
            $searchQuery->where('country', $filters['country']);
        }

        if (!empty($filters['year_from'])) {
            $searchQuery->where('release_year', '>=', (int)$filters['year_from']);
        }

        if (!empty($filters['year_to'])) {
            $searchQuery->where('release_year', '<=', (int)$filters['year_to']);
        }

        if (!empty($filters['rating_min'])) {
            $searchQuery->where('rating', '>=', (float)$filters['rating_min']);
        }

        $dramas = $searchQuery->paginate(12);
        $dramas->appends($request->all());

        return view('search.results', compact('dramas', 'query', 'filters'));
    }

    public function autocomplete(Request $request)
    {
        $query = $request->get('q');

        if (!$query) {
            return response()->json([]);
        }

        $results = Drama::searchQuery($query)
            ->size(5)
            ->execute();

        $suggestions = $results->hits()->map(function ($hit) {
            return [
                'title' => $hit->document()->get('title'),
                'type' => $hit->document()->get('type'),
                'year' => $hit->document()->get('release_year'),
                'url' => route('dramas.show', \Str::slug($hit->document()->get('title'))),
            ];
        });

        return response()->json($suggestions);
    }
}