@extends('layouts.app')

@section('title', 'Manage Drama/Movies - DramaVault')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="fas fa-film me-2"></i>Manage Drama/Movies
                    </h1>
                    <p class="text-muted">View, edit, and delete all drama/movie entries</p>
                </div>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                    <a href="{{ route('admin.import.index') }}" class="btn btn-success me-2">
                        <i class="fas fa-cloud-download-alt"></i> Import
                    </a>
                    <a href="{{ route('admin.dramas.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.dramas.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search by title..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="drama" {{ request('type') == 'drama' ? 'selected' : '' }}>Drama</option>
                        <option value="movie" {{ request('type') == 'movie' ? 'selected' : '' }}>Movie</option>
                        <option value="series" {{ request('type') == 'series' ? 'selected' : '' }}>Series</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort" class="form-select">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                        <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title A-Z</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating</option>
                        <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Views</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Drama/Movies List -->
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                All Drama/Movies ({{ $dramas->total() }})
            </h6>
            <span class="badge bg-primary">Page {{ $dramas->currentPage() }} of {{ $dramas->lastPage() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">Poster</th>
                            <th>Title</th>
                            <th style="width: 100px;">Type</th>
                            <th style="width: 100px;">Status</th>
                            <th style="width: 80px;">Year</th>
                            <th style="width: 100px;">Rating</th>
                            <th style="width: 100px;">Views</th>
                            <th style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dramas as $drama)
                        <tr>
                            <td>
                                <img src="{{ $drama->poster_url }}" alt="{{ $drama->title }}" 
                                     class="rounded" style="width: 40px; height: 60px; object-fit: cover;">
                            </td>
                            <td>
                                <a href="{{ route('dramas.show', $drama->slug) }}" 
                                   class="text-decoration-none text-dark fw-semibold" target="_blank">
                                    {{ Str::limit($drama->title, 40) }}
                                </a>
                                <br>
                                <small class="text-muted">{{ $drama->episodes }} episodes</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $drama->type == 'movie' ? 'info' : ($drama->type == 'series' ? 'success' : 'secondary') }}">
                                    {{ ucfirst($drama->type) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $drama->status == 'completed' ? 'success' : ($drama->status == 'ongoing' ? 'primary' : 'warning') }}">
                                    {{ ucfirst($drama->status) }}
                                </span>
                            </td>
                            <td>{{ $drama->release_year }}</td>
                            <td>
                                <span class="text-warning">
                                    <i class="fas fa-star"></i> {{ number_format($drama->avg_rating, 1) }}
                                </span>
                                <br>
                                <small class="text-muted">({{ $drama->total_ratings }})</small>
                            </td>
                            <td>
                                <i class="fas fa-eye text-muted"></i> {{ number_format($drama->total_views) }}
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('dramas.show', $drama->slug) }}" 
                                       class="btn btn-outline-info" 
                                       title="View" 
                                       target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.dramas.edit', $drama->slug) }}" 
                                       class="btn btn-outline-primary" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-danger delete-drama" 
                                            data-slug="{{ $drama->slug }}" 
                                            data-title="{{ $drama->title }}" 
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-film fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No drama/movies found</p>
                                <a href="{{ route('admin.dramas.create') }}" class="btn btn-primary mt-3">
                                    <i class="fas fa-plus"></i> Add Your First Drama/Movie
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($dramas->hasPages())
        <div class="card-footer">
            {{ $dramas->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// Delete Drama/Movie with SweetAlert2
document.querySelectorAll('.delete-drama').forEach(button => {
    button.addEventListener('click', function() {
        const dramaSlug = this.dataset.slug;
        const dramaTitle = this.dataset.title;
        
        Swal.fire({
            title: 'Delete Drama/Movie?',
            text: `Are you sure you want to delete "${dramaTitle}"? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Send delete request
                fetch(`/admin/dramas/${dramaSlug}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: data.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Error!', data.message || 'Failed to delete drama/movie', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error!', 'An error occurred while deleting the drama/movie', 'error');
                });
            }
        });
    });
});
</script>
@endpush
@endsection
