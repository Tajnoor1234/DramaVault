

<?php $__env->startSection('title', 'Drama News - DramaVault'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <!-- Header -->
    <div class="row mb-5">
        <div class="col-12">
            <h1 class="fw-bold" data-aos="fade-up">
                <i class="fas fa-newspaper me-2"></i>Drama News
            </h1>
            <p class="text-muted" data-aos="fade-up" data-aos-delay="100">
                Stay updated with the latest drama news and updates
            </p>
        </div>
    </div>

    <!-- Featured News -->
    <?php if($featuredNews->count() > 0): ?>
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="fw-bold mb-4" data-aos="fade-up">Featured Stories</h3>
        </div>
        <?php $__currentLoopData = $featuredNews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $featured): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="<?php echo e($loop->index * 100); ?>">
            <div class="card news-card h-100 shadow-sm border-0">
                <div class="position-relative">
                    <img src="<?php echo e($featured->image_url); ?>" class="card-img-top" alt="<?php echo e($featured->title); ?>" style="height: 200px; object-fit: cover;">
                    <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2">
                        <i class="fas fa-star me-1"></i>Featured
                    </span>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-primary me-2"><?php echo e($featured->category); ?></span>
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i><?php echo e($featured->published_at->diffForHumans()); ?>

                        </small>
                    </div>
                    <h5 class="card-title fw-bold"><?php echo e($featured->title); ?></h5>
                    <p class="card-text text-muted"><?php echo e(Str::limit($featured->excerpt, 120)); ?></p>
                    <a href="<?php echo e(route('news.show', $featured->slug)); ?>" class="btn btn-outline-primary btn-sm">
                        Read More <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    <!-- All News -->
    <div class="row">
        <div class="col-12">
            <h3 class="fw-bold mb-4" data-aos="fade-up">Latest News</h3>
        </div>
        <?php $__empty_1 = true; $__currentLoopData = $news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="<?php echo e($loop->index % 4 * 50); ?>">
            <div class="card news-card h-100 shadow-sm border-0">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="<?php echo e($article->image_url); ?>" class="img-fluid rounded-start h-100" alt="<?php echo e($article->title); ?>" style="object-fit: cover;">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-primary me-2"><?php echo e($article->category); ?></span>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i><?php echo e($article->published_at->diffForHumans()); ?>

                                </small>
                            </div>
                            <h6 class="card-title fw-bold"><?php echo e(Str::limit($article->title, 60)); ?></h6>
                            <p class="card-text text-muted small"><?php echo e(Str::limit($article->excerpt, 100)); ?></p>
                            <a href="<?php echo e(route('news.show', $article->slug)); ?>" class="btn btn-primary btn-sm">
                                Read More
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12 text-center py-5" data-aos="fade-up">
            <i class="fas fa-newspaper fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No news articles yet</h4>
            <p class="text-muted">Check back soon for the latest drama news!</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if($news->hasPages()): ?>
    <div class="row mt-5">
        <div class="col-12">
            <nav aria-label="News pagination" data-aos="fade-up">
                <?php echo e($news->links('vendor.pagination.custom')); ?>

            </nav>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.news-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.news-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\XAMPP\htdocs\DramaVault\resources\views/news/index.blade.php ENDPATH**/ ?>