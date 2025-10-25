@extends('layouts.app')

@section('title', 'Manage Users - DramaVault')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="fas fa-users-cog me-2"></i>Manage Users
                    </h1>
                    <p class="text-muted">View and manage all registered users</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <h6 class="text-white-50 mb-1">Total Users</h6>
                    <h3 class="mb-0">{{ $users->total() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <h6 class="text-white-50 mb-1">Active Users</h6>
                    <h3 class="mb-0">{{ $users->where('is_active', true)->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-white">
                <div class="card-body">
                    <h6 class="text-white-50 mb-1">Admins</h6>
                    <h3 class="mb-0">{{ $users->where('role', 'admin')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-danger text-white">
                <div class="card-body">
                    <h6 class="text-white-50 mb-1">Banned Users</h6>
                    <h3 class="mb-0">{{ $users->where('is_active', false)->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search by name or email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="role" class="form-select">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="moderator" {{ request('role') == 'moderator' ? 'selected' : '' }}>Moderator</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Banned</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort" class="form-select">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                        <option value="most_active" {{ request('sort') == 'most_active' ? 'selected' : '' }}>Most Active</option>
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

    <!-- Users List -->
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                All Users ({{ $users->total() }})
            </h6>
            <span class="badge bg-primary">Page {{ $users->currentPage() }} of {{ $users->lastPage() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">Avatar</th>
                            <th>Name / Email</th>
                            <th style="width: 100px;">Role</th>
                            <th style="width: 100px;">Status</th>
                            <th style="width: 120px;">Activity</th>
                            <th style="width: 120px;">Joined</th>
                            <th style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr class="{{ !$user->is_active ? 'table-danger' : '' }}">
                            <td>
                                @if($user->avatar)
                                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" 
                                         class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $user->name }}</div>
                                <small class="text-muted">{{ $user->email }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'moderator' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle"></i> Active
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-ban"></i> Banned
                                    </span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="fas fa-star text-warning"></i> {{ $user->ratings_count ?? 0 }}<br>
                                    <i class="fas fa-comment text-info"></i> {{ $user->comments_count ?? 0 }}
                                </small>
                            </td>
                            <td>
                                <small class="text-muted">{{ $user->created_at->format('M d, Y') }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <!-- Change Role -->
                                    <div class="dropdown">
                                        <button class="btn btn-outline-primary dropdown-toggle" type="button" 
                                                data-bs-toggle="dropdown" title="Change Role">
                                            <i class="fas fa-user-tag"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <form method="POST" action="{{ route('admin.users.role', $user) }}" class="dropdown-item p-0">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="role" value="admin">
                                                    <button type="submit" class="btn btn-link text-decoration-none text-dark w-100 text-start">
                                                        <i class="fas fa-crown text-danger"></i> Make Admin
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('admin.users.role', $user) }}" class="dropdown-item p-0">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="role" value="moderator">
                                                    <button type="submit" class="btn btn-link text-decoration-none text-dark w-100 text-start">
                                                        <i class="fas fa-shield-alt text-warning"></i> Make Moderator
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('admin.users.role', $user) }}" class="dropdown-item p-0">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="role" value="user">
                                                    <button type="submit" class="btn btn-link text-decoration-none text-dark w-100 text-start">
                                                        <i class="fas fa-user text-secondary"></i> Make User
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Ban/Unban Toggle -->
                                    @if($user->is_active)
                                        <button type="button" class="btn btn-outline-danger ban-user" 
                                                data-id="{{ $user->id }}" 
                                                data-name="{{ $user->name }}" 
                                                title="Ban User">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-outline-success unban-user" 
                                                data-id="{{ $user->id }}" 
                                                data-name="{{ $user->name }}" 
                                                title="Unban User">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif

                                    <!-- View Profile -->
                                    <a href="{{ route('users.show', $user) }}" 
                                       class="btn btn-outline-info" 
                                       title="View Profile"
                                       target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No users found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($users->hasPages())
        <div class="card-footer">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// Ban User
document.querySelectorAll('.ban-user').forEach(button => {
    button.addEventListener('click', function() {
        const userId = this.dataset.id;
        const userName = this.dataset.name;
        
        Swal.fire({
            title: 'Ban User?',
            text: `Are you sure you want to ban "${userName}"? They will not be able to access the platform.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, ban user!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Banning user...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Send ban request
                fetch(`/admin/users/${userId}/ban`, {
                    method: 'POST',
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
                            title: 'Banned!',
                            text: data.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Error!', data.message || 'Failed to ban user', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error!', 'An error occurred while banning the user', 'error');
                });
            }
        });
    });
});

// Unban User
document.querySelectorAll('.unban-user').forEach(button => {
    button.addEventListener('click', function() {
        const userId = this.dataset.id;
        const userName = this.dataset.name;
        
        Swal.fire({
            title: 'Unban User?',
            text: `Restore access for "${userName}"?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, unban!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Unbanning user...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/admin/users/${userId}/unban`, {
                    method: 'POST',
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
                            title: 'Unbanned!',
                            text: data.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Error!', data.message || 'Failed to unban user', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error!', 'An error occurred while unbanning the user', 'error');
                });
            }
        });
    });
});
</script>
@endpush
@endsection
