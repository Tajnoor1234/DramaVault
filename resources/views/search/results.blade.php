@extends('layouts.app')

@section('title', 'Search Results - DramaVault')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Search Filters -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0"><i class="fas fa-filter me-2"></i>Filters</h6>
                </div>
                <div class="card-body">
                    <form id="search-filters" method="GET" action="{{ route('search') }}">
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select">
                                <option value="">All Types</option>
                                <option value="drama" {{ request('type') == 'drama' ? 'selected' : '' }}>Drama</option>
                                <option value="movie" {{ request('type') == 'movie' ? 'selected' : '' }}>Movie</option>
                                <option value="series" {{ request('type') == 'series' ? 'selected' : '' }}>Series</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Genre</label>
                            <select name="genre" class="form-select">
                                <option value="">All Genres</option>
                                @foreach($genres as $genre)
                                <option value="{{ $genre->name }}" {{ request('genre') == $genre->name ? 'selected' : '' }}>
                                    {{ $genre->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Country</label>
                            <input type="text" name="country" class="form-control" value="{{ request('country') }}" 
                                   placeholder="e.g., South Korea">
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label">Year From</label>
                                <input type="number" name="year_from" class="form-control" 
                                       value="{{ request('year_from') }}" min="1900" max="{{ date('Y') }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Year To</label>
                                <input type="number" name="year_to" class="form-control" 
                                       value="{{ request('year_to') }}" min="1900" max="{{ date('Y') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Minimum Rating</label>
                            <input type="number" name="rating_min" class="form-control" 
                                   value="{{ request('rating_min') }}" min="0" max="10" step="0.1">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Search Results -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold">
                    Search Results
                    @if($query)
                    for "{{ $query }}"
                    @endif
                </h4>
                <span class="text-muted">{{ $dramas->total() }} results found</span>
            </div>

            @if($dramas->count() > 0)
            <div class="row g-3">
                @foreach($dramas as $drama)
                <div class="col-lg-4 col-md-6">
                    <div class="drama-card card h-100 border-0 shadow-sm hover-lift">
                        <img src="{{ $drama->getPosterUrl() }}" class="card-img-top" 
                             alt="{{ $drama->title }}" style="height: 300px; object-fit: cover;">
                        <div class="card-body">
                            <h6 class="card-title fw-bold">{{ $drama->title }}</h6>
                            <div class="drama-meta text-muted small mb-2">
                                <span class="me-2">{{ $drama->release_year }}</span>
                                <span class="me-2">•</span>
                                <span>{{ ucfirst($drama->type) }}</span>
                            </div>
                            <div class="rating mb-2">
                                <span class="text-warning">
                                    <i class="fas fa-star me-1"></i>{{ $drama->rating }}
                                </span>
                                <span class="text-muted">({{ $drama->rating_count }})</span>
                            </div>
                            <a href="{{ route('dramas.show', $drama->slug) }}" class="btn btn-outline-primary btn-sm w-100">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $dramas->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-search fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No dramas found</h5>
                <p class="text-muted">Try adjusting your search criteria or browse all dramas.</p>
                <a href="{{ route('dramas.index') }}" class="btn btn-primary">Browse All Dramas</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when filters change
    const filterForm = document.getElementById('search-filters');
    const filterInputs = filterForm.querySelectorAll('select, input');
    
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            filterForm.submit();
        });
    });
});
</script>
@endpush