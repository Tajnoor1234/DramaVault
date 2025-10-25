@extends('layouts.app')

@section('title', $news->title . ' - DramaVault')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" data-aos="fade-down">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('news.index') }}">News</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($news->title, 50) }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <article class="news-article" data-aos="fade-up">
                <!-- Header -->
                <div class="mb-4">
                    <span class="badge bg-primary mb-3">{{ $news->category }}</span>
                    <h1 class="fw-bold mb-3">{{ $news->title }}</h1>
                    
                    <div class="d-flex align-items-center text-muted mb-3">
                        <div class="me-4">
                            <i class="fas fa-user me-2"></i>
                            <span>{{ $news->author ? $news->author->name : 'Admin' }}</span>
                        </div>
                        <div class="me-4">
                            <i class="fas fa-clock me-2"></i>
                            <span>{{ $news->published_at->format('F d, Y') }}</span>
                        </div>
                        <div>
                            <i class="fas fa-eye me-2"></i>
                            <span>{{ $news->views_count }} views</span>
                        </div>
                    </div>
                </div>

                <!-- Featured Image -->
                @if($news->image_path)
                <div class="mb-4" data-aos="fade-up" data-aos-delay="100">
                    <img src="{{ $news->image_url }}" alt="{{ $news->title }}" class="img-fluid rounded shadow-sm w-100" style="max-height: 500px; object-fit: cover;">
                </div>
                @endif

                <!-- Excerpt -->
                <div class="lead mb-4 text-muted" data-aos="fade-up" data-aos-delay="200">
                    {{ $news->excerpt }}
                </div>

                <!-- Content -->
                <div class="news-content" data-aos="fade-up" data-aos-delay="300">
                    {!! nl2br(e($news->content)) !!}
                </div>

                <!-- Source Link -->
                @if($news->source_url)
                <div class="alert alert-info mt-4" data-aos="fade-up" data-aos-delay="400">
                    <i class="fas fa-link me-2"></i>
                    <strong>Source:</strong> 
                    <a href="{{ $news->source_url }}" target="_blank" rel="noopener">
                        {{ $news->source ?? 'Original Article' }}
                        <i class="fas fa-external-link-alt ms-1"></i>
                    </a>
                </div>
                @endif

                <!-- Tags/Category -->
                <div class="mt-4 pt-4 border-top" data-aos="fade-up" data-aos-delay="500">
                    <h6 class="fw-bold mb-3">Category</h6>
                    <span class="badge bg-primary">{{ $news->category }}</span>
                </div>

                <!-- Share Buttons -->
                <div class="mt-4 pt-4 border-top" data-aos="fade-up" data-aos-delay="600">
                    <h6 class="fw-bold mb-3">Share this article</h6>
                    <div class="d-flex gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('news.show', $news->slug)) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fab fa-facebook-f me-1"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('news.show', $news->slug)) }}&text={{ urlencode($news->title) }}" target="_blank" class="btn btn-outline-info btn-sm">
                            <i class="fab fa-twitter me-1"></i> Twitter
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('news.show', $news->slug)) }}&title={{ urlencode($news->title) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fab fa-linkedin-in me-1"></i> LinkedIn
                        </a>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="mt-5 pt-4 border-top" data-aos="fade-up" data-aos-delay="700">
                    <h4 class="fw-bold mb-4">
                        <i class="fas fa-comments me-2"></i>Comments
                        <span class="badge bg-primary ms-2">{{ $news->allComments->count() }}</span>
                    </h4>

                    @auth
                    <!-- Comment Form -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('comments.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="news_id" value="{{ $news->id }}">
                                <div class="mb-3">
                                    <label for="comment" class="form-label">Add a comment</label>
                                    <textarea class="form-control @error('body') is-invalid @enderror" 
                                              id="comment" name="body" rows="3" 
                                              placeholder="Share your thoughts..." required></textarea>
                                    @error('body')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i> Post Comment
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Please <a href="{{ route('login') }}">login</a> to leave a comment.
                    </div>
                    @endauth

                    <!-- Comments List -->
                    @if($news->comments->count() > 0)
                        @foreach($news->comments->sortByDesc('created_at') as $comment)
                            <div class="card mb-3" id="comment-{{ $comment->id }}">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <img src="{{ $comment->user->avatar_path ? asset('storage/' . $comment->user->avatar_path) : 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) }}" 
                                                 alt="{{ $comment->user->name }}" 
                                                 class="rounded-circle" 
                                                 style="width: 48px; height: 48px;">
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <strong>{{ $comment->user->name }}</strong>
                                                    @if($comment->user->role === 'admin')
                                                        <span class="badge bg-danger ms-1">Admin</span>
                                                    @endif
                                                    <small class="text-muted ms-2">
                                                        <i class="fas fa-clock me-1"></i>{{ $comment->created_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                                @auth
                                                    @if(auth()->id() === $comment->user_id || auth()->user()->role === 'admin')
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm btn-link text-muted" type="button" data-bs-toggle="dropdown">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li>
                                                                    <button class="dropdown-item text-danger" 
                                                                            onclick="deleteComment({{ $comment->id }})">
                                                                        <i class="fas fa-trash me-2"></i>Delete
                                                                    </button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    @endif
                                                @endauth
                                            </div>
                                            <p class="mb-2">{{ $comment->body }}</p>
                                            
                                            <!-- Comment Actions -->
                                            <div class="d-flex gap-2 mb-2">
                                                <button class="btn btn-sm btn-outline-primary like-btn" 
                                                        data-comment-id="{{ $comment->id }}" 
                                                        {{ auth()->check() ? '' : 'disabled' }}>
                                                    <i class="fas fa-thumbs-up me-1"></i>
                                                    <span class="likes-count">{{ $comment->likes_count }}</span>
                                                </button>
                                                <button class="btn btn-sm btn-outline-secondary dislike-btn" 
                                                        data-comment-id="{{ $comment->id }}"
                                                        {{ auth()->check() ? '' : 'disabled' }}>
                                                    <i class="fas fa-thumbs-down me-1"></i>
                                                    <span class="dislikes-count">{{ $comment->dislikes_count }}</span>
                                                </button>
                                                @auth
                                                <button class="btn btn-sm btn-outline-info" 
                                                        onclick="toggleReplyForm({{ $comment->id }})">
                                                    <i class="fas fa-reply me-1"></i>Reply
                                                </button>
                                                @endauth
                                            </div>
                                            
                                            @if($comment->replies->count() > 0)
                                                <small class="text-muted">
                                                    <i class="fas fa-comments me-1"></i>{{ $comment->replies->count() }} {{ Str::plural('reply', $comment->replies->count()) }}
                                                </small>
                                            @endif

                                            <!-- Reply Form -->
                                            @auth
                                            <div class="reply-form mt-3" id="reply-form-{{ $comment->id }}" style="display: none;">
                                                <form action="{{ route('comments.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="news_id" value="{{ $news->id }}">
                                                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                                    <div class="input-group">
                                                        <textarea class="form-control" name="body" rows="2" 
                                                                  placeholder="Write a reply..." required></textarea>
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fas fa-paper-plane"></i>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                            @endauth

                                            <!-- Replies -->
                                            @if($comment->replies->count() > 0)
                                                <div class="mt-3 ps-3 border-start border-2">
                                                    @foreach($comment->replies as $reply)
                                                        @include('partials.news-comment-reply', ['reply' => $reply, 'news' => $news, 'depth' => 1])
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No comments yet. Be the first to comment!</p>
                        </div>
                    @endif
                </div>
            </article>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Related News -->
            @if($relatedNews->count() > 0)
            <div class="card shadow-sm border-0 mb-4" data-aos="fade-up" data-aos-delay="800">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-newspaper me-2"></i>Related News
                    </h5>
                    @foreach($relatedNews as $related)
                    <div class="mb-3 pb-3 @if(!$loop->last) border-bottom @endif">
                        <div class="row g-2">
                            @if($related->image_path)
                            <div class="col-4">
                                <img src="{{ $related->image_url }}" alt="{{ $related->title }}" class="img-fluid rounded" style="height: 60px; object-fit: cover; width: 100%;">
                            </div>
                            <div class="col-8">
                            @else
                            <div class="col-12">
                            @endif
                                <h6 class="mb-1">
                                    <a href="{{ route('news.show', $related->slug) }}" class="text-decoration-none text-dark">
                                        {{ Str::limit($related->title, 60) }}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>{{ $related->published_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Latest News -->
            @if($latestNews->count() > 0)
            <div class="card shadow-sm border-0" data-aos="fade-up" data-aos-delay="900">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-clock me-2"></i>Latest News
                    </h5>
                    @foreach($latestNews as $latest)
                    <div class="mb-3 pb-3 @if(!$loop->last) border-bottom @endif">
                        <h6 class="mb-1">
                            <a href="{{ route('news.show', $latest->slug) }}" class="text-decoration-none text-dark">
                                {{ Str::limit($latest->title, 60) }}
                            </a>
                        </h6>
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>{{ $latest->published_at->format('M d, Y') }}
                        </small>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.news-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #333;
}

.news-content p {
    margin-bottom: 1.5rem;
}

.news-article img {
    max-width: 100%;
    height: auto;
}

.breadcrumb {
    background-color: transparent;
    padding: 0;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
}

.news-article h1 {
    line-height: 1.3;
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

// Like/Dislike functionality
document.addEventListener('DOMContentLoaded', function() {
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
});
</script>
@endpush

