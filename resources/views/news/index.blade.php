@extends('layouts.app')

@section('title', 'Drama News - DramaVault')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row mb-5">
        <div class="col-12">
            <h1 class="fw-bold" data-aos="fade-up">
                <i class="fas fa-newspaper me-2"></i>Drama News
            </h1>
            <p class="text-muted" data-aos="fade-up" data-aos-delay="100">
                Stay updated with the latest drama news and updates
            </p>
        </div>
    </div>

    <!-- Featured News -->
    @if($featuredNews->count() > 0)
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="fw-bold mb-4" data-aos="fade-up">Featured Stories</h3>
        </div>
        @foreach($featuredNews as $featured)
        <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
            <div class="card news-card h-100 shadow-sm border-0">
                <div class="position-relative">
                    <img src="{{ $featured->image_url }}" class="card-img-top" alt="{{ $featured->title }}" style="height: 200px; object-fit: cover;">
                    <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2">
                        <i class="fas fa-star me-1"></i>Featured
                    </span>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-primary me-2">{{ $featured->category }}</span>
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>{{ $featured->published_at->diffForHumans() }}
                        </small>
                    </div>
                    <h5 class="card-title fw-bold">{{ $featured->title }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($featured->excerpt, 120) }}</p>
                    <a href="{{ route('news.show', $featured->slug) }}" class="btn btn-outline-primary btn-sm">
                        Read More <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- All News -->
    <div class="row">
        <div class="col-12">
            <h3 class="fw-bold mb-4" data-aos="fade-up">Latest News</h3>
        </div>
        @forelse($news as $article)
        <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->index % 4 * 50 }}">
            <div class="card news-card h-100 shadow-sm border-0">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="{{ $article->image_url }}" class="img-fluid rounded-start h-100" alt="{{ $article->title }}" style="object-fit: cover;">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-primary me-2">{{ $article->category }}</span>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>{{ $article->published_at->diffForHumans() }}
                                </small>
                            </div>
                            <h6 class="card-title fw-bold">{{ Str::limit($article->title, 60) }}</h6>
                            <p class="card-text text-muted small">{{ Str::limit($article->excerpt, 100) }}</p>
                            <a href="{{ route('news.show', $article->slug) }}" class="btn btn-primary btn-sm">
                                Read More
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5" data-aos="fade-up">
            <i class="fas fa-newspaper fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No news articles yet</h4>
            <p class="text-muted">Check back soon for the latest drama news!</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($news->hasPages())
    <div class="row mt-5">
        <div class="col-12">
            <nav aria-label="News pagination" data-aos="fade-up">
                {{ $news->links('vendor.pagination.custom') }}
            </nav>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.news-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.news-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>
@endpush
