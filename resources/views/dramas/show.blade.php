@extends('layouts.app')

@section('title', $drama->title . ' - DramaVault')

@section('content')
<div class="container py-4">
    <!-- Drama Header -->
    <div class="row mb-5 animate__animated animate__fadeIn">
        <div class="col-md-4">
            <div class="drama-poster position-relative">
                <img src="{{ $drama->poster_url }}" 
                     alt="{{ $drama->title }}" 
                     class="img-fluid rounded-3 shadow-lg w-100" 
                     style="max-height: 500px; object-fit: cover;"
                     onerror="this.onerror=null; this.src='{{ asset('images/default-poster.png') }}';">
                <div class="position-absolute top-0 end-0 m-3">
                    @if($drama->is_featured)
                    <span class="badge bg-warning text-dark fs-6">
                        <i class="fas fa-crown me-1"></i>Featured
                    </span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="drama-info">
                <h1 class="display-5 fw-bold mb-2">{{ $drama->title }}</h1>
                
                <div class="drama-meta mb-3">
                    <span class="badge bg-primary fs-6 me-2">{{ $drama->type }}</span>
                    <span class="badge bg-secondary fs-6 me-2">{{ $drama->release_year }}</span>
                    <span class="badge bg-info fs-6 me-2">{{ $drama->country }}</span>
                    <span class="badge bg-success fs-6">{{ $drama->status }}</span>
                </div>

                <!-- Rating Section -->
                <div class="rating-section mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <div class="rating-display me-3">
                            <span class="display-4 fw-bold text-warning">{{ number_format($drama->avg_rating, 1) }}</span>
                            <span class="text-muted">/10</span>
                        </div>
                        <div>
                            <div class="rating-stars mb-1">
                                @for($i = 1; $i <= 10; $i++)
                                <i class="fas fa-star {{ $i <= $drama->avg_rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                            </div>
                            <small class="text-muted">{{ $drama->total_ratings }} ratings</small>
                        </div>
                    </div>
                    
                    @auth
                    <div class="rating-actions">
                        <form action="{{ $userRating ? route('ratings.update', $userRating->id) : route('ratings.store', $drama) }}" method="POST" class="rating-form" data-drama-id="{{ $drama->id }}">
                            @csrf
                            @if($userRating)
                                @method('PUT')
                            @endif
                            <div class="mb-2">
                                <label class="form-label">Your Rating:</label>
                                <div class="rating-stars interactive">
                                    @for($i = 1; $i <= 10; $i++)
                                    <i class="fas fa-star star {{ $i <= ($userRating->rating ?? 0) ? 'text-warning active' : 'text-muted' }}" 
                                       data-rating="{{ $i }}"></i>
                                    @endfor
                                    <input type="hidden" name="rating" value="{{ $userRating->rating ?? 0 }}" id="rating-input">
                                </div>
                            </div>
                            <div class="mb-3">
                                <textarea name="review" class="form-control" rows="3" 
                                          placeholder="Write a review (optional)">{{ old('review', $userRating->review ?? '') }}</textarea>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-star me-1"></i>
                                    {{ $userRating ? 'Update Rating' : 'Submit Rating' }}
                                </button>
                                @if($userRating)
                                <form action="{{ route('ratings.destroy', $userRating->id) }}" method="POST" class="d-inline" id="delete-rating-form-{{ $userRating->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-outline-danger" 
                                            onclick="confirmDeleteRating({{ $userRating->id }})">
                                        <i class="fas fa-trash me-1"></i>Remove
                                    </button>
                                </form>
                                @endif
                            </div>
                        </form>
                    </div>
                    @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#authRequiredModal" class="alert-link">Sign in</a> to rate this drama.
                    </div>
                    @endauth
                </div>

                <!-- Watchlist Actions -->
                @auth
                <div class="watchlist-section mb-4">
                    <form action="{{ route('watchlist.store', $drama) }}" method="POST" class="watchlist-form" data-drama-id="{{ $drama->id }}">
                        @csrf
                        <label class="form-label">Add to Watchlist:</label>
                        <div class="d-flex gap-2 flex-wrap">
                            <select name="status" class="form-select" style="width: auto;">
                                <option value="plan_to_watch" {{ $watchlistStatus == 'plan_to_watch' ? 'selected' : '' }}>
                                    Plan to Watch
                                </option>
                                <option value="watching" {{ $watchlistStatus == 'watching' ? 'selected' : '' }}>
                                    Currently Watching
                                </option>
                                <option value="completed" {{ $watchlistStatus == 'completed' ? 'selected' : '' }}>
                                    Completed
                                </option>
                                <option value="on_hold" {{ $watchlistStatus == 'on_hold' ? 'selected' : '' }}>
                                    On Hold
                                </option>
                                <option value="dropped" {{ $watchlistStatus == 'dropped' ? 'selected' : '' }}>
                                    Dropped
                                </option>
                            </select>
                            @if($watchlistStatus)
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-sync me-1"></i>Update
                            </button>
                            <button type="button" class="btn btn-outline-danger" 
                                    onclick="confirmRemoveWatchlist('{{ $drama->slug }}')">
                                <i class="fas fa-times me-1"></i>Remove
                            </button>
                            @else
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Add to Watchlist
                            </button>
                            @endif
                        </div>
                    </form>
                </div>
                @endauth

                <!-- Drama Details -->
                <div class="drama-details">
                    <div class="row">
                        <div class="col-sm-6">
                            <p><strong>Episodes:</strong> {{ $drama->episodes }}</p>
                            <p><strong>Duration:</strong> {{ $drama->duration ? $drama->duration . ' min' : 'N/A' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p><strong>Type:</strong> {{ ucfirst($drama->type) }}</p>
                            <p><strong>Status:</strong> {{ ucfirst($drama->status) }}</p>
                        </div>
                    </div>
                    
                    <div class="genres mb-3">
                        <strong>Genres:</strong>
                        @foreach($drama->genres as $genre)
                        <span class="badge bg-primary bg-opacity-10 text-primary me-1">{{ $genre->name }}</span>
                        @endforeach
                    </div>

                    <!-- Social Sharing -->
                    <div class="social-sharing mb-4">
                        <label class="form-label mb-2">Share this drama:</label>
                        <div class="d-flex gap-2 flex-wrap">
                            <!-- Facebook -->
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('dramas.show', $drama->slug)) }}"
                               target="_blank" class="btn btn-outline-primary btn-sm social-share">
                                <i class="fab fa-facebook-f me-1"></i>Facebook
                            </a>
                            
                            <!-- Twitter -->
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('dramas.show', $drama->slug)) }}&text={{ urlencode("Check out {$drama->title} on DramaVault") }}"
                               target="_blank" class="btn btn-outline-info btn-sm social-share">
                                <i class="fab fa-twitter me-1"></i>Twitter
                            </a>
                            
                            <!-- WhatsApp -->
                            <a href="https://wa.me/?text={{ urlencode("Check out {$drama->title} on DramaVault: " . route('dramas.show', $drama->slug)) }}"
                               target="_blank" class="btn btn-outline-success btn-sm social-share">
                                <i class="fab fa-whatsapp me-1"></i>WhatsApp
                            </a>
                            
                            <!-- Email -->
                            <a href="mailto:?subject=Check out {{ $drama->title }}&body=Check out {{ $drama->title }} on DramaVault: {{ route('dramas.show', $drama->slug) }}"
                               class="btn btn-outline-secondary btn-sm social-share">
                                <i class="fas fa-envelope me-1"></i>Email
                            </a>
                            
                            <!-- Copy Link -->
                            <button class="btn btn-outline-dark btn-sm" onclick="copyToClipboard('{{ route('dramas.show', $drama->slug) }}')">
                                <i class="fas fa-link me-1"></i>Copy Link
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Synopsis -->
    <div class="row mb-5 animate__animated animate__fadeInUp">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0"><i class="fas fa-book-open me-2"></i>Synopsis</h5>
                </div>
                <div class="card-body">
                    <p class="card-text" style="line-height: 1.8; font-size: 1.1rem;">{{ $drama->synopsis }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Cast Section -->
    <div class="row mb-5 animate__animated animate__fadeInUp">
        <div class="col-12">
            <h3 class="fw-bold mb-4">Cast</h3>
            <div class="row g-3">
                @foreach($drama->cast as $castMember)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <a href="{{ route('casts.show', $castMember->slug) }}" class="text-decoration-none">
                        <div class="cast-card card h-100 border-0 shadow-sm hover-lift">
                            <div class="card-body text-center">
                                <img src="{{ $castMember->image_url }}" 
                                     alt="{{ $castMember->name }}" 
                                     class="rounded-circle mb-3" 
                                     style="width: 100px; height: 100px; object-fit: cover;">
                                <h6 class="card-title fw-bold text-dark">{{ $castMember->name }}</h6>
                                <p class="card-text text-muted small">{{ $castMember->pivot->character_name }}</p>
                                <span class="badge bg-secondary">{{ ucfirst($castMember->pivot->role_type) }}</span>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="row animate__animated animate__fadeInUp">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-comments me-2"></i>Reviews & Comments
                        <span class="badge bg-primary ms-2">{{ $drama->allComments->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @auth
                    <!-- Comment Form -->
                    <form action="{{ route('comments.store') }}" method="POST" class="comment-form mb-4">
                        @csrf
                        <input type="hidden" name="drama_id" value="{{ $drama->id }}">
                        <div class="mb-3">
                            <textarea name="body" class="form-control" rows="3" 
                                      placeholder="Share your thoughts about this drama..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i>Post Comment
                        </button>
                    </form>
                    @else
                    <div class="alert alert-info text-center">
                        <i class="fas fa-comments me-2"></i>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#authRequiredModal" class="alert-link">Sign in</a> to join the discussion.
                    </div>
                    @endauth

                    <!-- Reviews (Ratings with text) -->
                    @if($drama->ratings->where('review', '!=', null)->count() > 0)
                    <div class="reviews-section mb-4">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-star text-warning me-2"></i>User Reviews
                        </h6>
                        @foreach($drama->ratings->where('review', '!=', null) as $rating)
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $rating->user->avatar }}" 
                                             alt="{{ $rating->user->name }}" 
                                             class="rounded-circle me-2"
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                        <div>
                                            <h6 class="mb-0">{{ $rating->user->name }}</h6>
                                            <small class="text-muted">{{ $rating->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="rating-stars mb-1">
                                            @for($i = 1; $i <= 10; $i++)
                                            <i class="fas fa-star {{ $i <= $rating->rating ? 'text-warning' : 'text-muted' }}" style="font-size: 0.8rem;"></i>
                                            @endfor
                                        </div>
                                        <span class="badge bg-warning text-dark">{{ $rating->rating }}/10</span>
                                    </div>
                                </div>
                                <p class="mb-0">{{ $rating->review }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <!-- Comments List -->
                    <div id="comments-list">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-comments me-2"></i>Comments
                        </h6>
                        @forelse($drama->comments as $comment)
                        @include('partials.comment-new', ['comment' => $comment, 'drama' => $drama])
                        @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-comments fa-3x mb-3"></i>
                            <p>No comments yet. Be the first to share your thoughts!</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Dramas -->
    @if($similarDramas->count() > 0)
    <div class="row mt-5 animate__animated animate__fadeInUp">
        <div class="col-12">
            <h3 class="fw-bold mb-4">You Might Also Like</h3>
            <div class="row g-3">
                @foreach($similarDramas as $related)
                <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                    <a href="{{ route('dramas.show', $related->slug) }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm hover-lift">
                            <img src="{{ $related->poster_url }}" 
                                 class="card-img-top" 
                                 alt="{{ $related->title }}"
                                 style="height: 200px; object-fit: cover;"
                                 onerror="this.onerror=null; this.src='{{ asset('images/default-poster.png') }}';">
                            <div class="card-body p-2">
                                <h6 class="card-title small fw-bold text-dark mb-1 text-truncate">{{ $related->title }}</h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-warning">
                                        <i class="fas fa-star me-1"></i>{{ $related->avg_rating }}
                                    </small>
                                    <small class="text-muted">{{ $related->release_year }}</small>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Delete Rating Form -->
<form id="delete-rating-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Remove Watchlist Form -->
<form id="remove-watchlist-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('styles')
<style>
.rating-stars.interactive .star {
    cursor: pointer;
    transition: all 0.2s ease;
}

.rating-stars.interactive .star:hover,
.rating-stars.interactive .star.hover {
    color: #ffc107 !important;
    transform: scale(1.2);
}

.cast-card:hover {
    transform: translateY(-5px);
    transition: all 0.3s ease;
}

.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.reply-form {
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Like/Dislike button active states */
.like-btn.active {
    background-color: #0d6efd;
    color: white;
    border-color: #0d6efd;
}

.dislike-btn.active {
    background-color: #6c757d;
    color: white;
    border-color: #6c757d;
}

.like-btn:not(.active):hover {
    background-color: #e7f1ff;
}

.dislike-btn:not(.active):hover {
    background-color: #f8f9fa;
}
</style>
@endpush

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        Swal.fire({
            icon: 'success',
            title: 'Link Copied!',
            text: 'Drama link has been copied to clipboard',
            timer: 2000,
            showConfirmButton: false
        });
    }, function(err) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to copy link'
        });
    });
}

