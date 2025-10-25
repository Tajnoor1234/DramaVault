@extends('layouts.app')

@section('title', 'Manage Cast Members - Admin')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">
                <i class="fas fa-users"></i> Manage Cast Members
            </h1>
            <p class="text-muted mb-0">View, edit, and manage all cast members</p>
        </div>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <a href="{{ route('admin.casts.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Add New Cast Member
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h3 class="mb-0">{{ $casts->total() }}</h3>
                    <p class="mb-0">Total Cast Members</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h3 class="mb-0">{{ $casts->sum('dramas_count') }}</h3>
                    <p class="mb-0">Total Appearances</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h3 class="mb-0">{{ number_format($casts->avg('dramas_count'), 1) }}</h3>
                    <p class="mb-0">Avg Dramas per Cast</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.casts.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search by name or bio..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select">
                        <option value="">All Genders</option>
                        <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ request('gender') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sort By</label>
                    <select name="sort" class="form-select">
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest Added</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="most_dramas" {{ request('sort') === 'most_dramas' ? 'selected' : '' }}>Most Dramas</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Cast Members Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Cast Members ({{ $casts->total() }})</h5>
        </div>
        <div class="card-body p-0">
            @if($casts->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">Image</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Birth Date</th>
                            <th>Birth Place</th>
                            <th class="text-center">Dramas</th>
                            <th class="text-center" style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($casts as $cast)
                        <tr>
                            <td>
                                <img src="{{ $cast->image_url }}" 
                                     alt="{{ $cast->name }}" 
                                     class="rounded"
                                     style="width: 50px; height: 50px; object-fit: cover;">
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $cast->name }}</strong>
                                    @if($cast->age)
                                    <br><small class="text-muted">Age: {{ $cast->age }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($cast->gender)
                                <span class="badge bg-secondary">
                                    <i class="fas fa-{{ $cast->gender === 'male' ? 'mars' : ($cast->gender === 'female' ? 'venus' : 'genderless') }}"></i>
                                    {{ ucfirst($cast->gender) }}
                                </span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($cast->birth_date)
                                {{ $cast->birth_date->format('M d, Y') }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                {{ $cast->birth_place ?? '-' }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $cast->dramas_count }}</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('casts.show', $cast) }}" 
                                       class="btn btn-outline-primary" 
                                       title="View Profile"
                                       target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.casts.edit', $cast) }}" 
                                       class="btn btn-outline-warning" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-danger" 
                                            title="Delete"
                                            onclick="deleteCast('{{ $cast->slug }}', '{{ $cast->name }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No cast members found</h5>
                @if(request()->hasAny(['search', 'gender', 'sort']))
                <a href="{{ route('admin.casts.index') }}" class="btn btn-outline-primary mt-3">
                    <i class="fas fa-times"></i> Clear Filters
                </a>
                @else
                <a href="{{ route('admin.casts.create') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-plus"></i> Add First Cast Member
                </a>
                @endif
            </div>
            @endif
        </div>
        @if($casts->count() > 0)
        <div class="card-footer bg-white">
            {{ $casts->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCastModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i> Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="castName"></strong>?</p>
                <p class="text-danger mb-0">
                    <i class="fas fa-warning"></i> This action cannot be undone. The cast member will be removed from all dramas.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteCastForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete Cast Member
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function deleteCast(slug, name) {
    document.getElementById('castName').textContent = name;
    document.getElementById('deleteCastForm').action = `/admin/casts/${slug}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteCastModal'));
    modal.show();
}
</script>
@endpush
