

<?php $__env->startSection('title', 'Manage Drama/Movies - DramaVault'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="fas fa-film me-2"></i>Manage Drama/Movies
                    </h1>
                    <p class="text-muted">View, edit, and delete all drama/movie entries</p>
                </div>
                <div>
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                    <a href="<?php echo e(route('admin.import.index')); ?>" class="btn btn-success me-2">
                        <i class="fas fa-cloud-download-alt"></i> Import
                    </a>
                    <a href="<?php echo e(route('admin.dramas.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('admin.dramas.index')); ?>" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search by title..." value="<?php echo e(request('search')); ?>">
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="drama" <?php echo e(request('type') == 'drama' ? 'selected' : ''); ?>>Drama</option>
                        <option value="movie" <?php echo e(request('type') == 'movie' ? 'selected' : ''); ?>>Movie</option>
                        <option value="series" <?php echo e(request('type') == 'series' ? 'selected' : ''); ?>>Series</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="upcoming" <?php echo e(request('status') == 'upcoming' ? 'selected' : ''); ?>>Upcoming</option>
                        <option value="ongoing" <?php echo e(request('status') == 'ongoing' ? 'selected' : ''); ?>>Ongoing</option>
                        <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort" class="form-select">
                        <option value="latest" <?php echo e(request('sort') == 'latest' ? 'selected' : ''); ?>>Latest</option>
                        <option value="oldest" <?php echo e(request('sort') == 'oldest' ? 'selected' : ''); ?>>Oldest</option>
                        <option value="title" <?php echo e(request('sort') == 'title' ? 'selected' : ''); ?>>Title A-Z</option>
                        <option value="rating" <?php echo e(request('sort') == 'rating' ? 'selected' : ''); ?>>Rating</option>
                        <option value="views" <?php echo e(request('sort') == 'views' ? 'selected' : ''); ?>>Views</option>
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

    <!-- Drama/Movies List -->
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                All Drama/Movies (<?php echo e($dramas->total()); ?>)
            </h6>
            <span class="badge bg-primary">Page <?php echo e($dramas->currentPage()); ?> of <?php echo e($dramas->lastPage()); ?></span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">Poster</th>
                            <th>Title</th>
                            <th style="width: 100px;">Type</th>
                            <th style="width: 100px;">Status</th>
                            <th style="width: 80px;">Year</th>
                            <th style="width: 100px;">Rating</th>
                            <th style="width: 100px;">Views</th>
                            <th style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $dramas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $drama): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <img src="<?php echo e($drama->poster_url); ?>" alt="<?php echo e($drama->title); ?>" 
                                     class="rounded" style="width: 40px; height: 60px; object-fit: cover;">
                            </td>
                            <td>
                                <a href="<?php echo e(route('dramas.show', $drama->slug)); ?>" 
                                   class="text-decoration-none text-dark fw-semibold" target="_blank">
                                    <?php echo e(Str::limit($drama->title, 40)); ?>

                                </a>
                                <br>
                                <small class="text-muted"><?php echo e($drama->episodes); ?> episodes</small>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo e($drama->type == 'movie' ? 'info' : ($drama->type == 'series' ? 'success' : 'secondary')); ?>">
                                    <?php echo e(ucfirst($drama->type)); ?>

                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo e($drama->status == 'completed' ? 'success' : ($drama->status == 'ongoing' ? 'primary' : 'warning')); ?>">
                                    <?php echo e(ucfirst($drama->status)); ?>

                                </span>
                            </td>
                            <td><?php echo e($drama->release_year); ?></td>
                            <td>
                                <span class="text-warning">
                                    <i class="fas fa-star"></i> <?php echo e(number_format($drama->avg_rating, 1)); ?>

                                </span>
                                <br>
                                <small class="text-muted">(<?php echo e($drama->total_ratings); ?>)</small>
                            </td>
                            <td>
                                <i class="fas fa-eye text-muted"></i> <?php echo e(number_format($drama->total_views)); ?>

                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?php echo e(route('dramas.show', $drama->slug)); ?>" 
                                       class="btn btn-outline-info" 
                                       title="View" 
                                       target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('admin.dramas.edit', $drama->slug)); ?>" 
                                       class="btn btn-outline-primary" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-danger delete-drama" 
                                            data-slug="<?php echo e($drama->slug); ?>" 
                                            data-title="<?php echo e($drama->title); ?>" 
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-film fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No drama/movies found</p>
                                <a href="<?php echo e(route('admin.dramas.create')); ?>" class="btn btn-primary mt-3">
                                    <i class="fas fa-plus"></i> Add Your First Drama/Movie
                                </a>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if($dramas->hasPages()): ?>
        <div class="card-footer d-flex justify-content-center">
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm">
                    
                    <?php if($dramas->onFirstPage()): ?>
                        <li class="page-item disabled">
                            <span class="page-link">Previous</span>
                        </li>
                    <?php else: ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo e($dramas->previousPageUrl()); ?>" rel="prev">Previous</a>
                        </li>
                    <?php endif; ?>

                    
                    <?php $__currentLoopData = range(1, $dramas->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($page == $dramas->currentPage()): ?>
                            <li class="page-item active"><span class="page-link"><?php echo e($page); ?></span></li>
                        <?php else: ?>
                            <li class="page-item"><a class="page-link" href="<?php echo e($dramas->url($page)); ?>"><?php echo e($page); ?></a></li>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    
                    <?php if($dramas->hasMorePages()): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo e($dramas->nextPageUrl()); ?>" rel="next">Next</a>
                        </li>
                    <?php else: ?>
                        <li class="page-item disabled">
                            <span class="page-link">Next</span>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Delete Drama/Movie with SweetAlert2
document.querySelectorAll('.delete-drama').forEach(button => {
    button.addEventListener('click', function() {
        const dramaSlug = this.dataset.slug;
        const dramaTitle = this.dataset.title;
        
        Swal.fire({
            title: 'Delete Drama/Movie?',
            text: `Are you sure you want to delete "${dramaTitle}"? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Send delete request
                fetch(`/admin/dramas/${dramaSlug}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: data.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Error!', data.message || 'Failed to delete drama/movie', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error!', 'An error occurred while deleting the drama/movie', 'error');
                });
            }
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\XAMPP\htdocs\DramaVault\resources\views/admin/dramas/index.blade.php ENDPATH**/ ?>