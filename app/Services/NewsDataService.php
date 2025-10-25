<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class NewsDataService
{
    protected $apiKey;
    protected $baseUrl = 'https://newsdata.io/api/1';

    public function __construct()
    {
        $this->apiKey = config('services.newsdata.api_key');
    }

    /**
     * Get latest news by category
     */
    public function getLatestNews($category = null, $query = null)
    {
        $cacheKey = "newsdata_latest_{$category}_{$query}";
        
        return Cache::remember($cacheKey, now()->addHours(6), function () use ($category, $query) {
            try {
                $params = [
                    'apikey' => $this->apiKey,
                    'language' => 'en',
                    'size' => 10,
                ];

                if ($category) {
                    $params['category'] = $category;
                }

                if ($query) {
                    $params['q'] = $query;
                }

                $response = Http::timeout(30)->get("{$this->baseUrl}/news", $params);

                if ($response->successful()) {
                    $data = $response->json();
                    Log::info('NewsData Response', [
                        'category' => $category,
                        'query' => $query,
                        'status' => $data['status'] ?? 'unknown',
                        'totalResults' => $data['totalResults'] ?? 0,
                    ]);
                    return $data;
                } else {
                    Log::error('NewsData Error', [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    return null;
                }
            } catch (\Exception $e) {
                Log::error('NewsData Exception', [
                    'message' => $e->getMessage()
                ]);
                return null;
            }
        });
    }

    /**
     * Get entertainment news
     */
    public function getEntertainmentNews()
    {
        return $this->getLatestNews('entertainment');
    }

    /**
     * Get drama/movie specific news
     */
    public function getDramaNews()
    {
        return $this->getLatestNews('entertainment', 'drama OR movie OR series OR kdrama');
    }

    /**
     * Get sports news (might have entertainment sports news)
     */
    public function getSportsNews()
    {
        return $this->getLatestNews('sports');
    }

    /**
     * Search news by keyword
     */
    public function searchNews($keyword)
    {
        return $this->getLatestNews(null, $keyword);
    }
}
