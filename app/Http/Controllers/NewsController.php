<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Services\NewsDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    protected $newsDataService;

    public function __construct(NewsDataService $newsDataService)
    {
        $this->newsDataService = $newsDataService;
    }
    public function index()
    {
        $news = News::where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(10);

        $featuredNews = News::where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('is_featured', true)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('news.index', compact('news', 'featuredNews'));
    }

    public function show(News $news)
    {
        if (!$news->is_published || $news->published_at->isFuture()) {
            abort(404);
        }

        $news->incrementViews();
        $news->load([
            'author', 
            'comments' => function($query) {
                $query->latest()->with(['user', 'replies' => function($q) {
                    $q->with(['user', 'replies' => function($q2) {
                        $q2->with(['user', 'replies.user']);
                    }]);
                }]);
            },
            'allComments'
        ]);

        $relatedNews = News::published()
            ->where('id', '!=', $news->id)
            ->where('category', $news->category)
            ->latest('published_at')
            ->limit(4)
            ->get();

        $latestNews = News::published()
            ->where('id', '!=', $news->id)
            ->latest('published_at')
            ->limit(5)
            ->get();

        return view('news.show', compact('news', 'relatedNews', 'latestNews'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'category' => 'required|string',
            'image' => 'nullable|image|max:5120',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'is_featured' => 'boolean',
        ]);

        $news = new News();
        $news->title = $validated['title'];
        
        // Generate unique slug
        $slug = Str::slug($validated['title']);
        $count = News::where('slug', 'LIKE', "{$slug}%")->count();
        $news->slug = $count > 0 ? "{$slug}-" . ($count + 1) : $slug;
        
        $news->excerpt = $validated['excerpt'];
        $news->content = $validated['content'];
        $news->category = $validated['category'];
        $news->author_id = auth()->id();
        $news->is_published = $validated['is_published'] ?? false;
        $news->published_at = $validated['published_at'] ?? now();
        $news->is_featured = $validated['is_featured'] ?? false;

        if ($request->hasFile('image')) {
            $news->image_path = $request->file('image')->store('news', 'public');
        }

        $news->save();

        return redirect()->route('news.show', $news->slug)
            ->with('success', 'News article created successfully!');
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'category' => 'required|string',
            'image' => 'nullable|image|max:5120',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'is_featured' => 'boolean',
        ]);

        $news->title = $validated['title'];
        $news->excerpt = $validated['excerpt'];
        $news->content = $validated['content'];
        $news->category = $validated['category'];
        $news->is_published = $validated['is_published'] ?? false;
        $news->published_at = $validated['published_at'] ?? $news->published_at;
        $news->is_featured = $validated['is_featured'] ?? false;

        if ($request->hasFile('image')) {
            // Delete old image
            if ($news->image_path) {
                Storage::disk('public')->delete($news->image_path);
            }
            $news->image_path = $request->file('image')->store('news', 'public');
        }

        $news->save();

        return redirect()->route('news.show', $news->slug)
            ->with('success', 'News article updated successfully!');
    }

    public function destroy(News $news)
    {
        if ($news->image_path) {
            Storage::disk('public')->delete($news->image_path);
        }

        $news->delete();

        return redirect()->route('news.index')
            ->with('success', 'News article deleted successfully!');
    }

    /**
     * Fetch news from NewsData.io
     */
    public function fetchAPINews()
    {
        // Clear cache to get fresh data
        \Cache::forget('newsdata_latest_entertainment_');
        \Cache::forget('newsdata_latest_entertainment_drama OR movie OR series OR kdrama');
        
        $entertainmentNews = $this->newsDataService->getEntertainmentNews();
        $dramaNews = $this->newsDataService->getDramaNews();

        // Debug: Log the response
        \Log::info('Entertainment News Response', ['total' => $entertainmentNews['totalResults'] ?? 0]);
        \Log::info('Drama News Response', ['total' => $dramaNews['totalResults'] ?? 0]);

        return view('admin.news.api-import', compact('entertainmentNews', 'dramaNews'));
    }

    /**
     * Import news from NewsData.io
     */
    public function importAPINews(Request $request)
    {
        $validated = $request->validate([
            'articles' => 'required|array',
            'articles.*.title' => 'required|string',
            'articles.*.description' => 'nullable|string',
            'articles.*.content' => 'nullable|string',
            'articles.*.link' => 'required|url',
            'articles.*.image_url' => 'nullable|url',
            'articles.*.pubDate' => 'required',
            'articles.*.source_id' => 'nullable|string',
        ]);

        $imported = 0;
        $skipped = 0;

        foreach ($validated['articles'] as $article) {
            // Check if news already exists by URL
            if (News::where('source_url', $article['link'])->exists()) {
                $skipped++;
                continue;
            }

            // Download image if available
            $imagePath = null;
            if (!empty($article['image_url'])) {
                try {
                    $imageContent = file_get_contents($article['image_url']);
                    $imageName = 'news/' . Str::random(40) . '.jpg';
                    Storage::disk('public')->put($imageName, $imageContent);
                    $imagePath = $imageName;
                } catch (\Exception $e) {
                    // Skip image if download fails
                }
            }

            // Create news article
            News::create([
                'title' => $article['title'],
                'slug' => Str::slug($article['title']) . '-' . Str::random(6),
                'excerpt' => $article['description'] ?? Str::limit($article['content'] ?? '', 200),
                'content' => $article['content'] ?? $article['description'] ?? '',
                'category' => 'Entertainment',
                'author_id' => auth()->id(),
                'image_path' => $imagePath,
                'source' => $article['source_id'] ?? 'NewsData.io',
                'source_url' => $article['link'],
                'is_published' => true,
                'published_at' => $article['pubDate'],
                'is_featured' => false,
            ]);

            $imported++;
        }

        return redirect()->back()->with('success', "Imported {$imported} articles. Skipped {$skipped} duplicates.");
    }
}
