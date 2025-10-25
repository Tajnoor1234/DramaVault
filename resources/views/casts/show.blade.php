@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Cast Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <img src="{{ $cast->image_url }}" 
                                 alt="{{ $cast->name }}" 
                                 class="img-fluid rounded-circle shadow"
                                 style="width: 200px; height: 200px; object-fit: cover;">
                        </div>
                        <div class="col-md-9">
                            <h1 class="display-5 fw-bold mb-3">{{ $cast->name }}</h1>
                            
                            <div class="row mb-3">
                                @if($cast->birth_date)
                                <div class="col-md-6">
                                    <strong><i class="fas fa-birthday-cake me-2"></i>Birth Date:</strong>
                                    <span class="ms-2">{{ $cast->birth_date->format('F d, Y') }}</span>
                                    @if($cast->age)
                                    <span class="text-muted">({{ $cast->age }} years old)</span>
                                    @endif
                                </div>
                                @endif
                                
                                @if($cast->birth_place)
                                <div class="col-md-6">
                                    <strong><i class="fas fa-map-marker-alt me-2"></i>Birth Place:</strong>
                                    <span class="ms-2">{{ $cast->birth_place }}</span>
                                </div>
                                @endif
                            </div>

                            @if($cast->gender)
                            <div class="mb-3">
                                <strong><i class="fas fa-venus-mars me-2"></i>Gender:</strong>
                                <span class="ms-2">{{ ucfirst($cast->gender) }}</span>
                            </div>
                            @endif

                            @if($cast->bio)
                            <div class="mb-3">
                                <strong><i class="fas fa-user me-2"></i>Biography:</strong>
                                <p class="mt-2 text-muted">{{ $cast->bio }}</p>
                            </div>
                            @endif

                            @if($cast->social_links)
                            <div class="mb-3">
                                <strong><i class="fas fa-share-alt me-2"></i>Social Media:</strong>
                                <div class="mt-2">
                                    @foreach($cast->social_links as $platform => $url)
                                    <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-outline-primary me-2">
                                        <i class="fab fa-{{ $platform }}"></i> {{ ucfirst($platform) }}
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filmography -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-film me-2"></i>Filmography
                        <span class="badge bg-primary ms-2">{{ $cast->dramas->count() }}</span>
                    </h4>
                </div>
                <div class="card-body">
                    @if($cast->dramas->count() > 0)
                    <div class="row g-3">
                        @foreach($cast->dramas as $drama)
                        <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                            <a href="{{ route('dramas.show', $drama->slug) }}" class="text-decoration-none">
                                <div class="card border-0 shadow-sm hover-lift h-100">
                                    <img src="{{ $drama->poster_url }}" 
                                         class="card-img-top" 
                                         alt="{{ $drama->title }}"
                                         style="height: 250px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <h6 class="card-title small fw-bold text-dark mb-1 text-truncate" title="{{ $drama->title }}">
                                            {{ $drama->title }}
                                        </h6>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-warning">
                                                <i class="fas fa-star me-1"></i>{{ $drama->avg_rating }}
                                            </small>
                                            <small class="text-muted">{{ $drama->release_year }}</small>
                                        </div>
                                        <div class="mt-1">
                                            <small class="text-muted d-block">{{ $drama->pivot->character_name }}</small>
                                            <span class="badge bg-secondary" style="font-size: 0.7rem;">
                                                {{ ucfirst($drama->pivot->role_type) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-film fa-3x mb-3"></i>
                        <p>No filmography available yet.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.hover-lift {
    transition: transform 0.2s, box-shadow 0.2s;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
@endpush
@endsection
