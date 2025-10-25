<div class="d-flex mb-3" id="comment-{{ $reply->id }}">
    <div class="flex-shrink-0">
        <img src="{{ $reply->user->avatar_path ? asset('storage/' . $reply->user->avatar_path) : 'https://ui-avatars.com/api/?name=' . urlencode($reply->user->name) }}" 
             alt="{{ $reply->user->name }}" 
             class="rounded-circle" 
             style="width: {{ $depth > 1 ? '32' : '36' }}px; height: {{ $depth > 1 ? '32' : '36' }}px;">
    </div>
    <div class="flex-grow-1 ms-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <strong>{{ $reply->user->name }}</strong>
                @if($reply->user->role === 'admin')
                    <span class="badge bg-danger ms-1">Admin</span>
                @endif
                <small class="text-muted ms-2">
                    <i class="fas fa-clock me-1"></i>{{ $reply->created_at->diffForHumans() }}
                </small>
            </div>
            @auth
                @if(auth()->id() === $reply->user_id || auth()->user()->role === 'admin')
                    <div class="dropdown">
                        <button class="btn btn-sm btn-link text-muted" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <button class="dropdown-item text-danger" 
                                        onclick="deleteComment({{ $reply->id }})">
                                    <i class="fas fa-trash me-2"></i>Delete
                                </button>
                            </li>
                        </ul>
                    </div>
                @endif
            @endauth
        </div>
        <p class="mb-2">{{ $reply->body }}</p>
        
        <!-- Reply Actions -->
        <div class="d-flex gap-2 mb-2">
            <button class="btn btn-sm btn-outline-primary like-btn" 
                    data-comment-id="{{ $reply->id }}"
                    {{ auth()->check() ? '' : 'disabled' }}>
                <i class="fas fa-thumbs-up me-1"></i>
                <span class="likes-count">{{ $reply->likes_count }}</span>
            </button>
            <button class="btn btn-sm btn-outline-secondary dislike-btn" 
                    data-comment-id="{{ $reply->id }}"
                    {{ auth()->check() ? '' : 'disabled' }}>
                <i class="fas fa-thumbs-down me-1"></i>
                <span class="dislikes-count">{{ $reply->dislikes_count }}</span>
            </button>
            @auth
            @if($depth < 3)
            <button class="btn btn-sm btn-outline-info" 
                    onclick="toggleReplyForm({{ $reply->id }})">
                <i class="fas fa-reply me-1"></i>Reply
            </button>
            @endif
            @endauth
        </div>

        <!-- Reply Form for nested reply -->
        @auth
        @if($depth < 3)
        <div class="reply-form mt-3" id="reply-form-{{ $reply->id }}" style="display: none;">
            <form action="{{ route('comments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="drama_id" value="{{ $drama->id }}">
                <input type="hidden" name="parent_id" value="{{ $reply->id }}">
                <div class="input-group">
                    <textarea class="form-control" name="body" rows="2" 
                              placeholder="Write a reply..." required></textarea>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
        @endif
        @endauth

        <!-- Nested Replies (Recursive) -->
        @if($reply->replies->count() > 0)
            <div class="mt-3 ps-3 border-start border-2">
                @foreach($reply->replies as $nestedReply)
                    @include('partials.drama-comment-reply', ['reply' => $nestedReply, 'drama' => $drama, 'depth' => $depth + 1])
                @endforeach
            </div>
        @endif
    </div>
</div>
