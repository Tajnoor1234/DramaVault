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
                        <input type="hidden" name="drama_id" value="{{ $drama->id }}">
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
                            @include('partials.drama-comment-reply', ['reply' => $reply, 'drama' => $drama, 'depth' => 1])
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
