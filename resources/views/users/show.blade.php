@extends('layouts.app')

@section('title', $user->name . ' - DramaVault')

@section('content')
<div class="container py-4">
    <!-- Profile Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="position-relative">
                                <img src="{{ $user->avatar }}" 
                                     alt="{{ $user->name }}" 
                                     class="rounded-circle border border-3 border-primary"
                                     style="width: 120px; height: 120px; object-fit: cover;"
                                     id="profile-avatar">
                                @if(auth()->id() === $user->id)
                                <button class="btn btn-sm btn-primary rounded-circle position-absolute bottom-0 end-0" 
                                        style="width: 35px; height: 35px;"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editProfileModal">
                                    <i class="fas fa-camera"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                        <div class="col">
                            <h2 class="mb-1 fw-bold">{{ $user->name }}</h2>
                            @if($user->username)
                            <p class="text-muted mb-2">@<span>{{ $user->username }}</span></p>
                            @endif
                            @if($user->bio)
                            <p class="mb-3">{{ $user->bio }}</p>
                            @endif
                            <div class="d-flex gap-3 mb-3">
                                <div>
                                    <strong>{{ $user->ratings_count }}</strong>
                                    <span class="text-muted">Ratings</span>
                                </div>
                                <div>
                                    <strong>{{ $user->comments_count }}</strong>
                                    <span class="text-muted">Comments</span>
                                </div>
                                <div>
                                    <strong>{{ $user->followers_count }}</strong>
                                    <span class="text-muted">Followers</span>
                                </div>
                                <div>
                                    <strong>{{ $user->following_count }}</strong>
                                    <span class="text-muted">Following</span>
                                </div>
                            </div>
                            
                            @if(auth()->id() === $user->id)
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                <i class="fas fa-edit me-2"></i>Edit Profile
                            </button>
                            @else
                            <form action="{{ route('users.follow', $user) }}" method="POST" class="d-inline">
                                @csrf
                                @if(auth()->user()->following->contains($user))
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="fas fa-user-minus me-2"></i>Unfollow
                                </button>
                                @else
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-user-plus me-2"></i>Follow
                                </button>
                                @endif
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button">
                <i class="fas fa-star me-2"></i>My Reviews ({{ $user->ratings_count }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="comments-tab" data-bs-toggle="tab" data-bs-target="#comments" type="button">
                <i class="fas fa-comments me-2"></i>My Comments ({{ $user->comments_count }})
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="profileTabsContent">
        <!-- Reviews Tab -->
        <div class="tab-pane fade show active" id="reviews" role="tabpanel">
            @if($ratings->count() > 0)
            <div class="row g-3">
                @foreach($ratings as $rating)
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex gap-3">
                                <a href="{{ route('dramas.show', $rating->drama->slug) }}">
                                    <img src="{{ $rating->drama->poster_url }}" 
                                         alt="{{ $rating->drama->title }}"
                                         class="rounded"
                                         style="width: 80px; height: 120px; object-fit: cover;"
                                         onerror="this.src='{{ asset('images/default-poster.png') }}'">
                                </a>
                                <div class="flex-grow-1">
                                    <h6 class="mb-2">
                                        <a href="{{ route('dramas.show', $rating->drama->slug) }}" 
                                           class="text-dark text-decoration-none">
                                            {{ $rating->drama->title }}
                                        </a>
                                    </h6>
                                    <div class="mb-2">
                                        @for($i = 1; $i <= 10; $i++)
                                        <i class="fas fa-star {{ $i <= $rating->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                        <span class="ms-2 fw-bold">{{ $rating->rating }}/10</span>
                                    </div>
                                    @if($rating->review)
                                    <p class="text-muted small mb-2">{{ Str::limit($rating->review, 100) }}</p>
                                    @endif
                                    <small class="text-muted">{{ $rating->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-star fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No ratings yet</h5>
                @if(auth()->id() === $user->id)
                <a href="{{ route('dramas.index') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-search me-2"></i>Explore Dramas
                </a>
                @endif
            </div>
            @endif
        </div>

        <!-- Comments Tab -->
        <div class="tab-pane fade" id="comments" role="tabpanel">
            @php
                $comments = $user->comments()->with(['drama', 'news'])->latest()->paginate(10);
            @endphp
            
            @if($comments->count() > 0)
            <div class="list-group">
                @foreach($comments as $comment)
                <div class="list-group-item border-0 shadow-sm mb-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="mb-0">
                            @if($comment->drama_id)
                            <i class="fas fa-film text-primary me-2"></i>
                            <a href="{{ route('dramas.show', $comment->drama->slug) }}" class="text-dark">
                                {{ $comment->drama->title }}
                            </a>
                            @elseif($comment->news_id)
                            <i class="fas fa-newspaper text-info me-2"></i>
                            <a href="{{ route('news.show', $comment->news->slug) }}" class="text-dark">
                                {{ $comment->news->title }}
                            </a>
                            @endif
                        </h6>
                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="mb-0">{{ $comment->body }}</p>
                    <div class="mt-2">
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-thumbs-up me-1"></i>{{ $comment->likes_count ?? 0 }}
                        </span>
                        <span class="badge bg-light text-dark ms-1">
                            <i class="fas fa-thumbs-down me-1"></i>{{ $comment->dislikes_count ?? 0 }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            {{ $comments->links() }}
            @else
            <div class="text-center py-5">
                <i class="fas fa-comments fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No comments yet</h5>
                @if(auth()->id() === $user->id)
                <p class="text-muted">Start engaging with the community!</p>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
@if(auth()->id() === $user->id)
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-edit me-2"></i>Edit Profile
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Avatar Upload -->
                    <div class="text-center mb-4">
                        <img src="{{ $user->avatar }}" 
                             alt="{{ $user->name }}" 
                             class="rounded-circle border border-3 border-primary mb-3"
                             style="width: 120px; height: 120px; object-fit: cover;"
                             id="avatar-preview">
                        <div>
                            <label for="avatar" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-camera me-2"></i>Change Avatar
                            </label>
                            <input type="file" 
                                   id="avatar" 
                                   name="avatar" 
                                   class="d-none" 
                                   accept="image/*"
                                   onchange="previewAvatar(this)">
                            <small class="d-block text-muted mt-2">Max size: 2MB</small>
                        </div>
                    </div>

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" 
                               required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text">@</span>
                            <input type="text" 
                                   class="form-control @error('username') is-invalid @enderror" 
                                   id="username" 
                                   name="username" 
                                   value="{{ old('username', $user->username) }}" 
                                   required>
                        </div>
                        @error('username')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               required>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Bio -->
                    <div class="mb-3">
                        <label for="bio" class="form-label">Bio</label>
                        <textarea class="form-control @error('bio') is-invalid @enderror" 
                                  id="bio" 
                                  name="bio" 
                                  rows="3" 
                                  maxlength="1000">{{ old('bio', $user->bio) }}</textarea>
                        <small class="text-muted">{{ strlen($user->bio ?? '') }}/1000 characters</small>
                        @error('bio')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Theme Preference -->
                    <div class="mb-3">
                        <label for="theme_preference" class="form-label">Theme Preference</label>
                        <select class="form-select @error('theme_preference') is-invalid @enderror" 
                                id="theme_preference" 
                                name="theme_preference">
                            <option value="light" {{ old('theme_preference', $user->theme_preference) === 'light' ? 'selected' : '' }}>
                                Light
                            </option>
                            <option value="dark" {{ old('theme_preference', $user->theme_preference) === 'dark' ? 'selected' : '' }}>
                                Dark
                            </option>
                        </select>
                        @error('theme_preference')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar-preview').src = e.target.result;
            document.getElementById('profile-avatar').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Show success message
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

// Character counter for bio
document.getElementById('bio')?.addEventListener('input', function() {
    const counter = this.nextElementSibling;
    if (counter && counter.classList.contains('text-muted')) {
        counter.textContent = `${this.value.length}/1000 characters`;
    }
});

// Handle hash/anchor navigation from dropdown menu
function activateTabFromHash() {
    const hash = window.location.hash;
    
    if (hash === '#comments' || hash === '#reviews') {
        const tabId = hash.substring(1); // Remove the #
        const tabButton = document.getElementById(tabId + '-tab');
        
        if (tabButton) {
            // Remove active class from all tabs
            document.querySelectorAll('#profileTabs .nav-link').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Remove active class from all tab panes
            document.querySelectorAll('#profileTabsContent .tab-pane').forEach(pane => {
                pane.classList.remove('show', 'active');
            });
            
            // Activate the target tab button
            tabButton.classList.add('active');
            
            // Activate the target tab pane
            const targetPane = document.getElementById(tabId);
            if (targetPane) {
                targetPane.classList.add('show', 'active');
            }
            
            // Scroll to the tabs section after a short delay
            setTimeout(() => {
                const tabsElement = document.getElementById('profileTabs');
                if (tabsElement) {
                    tabsElement.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'start' 
                    });
                }
            }, 150);
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Activate tab on page load
    activateTabFromHash();
    
    // Update hash when tab is clicked manually
    document.querySelectorAll('#profileTabs button[data-bs-toggle="tab"]').forEach(button => {
        button.addEventListener('shown.bs.tab', function (e) {
            const targetId = e.target.getAttribute('data-bs-target').substring(1);
            window.history.pushState(null, null, '#' + targetId);
        });
    });
    
    // Listen for hash changes (e.g., browser back button)
    window.addEventListener('hashchange', function() {
        activateTabFromHash();
    });
});
</script>
@endpush
