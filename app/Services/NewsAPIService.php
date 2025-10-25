<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class NewsAPIService
{
    protected $apiKey;
    protected $baseUrl = 'https://newsapi.org/v2';

    public function __construct()
    {
        $this->apiKey = config('services.newsapi.api_key');
    }

    /**
     * Get top headlines
     */
    public function getTopHeadlines($category = 'entertainment', $country = 'us')
    {
        $cacheKey = "news_headlines_{$category}_{$country}";
        
        return Cache::remember($cacheKey, now()->addHours(6), function () use ($category, $country) {
            $response = Http::get("{$this->baseUrl}/top-headlines", [
                'apiKey' => $this->apiKey,
                'category' => $category,
                'country' => $country,
                'pageSize' => 20,
            ]);

            return $response->successful() ? $response->json() : null;
        });
    }

    /**
     * Search news articles
     */
    public function searchNews($query, $sortBy = 'publishedAt')
    {
        $cacheKey = "news_search_{$query}_{$sortBy}";
        
        return Cache::remember($cacheKey, now()->addHours(12), function () use ($query, $sortBy) {
            try {
                $response = Http::timeout(30)->get("{$this->baseUrl}/everything", [
                    'apiKey' => $this->apiKey,
                    'q' => $query,
                    'sortBy' => $sortBy,
                    'language' => 'en',
                    'pageSize' => 20,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    \Log::info('NewsAPI Response', [
                        'query' => $query,
                        'status' => $data['status'] ?? 'unknown',
                        'totalResults' => $data['totalResults'] ?? 0,
                        'message' => $data['message'] ?? null
                    ]);
                    return $data;
                } else {
                    \Log::error('NewsAPI Error', [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    return null;
                }
            } catch (\Exception $e) {
                \Log::error('NewsAPI Exception', [
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
        return $this->getTopHeadlines('entertainment', 'us');
    }

    /**
     * Get drama-specific news (uses entertainment category for free tier)
     */
    public function getDramaNews()
    {
        // Free tier: Use top headlines from multiple countries to get variety
        $usNews = $this->getTopHeadlines('entertainment', 'us');
        
        // Return US news for now (free tier limitation)
        return $usNews;
    }

    /**
     * Get technology news (might have streaming/entertainment tech news)
     */
    public function getTechnologyNews()
    {
        return $this->getTopHeadlines('technology', 'us');
    }
}
