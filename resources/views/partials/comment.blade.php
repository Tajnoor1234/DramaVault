<div class="comment-card card mb-3 animate__animated animate__fadeIn" id="comment-{{ $comment->id }}">
    <div class="card-body">
        <div class="d-flex align-items-start mb-3">
            <img src="{{ $comment->user->avatar ? asset('storage/' . $comment->user->avatar) : asset('images/default-avatar.jpg') }}" 
                 alt="{{ $comment->user->name }}" class="rounded-circle me-3" width="48" height="48">
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="fw-bold mb-1">{{ $comment->user->name }}</h6>
                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                    </div>
                    @auth
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary border-0" type="button" 
                                data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if(auth()->id() === $comment->user_id || auth()->user()->is_admin)
                            <li>
                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this comment?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-trash me-2"></i>Delete
                                    </button>
                                </form>
                            </li>
                            @endif
                            <li><a class="dropdown-item" href="#"><i class="fas fa-flag me-2"></i>Report</a></li>
                        </ul>
                    </div>
                    @endauth
                </div>
                <p class="mt-2 mb-2">{{ $comment->body }}</p>
                
                <!-- Comment Actions -->
                <div class="comment-actions d-flex gap-2">
                    <button class="btn btn-sm btn-outline-primary like-btn" data-comment-id="{{ $comment->id }}">
                        <i class="fas fa-thumbs-up me-1"></i>
                        <span class="likes-count">{{ $comment->likes_count }}</span>
                    </button>
                    <button class="btn btn-sm btn-outline-secondary dislike-btn" data-comment-id="{{ $comment->id }}">
                        <i class="fas fa-thumbs-down me-1"></i>
                        <span class="dislikes-count">{{ $comment->dislikes_count }}</span>
                    </button>
                    @auth
                    <button class="btn btn-sm btn-outline-info reply-btn" data-comment-id="{{ $comment->id }}">
                        <i class="fas fa-reply me-1"></i>Reply
                    </button>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Reply Form -->
        @auth
        <div class="reply-form ms-5 mt-3" id="reply-form-{{ $comment->id }}" style="display: none;">
            <form action="{{ route('comments.store') }}" method="POST" class="comment-form">
                @csrf
                <input type="hidden" name="drama_id" value="{{ $drama->id }}">
                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                <div class="mb-2">
                    <textarea name="body" class="form-control form-control-sm" rows="2" 
                              placeholder="Write a reply..." required></textarea>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-paper-plane me-1"></i>Post Reply
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm cancel-reply" 
                            data-comment-id="{{ $comment->id }}">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
        @endauth

        <!-- Replies -->
        @if($comment->replies->count() > 0)
        <div class="replies ms-5 mt-3">
            @foreach($comment->replies as $reply)
            <div class="reply-card card bg-light mb-2">
                <div class="card-body py-2">
                    <div class="d-flex align-items-start">
                        <img src="{{ $reply->user->avatar ? asset('storage/' . $reply->user->avatar) : asset('images/default-avatar.jpg') }}" 
                             alt="{{ $reply->user->name }}" class="rounded-circle me-2" width="32" height="32">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong class="small">{{ $reply->user->name }}</strong>
                                    <small class="text-muted ms-2">{{ $reply->created_at->diffForHumans() }}</small>
                                </div>
                                @if(auth()->id() === $reply->user_id || auth()->user()->is_admin)
                                <form action="{{ route('comments.destroy', $reply) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this reply?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                            <p class="small mb-0 mt-1">{{ $reply->body }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>