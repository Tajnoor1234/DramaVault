

<?php $__env->startSection('title', 'Admin Dashboard - DramaVault'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-5 fw-bold">
                <i class="fas fa-crown me-2 text-warning"></i>Admin Dashboard
            </h1>
            <p class="text-muted">Manage your DramaVault platform</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Dramas -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 p-3 rounded">
                                <i class="fas fa-film fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Drama/Movies</h6>
                            <h3 class="fw-bold mb-0"><?php echo e($stats['total_dramas'] ?? 0); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                <i class="fas fa-users fa-2x text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Users</h6>
                            <h3 class="fw-bold mb-0"><?php echo e($stats['total_users'] ?? 0); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Ratings -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 p-3 rounded">
                                <i class="fas fa-star fa-2x text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Ratings</h6>
                            <h3 class="fw-bold mb-0"><?php echo e($stats['total_ratings'] ?? 0); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Comments -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 p-3 rounded">
                                <i class="fas fa-comments fa-2x text-info"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Comments</h6>
                            <h3 class="fw-bold mb-0"><?php echo e($stats['total_comments'] ?? 0); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Quick Actions -->
    <div class="row">
        <!-- Recent Drama/Movies -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card shadow h-100 animate__animated animate__fadeInUp">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Drama/Movies</h6>
                    <div>
                        <a href="<?php echo e(route('admin.dramas.index')); ?>" class="btn btn-sm btn-outline-primary me-2">
                            <i class="fas fa-list me-1"></i>Show All
                        </a>
                        <a href="<?php echo e(route('admin.import.index')); ?>" class="btn btn-sm btn-success me-2">
                            <i class="fas fa-cloud-download-alt me-1"></i>Import
                        </a>
                        <a href="<?php echo e(route('admin.dramas.create')); ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i>Add New
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Rating</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $recentDramas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $drama): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo e(route('dramas.show', $drama->slug)); ?>" class="text-decoration-none">
                                            <?php echo e(Str::limit($drama->title, 30)); ?>

                                        </a>
                                    </td>
                                    <td><span class="badge bg-secondary"><?php echo e(ucfirst($drama->type)); ?></span></td>
                                    <td>
                                        <span class="text-warning">
                                            <i class="fas fa-star me-1"></i><?php echo e($drama->avg_rating); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?php echo e(route('admin.dramas.edit', $drama)); ?>" 
                                               class="btn btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger delete-drama" 
                                                    data-id="<?php echo e($drama->id); ?>" 
                                                    data-title="<?php echo e($drama->title); ?>" 
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card shadow h-100 animate__animated animate__fadeInUp">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="<?php echo e(route('admin.dramas.create')); ?>" class="btn btn-primary w-100 h-100 py-3 text-start">
                                <i class="fas fa-plus-circle fa-2x mb-2"></i>
                                <h6>Add New Drama/Movie</h6>
                                <small class="text-white-50">Create a new drama/movie entry</small>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="<?php echo e(route('admin.casts.create')); ?>" class="btn btn-success w-100 h-100 py-3 text-start">
                                <i class="fas fa-user-plus fa-2x mb-2"></i>
                                <h6>Add Cast Member</h6>
                                <small class="text-white-50">Add new actor/actress</small>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="<?php echo e(route('admin.news.index')); ?>" class="btn btn-info w-100 h-100 py-3 text-start">
                                <i class="fas fa-newspaper fa-2x mb-2"></i>
                                <h6>Manage News</h6>
                                <small class="text-white-50">View, create, or import news</small>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-warning w-100 h-100 py-3 text-start">
                                <i class="fas fa-users-cog fa-2x mb-2"></i>
                                <h6>Manage Users</h6>
                                <small class="text-white-50">View and manage all users</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Ratings Chart -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card shadow h-100 animate__animated animate__fadeInUp">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold text-primary">Activity Over Last 6 Months</h6>
                </div>
                <div class="card-body" style="height: 300px;">
                    <canvas id="ratingsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Drama Types -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow h-100 animate__animated animate__fadeInUp">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Contents Types Distribution</h6>
                    <span class="badge bg-primary"><?php echo e($stats['total_dramas'] ?? 0); ?> Total</span>
                </div>
                <div class="card-body" style="height: 300px;">
                    <canvas id="dramaTypesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ratings Chart with Real Data
    const ratingsCtx = document.getElementById('ratingsChart').getContext('2d');
    const ratingsChart = new Chart(ratingsCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($ratingsLabels); ?>,
            datasets: [{
                label: 'Ratings Given',
                data: <?php echo json_encode($ratingsData); ?>,
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'New Users',
                data: <?php echo json_encode($usersData); ?>,
                borderColor: '#1cc88a',
                backgroundColor: 'rgba(28, 200, 138, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Drama Types Chart with Real Data
    const typesCtx = document.getElementById('dramaTypesChart').getContext('2d');
    const dramaCount = <?php echo e($stats['drama_count'] ?? 0); ?>;
    const movieCount = <?php echo e($stats['movie_count'] ?? 0); ?>;
    const seriesCount = <?php echo e($stats['series_count'] ?? 0); ?>;
    const totalCount = dramaCount + movieCount + seriesCount;

    const typesChart = new Chart(typesCtx, {
        type: 'doughnut',
        data: {
            labels: ['Drama', 'Movie', 'Series'],
            datasets: [{
                data: [dramaCount, movieCount, seriesCount],
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const percentage = totalCount > 0 ? ((value / totalCount) * 100).toFixed(1) : 0;
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Delete Drama with SweetAlert2
    document.querySelectorAll('.delete-drama').forEach(button => {
        button.addEventListener('click', function() {
            const dramaId = this.dataset.id;
            const dramaTitle = this.dataset.title;
            
            Swal.fire({
                title: 'Delete Drama?',
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
                    fetch(`/admin/dramas/${dramaId}`, {
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
                            Swal.fire('Error!', data.message || 'Failed to delete drama', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error!', 'An error occurred while deleting the drama', 'error');
                    });
                }
            });
        });
    });
});

</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\XAMPP\htdocs\DramaVault\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>