@extends('layouts.app')

@section('title', 'Cast & Actors - DramaVault')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold" data-aos="fade-up">
                <i class="fas fa-users me-2"></i>Cast & Actors
            </h1>
            <p class="text-muted" data-aos="fade-up" data-aos-delay="100">
                Browse through our collection of talented actors and actresses
            </p>
        </div>
    </div>

    <div class="row g-4">
        @forelse($casts as $cast)
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="{{ $loop->index % 6 * 50 }}">
            <div class="card cast-card h-100 shadow-sm border-0">
                <img src="{{ $cast->image_url }}" class="card-img-top" alt="{{ $cast->name }}" style="height: 250px; object-fit: cover;">
                <div class="card-body text-center">
                    <h6 class="card-title fw-bold mb-2">{{ $cast->name }}</h6>
                    <p class="text-muted small mb-2">
                        <i class="fas fa-film me-1"></i>{{ $cast->dramas_count }} {{ Str::plural('Drama', $cast->dramas_count) }}
                    </p>
                    <a href="{{ route('casts.show', $cast->slug) }}" class="btn btn-primary btn-sm w-100">
                        View Profile
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5" data-aos="fade-up">
            <i class="fas fa-users fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No cast members found</h4>
            <p class="text-muted">Check back soon for updates!</p>
        </div>
        @endforelse
    </div>

    @if($casts->hasPages())
    <div class="row mt-5">
        <div class="col-12">
            <nav aria-label="Cast pagination" data-aos="fade-up">
                {{ $casts->links() }}
            </nav>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.cast-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.cast-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>
@endpush
