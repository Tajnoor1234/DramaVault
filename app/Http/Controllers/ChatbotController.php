<?php

namespace App\Http\Controllers;

use App\Models\Drama;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    private $apiKey;
    private $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        $this->apiUrl = config('services.gemini.endpoint');
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        try {
            // Get API key - try both config and env
            $apiKey = config('services.gemini.api_key') ?: env('GEMINI_API_KEY');
            
            if (empty($apiKey)) {
                Log::error('Gemini API key is not configured');
                return response()->json([
                    'success' => false,
                    'message' => 'AI service is not configured. Please contact administrator.',
                ], 500);
            }
            
            $userMessage = $request->input('message');
            
            // Get context about available dramas/movies
            $context = $this->buildContext();
            
            // Build prompt for Gemini
            $prompt = $this->buildPrompt($userMessage, $context);
            
            // Build the full URL with API key
            $url = $this->apiUrl . '?key=' . $apiKey;
            
            Log::info('Calling Gemini API', ['url_length' => strlen($url), 'has_key' => !empty($apiKey)]);
            
            // Call Gemini API
            $response = Http::timeout(30)->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 500,
                ]
            ]);

            if (!$response->successful()) {
                Log::error('Gemini API Error: ' . $response->body());
                throw new \Exception('Failed to get response from AI');
            }

            $data = $response->json();
            $aiResponse = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Sorry, I could not generate a response.';
            
            // Extract drama recommendations if any
            $recommendations = $this->extractRecommendations($aiResponse);

            return response()->json([
                'success' => true,
                'message' => $aiResponse,
                'recommendations' => $recommendations,
            ]);

        } catch (\Exception $e) {
            Log::error('Chatbot Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Sorry, I encountered an error. Please try again or rephrase your question.',
            ], 500);
        }
    }

    private function buildContext()
    {
        // Get available dramas/movies statistics
        $totalDramas = Drama::count();
        $genres = Genre::withCount('dramas')->get()->map(function($genre) {
            return $genre->name . ' (' . $genre->dramas_count . ')';
        })->take(10)->implode(', ');

        // Get some popular dramas
        $popular = Drama::with('genres')
            ->withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->take(10)
            ->get()
            ->map(function($drama) {
                return [
                    'title' => $drama->title,
                    'type' => $drama->type,
                    'rating' => round($drama->ratings_avg_rating ?? 0, 1),
                    'genres' => $drama->genres->pluck('name')->implode(', '),
                    'year' => $drama->release_year,
                ];
            });

        // Get recent additions
        $recent = Drama::with('genres')
            ->latest()
            ->take(5)
            ->get()
            ->map(function($drama) {
                return [
                    'title' => $drama->title,
                    'type' => $drama->type,
                    'genres' => $drama->genres->pluck('name')->implode(', '),
                ];
            });

        return [
            'total' => $totalDramas,
            'genres' => $genres,
            'popular' => $popular,
            'recent' => $recent,
        ];
    }

    private function buildPrompt($userMessage, $context)
    {
        $prompt = "You are a helpful AI assistant for DramaVault, a platform for Korean dramas, movies, and series.\n\n";
        $prompt .= "CONTEXT ABOUT OUR COLLECTION:\n";
        $prompt .= "- Total items: {$context['total']}\n";
        $prompt .= "- Available genres: {$context['genres']}\n\n";
        
        if ($context['popular']->isNotEmpty()) {
            $prompt .= "TOP RATED CONTENT:\n";
            foreach ($context['popular'] as $item) {
                $prompt .= "- {$item['title']} ({$item['type']}, {$item['year']}) - Rating: {$item['rating']}/10 - Genres: {$item['genres']}\n";
            }
            $prompt .= "\n";
        }

        if ($context['recent']->isNotEmpty()) {
            $prompt .= "RECENTLY ADDED:\n";
            foreach ($context['recent'] as $item) {
                $prompt .= "- {$item['title']} ({$item['type']}) - Genres: {$item['genres']}\n";
            }
            $prompt .= "\n";
        }

        $prompt .= "USER QUESTION: {$userMessage}\n\n";
        $prompt .= "INSTRUCTIONS:\n";
        $prompt .= "1. Answer helpfully based on the available content\n";
        $prompt .= "2. When recommending, ONLY suggest from the items listed above\n";
        $prompt .= "3. Be conversational and friendly\n";
        $prompt .= "4. If asking about content we don't have, politely mention it\n";
        $prompt .= "5. Keep responses concise (2-3 sentences for simple questions, more for recommendations)\n";
        $prompt .= "6. When recommending, mention the title in quotes like \"Title Here\"\n\n";
        $prompt .= "YOUR RESPONSE:";

        return $prompt;
    }

    private function extractRecommendations($aiResponse)
    {
        // Extract drama titles mentioned in quotes
        preg_match_all('/"([^"]+)"/', $aiResponse, $matches);
        
        if (empty($matches[1])) {
            return [];
        }

        $titles = $matches[1];
        $recommendations = [];

        foreach ($titles as $title) {
            // Try to find matching drama
            $drama = Drama::where('title', 'LIKE', '%' . $title . '%')
                ->with('genres')
                ->withAvg('ratings', 'rating')
                ->first();

            if ($drama) {
                $recommendations[] = [
                    'id' => $drama->id,
                    'title' => $drama->title,
                    'slug' => $drama->slug,
                    'type' => $drama->type,
                    'poster' => $drama->poster_url,
                    'rating' => round($drama->ratings_avg_rating ?? 0, 1),
                    'url' => route('dramas.show', $drama),
                ];
            }
        }

        return $recommendations;
    }

    public function suggestions()
    {
        // Return common question suggestions
        return response()->json([
            'suggestions' => [
                'What are the top-rated dramas?',
                'Recommend a romantic comedy drama',
                'Show me recent action movies',
                'What thriller series do you have?',
                'Suggest something similar to The King: Eternal Monarch',
                'I want to watch a fantasy drama',
            ]
        ]);
    }
}

