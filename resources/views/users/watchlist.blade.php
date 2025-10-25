@extends('layouts.app')

@section('title', 'My Watchlist - DramaVault')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-5 fw-bold animate__animated animate__fadeIn">
                <i class="fas fa-bookmark me-2 text-primary"></i>My Watchlist
            </h1>
            <p class="lead text-muted animate__animated animate__fadeIn">Track your drama journey</p>
        </div>
    </div>

    <!-- Watchlist Tabs -->
    <div class="row animate__animated animate__fadeInUp">
        <div class="col-12">
            <ul class="nav nav-pills mb-4" id="watchlistTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="watching-tab" data-bs-toggle="pill" 
                            data-bs-target="#watching" type="button" role="tab">
                        <i class="fas fa-play me-1"></i>Watching
                        <span class="badge bg-primary ms-1">{{ $statuses['watching']->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="completed-tab" data-bs-toggle="pill" 
                            data-bs-target="#completed" type="button" role="tab">
                        <i class="fas fa-check me-1"></i>Completed
                        <span class="badge bg-success ms-1">{{ $statuses['completed']->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="plan-tab" data-bs-toggle="pill" 
                            data-bs-target="#plan" type="button" role="tab">
                        <i class="fas fa-clock me-1"></i>Plan to Watch
                        <span class="badge bg-info ms-1">{{ $statuses['plan_to_watch']->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="hold-tab" data-bs-toggle="pill" 
                            data-bs-target="#hold" type="button" role="tab">
                        <i class="fas fa-pause me-1"></i>On Hold
                        <span class="badge bg-warning ms-1">{{ $statuses['on_hold']->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="dropped-tab" data-bs-toggle="pill" 
                            data-bs-target="#dropped" type="button" role="tab">
                        <i class="fas fa-times me-1"></i>Dropped
                        <span class="badge bg-danger ms-1">{{ $statuses['dropped']->count() }}</span>
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="watchlistTabsContent">
                <!-- Watching Tab -->
                <div class="tab-pane fade show active" id="watching" role="tabpanel">
                    @include('users.partials.watchlist-section', [
                        'items' => $statuses['watching'],
                        'emptyMessage' => 'No dramas currently being watched.'
                    ])
                </div>

                <!-- Completed Tab -->
                <div class="tab-pane fade" id="completed" role="tabpanel">
                    @include('users.partials.watchlist-section', [
                        'items' => $statuses['completed'],
                        'emptyMessage' => 'No completed dramas yet.'
                    ])
                </div>

                <!-- Plan to Watch Tab -->
                <div class="tab-pane fade" id="plan" role="tabpanel">
                    @include('users.partials.watchlist-section', [
                        'items' => $statuses['plan_to_watch'],
                        'emptyMessage' => 'No dramas planned to watch.'
                    ])
                </div>

                <!-- On Hold Tab -->
                <div class="tab-pane fade" id="hold" role="tabpanel">
                    @include('users.partials.watchlist-section', [
                        'items' => $statuses['on_hold'],
                        'emptyMessage' => 'No dramas on hold.'
                    ])
                </div>

                <!-- Dropped Tab -->
                <div class="tab-pane fade" id="dropped" role="tabpanel">
                    @include('users.partials.watchlist-section', [
                        'items' => $statuses['dropped'],
                        'emptyMessage' => 'No dropped dramas.'
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.watchlist-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.watchlist-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.watchlist-card a {
    color: inherit;
}

.watchlist-card a:hover {
    color: inherit;
}

.progress {
    height: 6px;
}

.nav-pills .nav-link {
    border-radius: 20px;
    margin-right: 10px;
    margin-bottom: 10px;
}

.nav-pills .nav-link.active {
    font-weight: 600;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle watchlist status change forms
    document.querySelectorAll('.watchlist-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const statusInput = this.querySelector('input[name="status"]');
            const statusName = statusInput ? statusInput.value.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) : '';
            
            // Show loading
            Swal.fire({
                title: 'Updating...',
                text: 'Changing status to ' + statusName,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Submit the form
            this.submit();
        });
    });
    
    // Handle watchlist delete forms
    document.querySelectorAll('.watchlist-delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            Swal.fire({
                title: 'Remove from Watchlist?',
                text: "This drama will be removed from your watchlist.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Removing...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    this.submit();
                }
            });
        });
    });
    
    // Show success message if present
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false
        });
    @endif
    
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            timer: 2000,
            showConfirmButton: false
        });
    @endif
});
</script>
@endpush