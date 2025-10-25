@extends('layouts.app')

@section('title', 'DramaVault - Your Ultimate Drama & Movie Database')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12 text-center" data-aos="fade-up">
                <h1 class="display-2 fw-bold mb-4">
                    <i class="fas fa-film me-3"></i>DramaVault
                </h1>
                <h2 class="display-5 fw-bold mb-4">Discover Your Next Favorite Drama</h2>
                <p class="lead mb-4">Explore thousands of dramas and movies, read reviews, track your watchlist, and connect with fellow enthusiasts.</p>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="{{ route('dramas.index') }}" class="btn btn-light btn-lg px-4">
                        <i class="fas fa-play me-2"></i>Explore Now
                    </a>
                    @guest
                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="fas fa-user-plus me-2"></i>Join Free
                    </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Dramas -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold mb-3" data-aos="fade-up">Featured Dramas</h2>
                <p class="text-muted" data-aos="fade-up" data-aos-delay="100">Handpicked selections you don't want to miss</p>
            </div>
        </div>
        
        <div class="row g-4">
            @foreach($featuredDramas as $drama)
            <div class="col-xl-3 col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="card drama-card h-100 shadow-sm border-0">
                    <div class="position-relative">
                        <img src="{{ $drama->poster_url }}" class="card-img-top" alt="{{ $drama->title }}">
                        <div class="card-img-overlay d-flex align-items-end p-0">
                            <div class="rating-badge bg-dark bg-opacity-75 text-white px-2 py-1 ms-2 mb-2 rounded">
                                <i class="fas fa-star text-warning me-1"></i>{{ number_format($drama->avg_rating, 1) }}
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="card-title fw-bold">{{ Str::limit($drama->title, 40) }}</h6>
                        <div class="d-flex justify-content-between text-muted small mb-2">
                            <span>{{ $drama->release_year }}</span>
                            <span>{{ $drama->country }}</span>
                        </div>
                        <div class="genres mb-2">
                            @foreach($drama->genres->take(2) as $genre)
                            <span class="badge bg-primary bg-opacity-10 text-primary me-1">{{ $genre->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <a href="{{ route('dramas.show', $drama->slug) }}" class="btn btn-primary btn-sm w-100">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-4" data-aos="fade-up">
                <div class="stat-card">
                    <i class="fas fa-tv fa-3x text-primary mb-3"></i>
                    <h3 class="fw-bold">{{ number_format($totalDramas) }}+</h3>
                    <p class="text-muted">Dramas & Movies</p>
                </div>
            </div>
            <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-card">
                    <i class="fas fa-users fa-3x text-primary mb-3"></i>
                    <h3 class="fw-bold">{{ number_format($totalUsers) }}+</h3>
                    <p class="text-muted">Community Members</p>
                </div>
            </div>
            <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-card">
                    <i class="fas fa-star fa-3x text-primary mb-3"></i>
                    <h3 class="fw-bold">{{ number_format($totalRatings) }}+</h3>
                    <p class="text-muted">Ratings & Reviews</p>
                </div>
            </div>
            <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-card">
                    <i class="fas fa-comments fa-3x text-primary mb-3"></i>
                    <h3 class="fw-bold">{{ number_format($totalComments) }}+</h3>
                    <p class="text-muted">Discussions</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Latest News -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold mb-3" data-aos="fade-up">Latest News</h2>
                <p class="text-muted" data-aos="fade-up" data-aos-delay="100">Stay updated with the latest drama news and updates</p>
            </div>
        </div>
        
        <div class="row g-4">
            @foreach($latestNews as $news)
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="card news-card h-100 shadow-sm border-0">
                    <img src="{{ $news->image_url }}" class="card-img-top" alt="{{ $news->title }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <span class="badge bg-primary mb-2">{{ $news->category }}</span>
                        <h6 class="card-title fw-bold">{{ Str::limit($news->title, 60) }}</h6>
                        <p class="card-text text-muted small">{{ Str::limit($news->excerpt, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">{{ $news->published_at->diffForHumans() }}</small>
                            <a href="{{ route('news.show', $news->slug) }}" class="btn btn-primary btn-sm">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-4" data-aos="fade-up">
            <a href="{{ route('news.index') }}" class="btn btn-outline-primary">View All News</a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="zoom-in">
                <h2 class="fw-bold mb-3">Ready to Start Your Drama Journey?</h2>
                <p class="lead mb-4">Join thousands of drama enthusiasts and never miss your next favorite show.</p>
                @guest
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4">
                        <i class="fas fa-user-plus me-2"></i>Sign Up Free
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </a>
                </div>
                @else
                <a href="{{ route('dramas.index') }}" class="btn btn-light btn-lg px-4">
                    <i class="fas fa-explore me-2"></i>Explore Dramas
                </a>
                @endguest
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    // Drama card hover effects
    document.addEventListener('DOMContentLoaded', function() {
        const dramaCards = document.querySelectorAll('.drama-card');
        dramaCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.transition = 'all 0.3s ease';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>
@endsection