function confirmDeleteRating(ratingId) {
    Swal.fire({
        title: 'Remove Rating?',
        text: "Your rating and review will be removed.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, remove it!'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(`delete-rating-form-${ratingId}`);
            form.submit();
        }
    });
}

function confirmRemoveWatchlist(dramaSlug) {
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
            const form = document.getElementById('remove-watchlist-form');
            form.action = `/dramas/${dramaSlug}/watchlist`;
            form.submit();
        }
    });
}

function toggleReplyForm(commentId) {
    const form = document.getElementById('reply-form-' + commentId);
    if (form.style.display === 'none') {
        form.style.display = 'block';
        form.querySelector('textarea').focus();
    } else {
        form.style.display = 'none';
    }
}

function deleteComment(commentId) {
    if (confirm('Are you sure you want to delete this comment?')) {
        fetch(`/comments/${commentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const commentElement = document.getElementById('comment-' + commentId);
                commentElement.style.transition = 'opacity 0.3s';
                commentElement.style.opacity = '0';
                setTimeout(() => {
                    commentElement.remove();
                    location.reload(); // Reload to update comment count
                }, 300);
            } else {
                alert('Failed to delete comment');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the comment');
        });
    }
}

// Interactive rating stars and comment functionality
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.rating-stars.interactive .star');
    
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.getAttribute('data-rating');
            document.getElementById('rating-input').value = rating;
            
            // Update star display
            stars.forEach(s => {
                if (s.getAttribute('data-rating') <= rating) {
                    s.classList.add('text-warning', 'active');
                    s.classList.remove('text-muted');
                } else {
                    s.classList.remove('text-warning', 'active');
                    s.classList.add('text-muted');
                }
            });
        });
    });

    @auth
    // Load user's like/dislike states for all comments
    const commentIds = [];
    document.querySelectorAll('[data-comment-id]').forEach(el => {
        const id = el.getAttribute('data-comment-id');
        if (!commentIds.includes(id)) {
            commentIds.push(id);
        }
    });
    
    if (commentIds.length > 0) {
        fetch(`/comments/user-likes`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ comment_ids: commentIds })
        })
        .then(response => response.json())
        .then(data => {
            // Set active states based on user's likes
            Object.keys(data).forEach(commentId => {
                const status = data[commentId];
                const buttons = document.querySelectorAll(`[data-comment-id="${commentId}"]`);
                
                buttons.forEach(button => {
                    if (button.classList.contains('like-btn') && status === 'like') {
                        button.classList.add('active');
                    } else if (button.classList.contains('dislike-btn') && status === 'dislike') {
                        button.classList.add('active');
                    }
                });
            });
        })
        .catch(error => console.error('Error loading like states:', error));
    }
    @endauth
    
    // Handle like buttons
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', function() {
            if (this.disabled) return;
            
            const commentId = this.getAttribute('data-comment-id');
            const isActive = this.classList.contains('active');
            
            fetch(`/comments/${commentId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const likesCount = this.querySelector('.likes-count');
                    const parentDiv = this.parentElement;
                    const dislikeBtn = parentDiv.querySelector('.dislike-btn[data-comment-id="' + commentId + '"]');
                    const dislikesCount = dislikeBtn.querySelector('.dislikes-count');
                    
                    if (isActive) {
                        // Unlike
                        this.classList.remove('active');
                        likesCount.textContent = parseInt(likesCount.textContent) - 1;
                    } else {
                        // Like
                        this.classList.add('active');
                        likesCount.textContent = parseInt(likesCount.textContent) + 1;
                        
                        // Remove dislike if active
                        if (dislikeBtn.classList.contains('active')) {
                            dislikeBtn.classList.remove('active');
                            dislikesCount.textContent = parseInt(dislikesCount.textContent) - 1;
                        }
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
    
    // Handle dislike buttons
    document.querySelectorAll('.dislike-btn').forEach(button => {
        button.addEventListener('click', function() {
            if (this.disabled) return;
            
            const commentId = this.getAttribute('data-comment-id');
            const isActive = this.classList.contains('active');
            
            fetch(`/comments/${commentId}/dislike`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const dislikesCount = this.querySelector('.dislikes-count');
                    const parentDiv = this.parentElement;
                    const likeBtn = parentDiv.querySelector('.like-btn[data-comment-id="' + commentId + '"]');
                    const likesCount = likeBtn.querySelector('.likes-count');
                    
                    if (isActive) {
                        // Un-dislike
                        this.classList.remove('active');
                        dislikesCount.textContent = parseInt(dislikesCount.textContent) - 1;
                    } else {
                        // Dislike
                        this.classList.add('active');
                        dislikesCount.textContent = parseInt(dislikesCount.textContent) + 1;
                        
                        // Remove like if active
                        if (likeBtn.classList.contains('active')) {
                            likeBtn.classList.remove('active');
                            likesCount.textContent = parseInt(likesCount.textContent) - 1;
                        }
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
    
    // Watchlist form submission with AJAX
    @auth
    const watchlistForm = document.querySelector('.watchlist-form');
    if (watchlistForm) {
        watchlistForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Updating...';
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to update watchlist'
                });
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
    @endauth
});
</script>
@endpush
