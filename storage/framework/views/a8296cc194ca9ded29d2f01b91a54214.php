

<?php $__env->startSection('title', $user->name . ' - DramaVault'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <!-- Profile Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="position-relative">
                                <img src="<?php echo e($user->avatar); ?>" 
                                     alt="<?php echo e($user->name); ?>" 
                                     class="rounded-circle border border-3 border-primary"
                                     style="width: 120px; height: 120px; object-fit: cover;"
                                     id="profile-avatar">
                                <?php if(auth()->id() === $user->id): ?>
                                <button class="btn btn-sm btn-primary rounded-circle position-absolute bottom-0 end-0" 
                                        style="width: 35px; height: 35px;"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editProfileModal">
                                    <i class="fas fa-camera"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col">
                            <h2 class="mb-1 fw-bold"><?php echo e($user->name); ?></h2>
                            <?php if($user->username): ?>
                            <p class="text-muted mb-2">@<span><?php echo e($user->username); ?></span></p>
                            <?php endif; ?>
                            <?php if($user->bio): ?>
                            <p class="mb-3"><?php echo e($user->bio); ?></p>
                            <?php endif; ?>
                            <div class="d-flex gap-3 mb-3">
                                <div>
                                    <strong><?php echo e($user->ratings_count); ?></strong>
                                    <span class="text-muted">Ratings</span>
                                </div>
                                <div>
                                    <strong><?php echo e($user->comments_count); ?></strong>
                                    <span class="text-muted">Comments</span>
                                </div>
                                <div>
                                    <strong><?php echo e($user->followers_count); ?></strong>
                                    <span class="text-muted">Followers</span>
                                </div>
                                <div>
                                    <strong><?php echo e($user->following_count); ?></strong>
                                    <span class="text-muted">Following</span>
                                </div>
                            </div>
                            
                            <?php if(auth()->id() === $user->id): ?>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                <i class="fas fa-edit me-2"></i>Edit Profile
                            </button>
                            <?php else: ?>
                            <form action="<?php echo e(route('users.follow', $user)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php if(auth()->user()->following->contains($user)): ?>
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="fas fa-user-minus me-2"></i>Unfollow
                                </button>
                                <?php else: ?>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-user-plus me-2"></i>Follow
                                </button>
                                <?php endif; ?>
                            </form>
                            <?php endif; ?>
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
                <i class="fas fa-star me-2"></i>My Reviews (<?php echo e($user->ratings_count); ?>)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="comments-tab" data-bs-toggle="tab" data-bs-target="#comments" type="button">
                <i class="fas fa-comments me-2"></i>My Comments (<?php echo e($user->comments_count); ?>)
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="profileTabsContent">
        <!-- Reviews Tab -->
        <div class="tab-pane fade show active" id="reviews" role="tabpanel">
            <?php if($ratings->count() > 0): ?>
            <div class="row g-3">
                <?php $__currentLoopData = $ratings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rating): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex gap-3">
                                <a href="<?php echo e(route('dramas.show', $rating->drama->slug)); ?>">
                                    <img src="<?php echo e($rating->drama->poster_url); ?>" 
                                         alt="<?php echo e($rating->drama->title); ?>"
                                         class="rounded"
                                         style="width: 80px; height: 120px; object-fit: cover;"
                                         onerror="this.src='<?php echo e(asset('images/default-poster.png')); ?>'">
                                </a>
                                <div class="flex-grow-1">
                                    <h6 class="mb-2">
                                        <a href="<?php echo e(route('dramas.show', $rating->drama->slug)); ?>" 
                                           class="text-dark text-decoration-none">
                                            <?php echo e($rating->drama->title); ?>

                                        </a>
                                    </h6>
                                    <div class="mb-2">
                                        <?php for($i = 1; $i <= 10; $i++): ?>
                                        <i class="fas fa-star <?php echo e($i <= $rating->rating ? 'text-warning' : 'text-muted'); ?>"></i>
                                        <?php endfor; ?>
                                        <span class="ms-2 fw-bold"><?php echo e($rating->rating); ?>/10</span>
                                    </div>
                                    <?php if($rating->review): ?>
                                    <p class="text-muted small mb-2"><?php echo e(Str::limit($rating->review, 100)); ?></p>
                                    <?php endif; ?>
                                    <small class="text-muted"><?php echo e($rating->created_at->diffForHumans()); ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-star fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No ratings yet</h5>
                <?php if(auth()->id() === $user->id): ?>
                <a href="<?php echo e(route('dramas.index')); ?>" class="btn btn-primary mt-3">
                    <i class="fas fa-search me-2"></i>Explore Dramas
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Comments Tab -->
        <div class="tab-pane fade" id="comments" role="tabpanel">
            <?php
                $comments = $user->comments()->with(['drama', 'news'])->latest()->paginate(10);
            ?>
            
            <?php if($comments->count() > 0): ?>
            <div class="list-group">
                <?php $__currentLoopData = $comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="list-group-item border-0 shadow-sm mb-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="mb-0">
                            <?php if($comment->drama_id): ?>
                            <i class="fas fa-film text-primary me-2"></i>
                            <a href="<?php echo e(route('dramas.show', $comment->drama->slug)); ?>" class="text-dark">
                                <?php echo e($comment->drama->title); ?>

                            </a>
                            <?php elseif($comment->news_id): ?>
                            <i class="fas fa-newspaper text-info me-2"></i>
                            <a href="<?php echo e(route('news.show', $comment->news->slug)); ?>" class="text-dark">
                                <?php echo e($comment->news->title); ?>

                            </a>
                            <?php endif; ?>
                        </h6>
                        <small class="text-muted"><?php echo e($comment->created_at->diffForHumans()); ?></small>
                    </div>
                    <p class="mb-0"><?php echo e($comment->body); ?></p>
                    <div class="mt-2">
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-thumbs-up me-1"></i><?php echo e($comment->likes_count ?? 0); ?>

                        </span>
                        <span class="badge bg-light text-dark ms-1">
                            <i class="fas fa-thumbs-down me-1"></i><?php echo e($comment->dislikes_count ?? 0); ?>

                        </span>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php echo e($comments->links()); ?>

            <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-comments fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No comments yet</h5>
                <?php if(auth()->id() === $user->id): ?>
                <p class="text-muted">Start engaging with the community!</p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<?php if(auth()->id() === $user->id): ?>
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-edit me-2"></i>Edit Profile
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('users.update', $user)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <!-- Avatar Upload -->
                    <div class="text-center mb-4">
                        <img src="<?php echo e($user->avatar); ?>" 
                             alt="<?php echo e($user->name); ?>" 
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
                               class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="name" 
                               name="name" 
                               value="<?php echo e(old('name', $user->name)); ?>" 
                               required>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text">@</span>
                            <input type="text" 
                                   class="form-control <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="username" 
                                   name="username" 
                                   value="<?php echo e(old('username', $user->username)); ?>" 
                                   required>
                        </div>
                        <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" 
                               class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="email" 
                               name="email" 
                               value="<?php echo e(old('email', $user->email)); ?>" 
                               required>
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Bio -->
                    <div class="mb-3">
                        <label for="bio" class="form-label">Bio</label>
                        <textarea class="form-control <?php $__errorArgs = ['bio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  id="bio" 
                                  name="bio" 
                                  rows="3" 
                                  maxlength="1000"><?php echo e(old('bio', $user->bio)); ?></textarea>
                        <small class="text-muted"><?php echo e(strlen($user->bio ?? '')); ?>/1000 characters</small>
                        <?php $__errorArgs = ['bio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Theme Preference -->
                    <div class="mb-3">
                        <label for="theme_preference" class="form-label">Theme Preference</label>
                        <select class="form-select <?php $__errorArgs = ['theme_preference'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                id="theme_preference" 
                                name="theme_preference">
                            <option value="light" <?php echo e(old('theme_preference', $user->theme_preference) === 'light' ? 'selected' : ''); ?>>
                                Light
                            </option>
                            <option value="dark" <?php echo e(old('theme_preference', $user->theme_preference) === 'dark' ? 'selected' : ''); ?>>
                                Dark
                            </option>
                        </select>
                        <?php $__errorArgs = ['theme_preference'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
<?php if(session('success')): ?>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '<?php echo e(session('success')); ?>',
        timer: 2000,
        showConfirmButton: false
    });
<?php endif; ?>

<?php if(session('error')): ?>
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '<?php echo e(session('error')); ?>',
        timer: 2000,
        showConfirmButton: false
    });
<?php endif; ?>

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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\XAMPP\htdocs\DramaVault\resources\views/users/show.blade.php ENDPATH**/ ?>