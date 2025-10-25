<div class="d-flex mb-3" id="comment-<?php echo e($reply->id); ?>">
    <div class="flex-shrink-0">
        <img src="<?php echo e($reply->user->avatar_path ? asset('storage/' . $reply->user->avatar_path) : 'https://ui-avatars.com/api/?name=' . urlencode($reply->user->name)); ?>" 
             alt="<?php echo e($reply->user->name); ?>" 
             class="rounded-circle" 
             style="width: <?php echo e($depth > 1 ? '32' : '36'); ?>px; height: <?php echo e($depth > 1 ? '32' : '36'); ?>px;">
    </div>
    <div class="flex-grow-1 ms-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <strong><?php echo e($reply->user->name); ?></strong>
                <?php if($reply->user->role === 'admin'): ?>
                    <span class="badge bg-danger ms-1">Admin</span>
                <?php endif; ?>
                <small class="text-muted ms-2">
                    <i class="fas fa-clock me-1"></i><?php echo e($reply->created_at->diffForHumans()); ?>

                </small>
            </div>
            <?php if(auth()->guard()->check()): ?>
                <?php if(auth()->id() === $reply->user_id || auth()->user()->role === 'admin'): ?>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-link text-muted" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <button class="dropdown-item text-danger" 
                                        onclick="deleteComment(<?php echo e($reply->id); ?>)">
                                    <i class="fas fa-trash me-2"></i>Delete
                                </button>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <p class="mb-2"><?php echo e($reply->body); ?></p>
        
        <!-- Reply Actions -->
        <div class="d-flex gap-2 mb-2">
            <button class="btn btn-sm btn-outline-primary like-btn" 
                    data-comment-id="<?php echo e($reply->id); ?>"
                    <?php echo e(auth()->check() ? '' : 'disabled'); ?>>
                <i class="fas fa-thumbs-up me-1"></i>
                <span class="likes-count"><?php echo e($reply->likes_count); ?></span>
            </button>
            <button class="btn btn-sm btn-outline-secondary dislike-btn" 
                    data-comment-id="<?php echo e($reply->id); ?>"
                    <?php echo e(auth()->check() ? '' : 'disabled'); ?>>
                <i class="fas fa-thumbs-down me-1"></i>
                <span class="dislikes-count"><?php echo e($reply->dislikes_count); ?></span>
            </button>
            <?php if(auth()->guard()->check()): ?>
            <?php if($depth < 3): ?>
            <button class="btn btn-sm btn-outline-info" 
                    onclick="toggleReplyForm(<?php echo e($reply->id); ?>)">
                <i class="fas fa-reply me-1"></i>Reply
            </button>
            <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- Reply Form for nested reply -->
        <?php if(auth()->guard()->check()): ?>
        <?php if($depth < 3): ?>
        <div class="reply-form mt-3" id="reply-form-<?php echo e($reply->id); ?>" style="display: none;">
            <form action="<?php echo e(route('comments.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="drama_id" value="<?php echo e($drama->id); ?>">
                <input type="hidden" name="parent_id" value="<?php echo e($reply->id); ?>">
                <div class="input-group">
                    <textarea class="form-control" name="body" rows="2" 
                              placeholder="Write a reply..." required></textarea>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
        <?php endif; ?>
        <?php endif; ?>

        <!-- Nested Replies (Recursive) -->
        <?php if($reply->replies->count() > 0): ?>
            <div class="mt-3 ps-3 border-start border-2">
                <?php $__currentLoopData = $reply->replies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nestedReply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('partials.drama-comment-reply', ['reply' => $nestedReply, 'drama' => $drama, 'depth' => $depth + 1], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH E:\XAMPP\htdocs\DramaVault\resources\views/partials/drama-comment-reply.blade.php ENDPATH**/ ?>