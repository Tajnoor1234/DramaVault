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

<!-- Latest Added Dramas -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold mb-3" data-aos="fade-up">
                    <i class="fas fa-clock text-primary me-2"></i>Recently Added
                </h2>
                <p class="text-muted" data-aos="fade-up" data-aos-delay="100">Check out the latest dramas, series, and movies</p>
            </div>
        </div>
        
        <div class="row g-4">
            @forelse($latestDramas as $drama)
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                <a href="{{ route('dramas.show', $drama->slug) }}" class="text-decoration-none">
                    <div class="card drama-card h-100 shadow-sm border-0">
                        <div class="position-relative">
                            <img src="{{ $drama->poster_url }}" 
                                 class="card-img-top" 
                                 alt="{{ $drama->title }}"
                                 style="height: 250px; object-fit: cover;"
                                 onerror="this.onerror=null; this.src='{{ asset('images/default-poster.png') }}';">
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge bg-success">
                                    <i class="fas fa-plus me-1"></i>New
                                </span>
                            </div>
                            <div class="card-img-overlay d-flex align-items-end p-0">
                                <div class="w-100 bg-dark bg-opacity-75 text-white px-2 py-1">
                                    <small><i class="fas fa-star text-warning me-1"></i>{{ number_format($drama->avg_rating, 1) }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-2">
                            <h6 class="card-title small fw-bold mb-1 text-dark" style="min-height: 40px;">{{ Str::limit($drama->title, 30) }}</h6>
                            <div class="d-flex justify-content-between text-muted" style="font-size: 0.75rem;">
                                <span>{{ $drama->type }}</span>
                                <span>{{ $drama->release_year }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-tv fa-3x text-muted mb-3"></i>
                <p class="text-muted">No dramas added yet.</p>
            </div>
            @endforelse
        </div>
        
        <div class="text-center mt-4" data-aos="fade-up">
            <a href="{{ route('dramas.index') }}" class="btn btn-primary">
                <i class="fas fa-th-large me-2"></i>View All Dramas/Movies
            </a>
        </div>
    </div>
</section>

<!-- Trending & Top Rated -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Trending Dramas -->
            <div class="col-lg-6 mb-5">
                <div class="row mb-4">
                    <div class="col-12">
                        <h2 class="fw-bold mb-3" data-aos="fade-up">
                            <i class="fas fa-fire text-danger me-2"></i>Trending Now
                        </h2>
                        <p class="text-muted" data-aos="fade-up" data-aos-delay="100">Most watched dramas</p>
                    </div>
                </div>
                
                <div class="row g-3">
                    @foreach($trendingDramas->take(4) as $drama)
                    <div class="col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <a href="{{ route('dramas.show', $drama->slug) }}" class="text-decoration-none">
                            <div class="card drama-card shadow-sm border-0">
                                <div class="row g-0">
                                    <div class="col-4">
                                        <img src="{{ $drama->poster_url }}" 
                                             class="img-fluid rounded-start" 
                                             alt="{{ $drama->title }}"
                                             style="height: 120px; object-fit: cover;"
                                             onerror="this.onerror=null; this.src='{{ asset('images/default-poster.png') }}';">
                                    </div>
                                    <div class="col-8">
                                        <div class="card-body p-2">
                                            <h6 class="card-title small fw-bold text-dark mb-1">{{ Str::limit($drama->title, 25) }}</h6>
                                            <div class="d-flex align-items-center mb-1">
                                                <i class="fas fa-star text-warning me-1" style="font-size: 0.7rem;"></i>
                                                <small class="text-muted">{{ number_format($drama->avg_rating, 1) }}</small>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-eye text-primary me-1" style="font-size: 0.7rem;"></i>
                                                <small class="text-muted">{{ number_format($drama->total_views) }} views</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Top Rated Dramas -->
            <div class="col-lg-6 mb-5">
                <div class="row mb-4">
                    <div class="col-12">
                        <h2 class="fw-bold mb-3" data-aos="fade-up">
                            <i class="fas fa-trophy text-warning me-2"></i>Top Rated
                        </h2>
                        <p class="text-muted" data-aos="fade-up" data-aos-delay="100">Highest rated by users</p>
                    </div>
                </div>
                
                <div class="row g-3">
                    @foreach($topRatedDramas->take(4) as $drama)
                    <div class="col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <a href="{{ route('dramas.show', $drama->slug) }}" class="text-decoration-none">
                            <div class="card drama-card shadow-sm border-0">
                                <div class="row g-0">
                                    <div class="col-4">
                                        <img src="{{ $drama->poster_url }}" 
                                             class="img-fluid rounded-start" 
                                             alt="{{ $drama->title }}"
                                             style="height: 120px; object-fit: cover;"
                                             onerror="this.onerror=null; this.src='{{ asset('images/default-poster.png') }}';">
                                    </div>
                                    <div class="col-8">
                                        <div class="card-body p-2">
                                            <h6 class="card-title small fw-bold text-dark mb-1">{{ Str::limit($drama->title, 25) }}</h6>
                                            <div class="d-flex align-items-center mb-1">
                                                <i class="fas fa-star text-warning me-1" style="font-size: 0.7rem;"></i>
                                                <small class="text-muted fw-bold">{{ number_format($drama->avg_rating, 1) }}/10</small>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-users text-primary me-1" style="font-size: 0.7rem;"></i>
                                                <small class="text-muted">{{ number_format($drama->total_ratings) }} ratings</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
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
