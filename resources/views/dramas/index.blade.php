@extends('layouts.app')

@section('title', 'Browse Dramas & Movies - DramaVault')

@section('content')
<!-- Page Header with Gradient Background -->
<section class="bg-primary text-white py-5 mb-4">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center" data-aos="fade-up">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="fas fa-film me-3"></i>Dramas & Movies
                </h1>
                <p class="lead mb-0">Discover amazing content from around the world</p>
            </div>
        </div>
    </div>
</section>

<div class="container py-4">
    <!-- Filters Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0" data-aos="fade-up">
                <div class="card-body">
                    <form action="{{ route('dramas.index') }}" method="GET" class="row g-3">
                        <!-- Search -->
                        <div class="col-md-3">
                            <label class="form-label fw-bold small">
                                <i class="fas fa-search me-1"></i>Search
                            </label>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Search dramas..." 
                                   value="{{ request('search') }}">
                        </div>

                        <!-- Genre Filter -->
                        <div class="col-md-2">
                            <label class="form-label fw-bold small">
                                <i class="fas fa-theater-masks me-1"></i>Genre
                            </label>
                            <select name="genre" class="form-select">
                                <option value="">All Genres</option>
                                @foreach($genres as $genre)
                                <option value="{{ $genre->slug }}" {{ request('genre') == $genre->slug ? 'selected' : '' }}>
                                    {{ $genre->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Type Filter -->
                        <div class="col-md-2">
                            <label class="form-label fw-bold small">
                                <i class="fas fa-film me-1"></i>Type
                            </label>
                            <select name="type" class="form-select">
                                <option value="">All Types</option>
                                <option value="series" {{ request('type') == 'series' ? 'selected' : '' }}>Series</option>
                                <option value="movie" {{ request('type') == 'movie' ? 'selected' : '' }}>Movie</option>
                            </select>
                        </div>

                        <!-- Country Filter -->
                        <div class="col-md-2">
                            <label class="form-label fw-bold small">
                                <i class="fas fa-globe me-1"></i>Country
                            </label>
                            <select name="country" class="form-select">
                                <option value="">All Countries</option>
                                <option value="South Korea" {{ request('country') == 'South Korea' ? 'selected' : '' }}>South Korea</option>
                                <option value="Japan" {{ request('country') == 'Japan' ? 'selected' : '' }}>Japan</option>
                                <option value="China" {{ request('country') == 'China' ? 'selected' : '' }}>China</option>
                                <option value="Thailand" {{ request('country') == 'Thailand' ? 'selected' : '' }}>Thailand</option>
                            </select>
                        </div>

                        <!-- Sort By -->
                        <div class="col-md-2">
                            <label class="form-label fw-bold small">
                                <i class="fas fa-sort me-1"></i>Sort By
                            </label>
                            <select name="sort" class="form-select">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Top Rated</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Popular</option>
                                <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title A-Z</option>
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="col-md-1 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary w-100" title="Apply Filters">
                                <i class="fas fa-filter"></i>
                            </button>
                        </div>
                    </form>
                    
                    <!-- Active Filters & Clear Button -->
                    @if(request()->hasAny(['search', 'genre', 'type', 'country', 'sort']))
                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <div>
                            @if(request('search'))
                                <span class="badge bg-primary me-1">Search: {{ request('search') }}</span>
                            @endif
                            @if(request('genre'))
                                <span class="badge bg-primary me-1">Genre: {{ request('genre') }}</span>
                            @endif
                            @if(request('type'))
                                <span class="badge bg-primary me-1">Type: {{ request('type') }}</span>
                            @endif
                            @if(request('country'))
                                <span class="badge bg-primary me-1">Country: {{ request('country') }}</span>
                            @endif
                        </div>
                        <a href="{{ route('dramas.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times me-1"></i>Clear All
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Results Count -->
    <div class="row mb-3">
        <div class="col-12" data-aos="fade-up">
            <div class="d-flex justify-content-between align-items-center">
                <p class="text-muted mb-0">
                    <i class="fas fa-list me-2"></i>
                    Showing {{ $dramas->firstItem() ?? 0 }} - {{ $dramas->lastItem() ?? 0 }} of {{ $dramas->total() }} results
                </p>
            </div>
        </div>
    </div>

    <!-- Dramas Grid -->
    <div class="row g-4 mb-4">
        @forelse($dramas as $drama)
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 6) * 50 }}">
            <a href="{{ route('dramas.show', $drama->slug) }}" class="text-decoration-none">
                <div class="card drama-card border-0 shadow-sm h-100">
                    <div class="position-relative overflow-hidden">
                        <img src="{{ $drama->poster_url }}" 
                             class="card-img-top drama-poster" 
                             alt="{{ $drama->title }}"
                             style="height: 300px; object-fit: cover;"
                             onerror="this.onerror=null; this.src='{{ asset('images/default-poster.png') }}';">
                        
                        @if($drama->is_featured)
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-crown me-1"></i>Featured
                            </span>
                        </div>
                        @endif
                        
                        <div class="position-absolute bottom-0 start-0 w-100 p-2" 
                             style="background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-warning fw-bold">
                                    <i class="fas fa-star me-1"></i>{{ number_format($drama->avg_rating ?? 0, 1) }}
                                </small>
                                <small class="text-white">{{ $drama->release_year }}</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-3">
                        <h6 class="card-title fw-bold mb-2 text-dark" style="min-height: 40px;">
                            {{ Str::limit($drama->title, 35) }}
                        </h6>
                        <div class="d-flex justify-content-between text-muted small mb-2">
                            <span><i class="fas fa-tag me-1"></i>{{ ucfirst($drama->type) }}</span>
                            <span><i class="fas fa-list me-1"></i>{{ $drama->episodes }} eps</span>
                        </div>
                        <div class="genres">
                            @foreach($drama->genres->take(2) as $genre)
                            <span class="badge bg-primary bg-opacity-10 text-primary me-1 mb-1" style="font-size: 0.7rem;">
                                {{ $genre->name }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @empty
        <div class="col-12" data-aos="fade-up">
            <div class="text-center py-5">
                <i class="fas fa-film fa-4x text-muted mb-4"></i>
                <h4 class="mb-3">No dramas found</h4>
                <p class="text-muted mb-4">Try adjusting your filters or search terms</p>
                <a href="{{ route('dramas.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-redo me-2"></i>Reset Filters
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($dramas->hasPages())
    <div class="row mt-5">
        <div class="col-12" data-aos="fade-up">
            <div class="d-flex justify-content-center">
                {{ $dramas->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
/* Drama Card Hover Effects */
.drama-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.drama-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2) !important;
}

.drama-poster {
    transition: transform 0.5s ease;
}

.drama-card:hover .drama-poster {
    transform: scale(1.1);
}

/* Filter Card Styling */
.card {
    border-radius: 12px;
}

/* Badge Styling */
.badge {
    font-weight: 500;
    padding: 0.4em 0.65em;
}

/* Form Controls */
.form-control:focus,
.form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}

/* Smooth Animations */
.overflow-hidden {
    overflow: hidden;
}
</style>
@endpush
