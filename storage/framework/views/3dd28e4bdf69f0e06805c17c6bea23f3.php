<!DOCTYPE html>
<html lang="en" data-theme="<?php echo e(auth()->check() ? auth()->user()->theme_preference : 'light'); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'DramaVault - Your Ultimate Drama & Movie Database'); ?></title>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <!-- Vite Assets -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js']); ?>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <?php echo $__env->make('partials.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <!-- Flash Messages -->
    <?php if(session('success') || session('error') || session('warning') || session('info')): ?>
    <div class="container mt-3">
        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <?php if(session('warning')): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i><?php echo e(session('warning')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <?php if(session('info')): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i><?php echo e(session('info')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <!-- Main Content -->
    <main class="min-vh-100">
        <?php echo $__env->yieldContent('content'); ?>
    </main>
    
    <!-- Footer -->
    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <!-- Cookie Consent -->
    <?php echo $__env->make('partials.cookie-consent', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <!-- Admin Login Floating Button (Only for logged out users) -->
    <?php if(auth()->guard()->guest()): ?>
    <div class="position-fixed bottom-0 end-0 m-4" style="z-index: 1000;">
        <button type="button" class="btn btn-dark rounded-circle shadow" 
                onclick="showAdminLogin()"
                data-bs-toggle="tooltip" 
                data-bs-placement="left" 
                title="Admin Login"
                style="width: 50px; height: 50px;">
            <i class="fas fa-lock"></i>
        </button>
    </div>
    <?php endif; ?>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
        
        // Session Management
        (function() {
            // Check session timeout (30 minutes of inactivity)
            const SESSION_TIMEOUT = 30 * 60 * 1000; // 30 minutes
            let lastActivity = Date.now();
            
            function updateActivity() {
                lastActivity = Date.now();
                sessionStorage.setItem('last_activity', lastActivity);
            }
            
            function checkSession() {
                <?php if(auth()->guard()->check()): ?>
                const now = Date.now();
                if (now - lastActivity > SESSION_TIMEOUT) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Session Expired',
                        text: 'Your session has expired due to inactivity. Please log in again.',
                        confirmButtonText: 'Log In'
                    }).then(() => {
                        window.location.href = '<?php echo e(route('login')); ?>';
                    });
                }
                <?php endif; ?>
            }
            
            // Track user activity
            ['mousedown', 'keydown', 'scroll', 'touchstart'].forEach(event => {
                document.addEventListener(event, updateActivity, true);
            });
            
            // Check session every minute
            setInterval(checkSession, 60000);
            
            // Initialize last activity
            updateActivity();
        })();
        
        // Theme Toggle
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-theme', newTheme);
            
            // Save to database if user is logged in
            if (<?php echo json_encode(auth()->check(), 15, 512) ?>) {
                fetch('<?php echo e(route("profile.update")); ?>', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify({
                        theme_preference: newTheme,
                        _method: 'PUT'
                    })
                });
            }
            
            // Update icon
            const themeIcon = document.getElementById('themeIcon');
            themeIcon.className = newTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        }
        
        // Auto-hide alerts
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
            
            // Initialize tooltips
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        });
        
        // Admin Login Modal
        function showAdminLogin() {
            Swal.fire({
                title: '<i class="fas fa-shield-alt text-warning me-2"></i>Admin Login',
                html: `
                    <div class="text-start">
                        <div class="alert alert-warning small">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            <strong>Administrators Only!</strong> Regular users cannot login here.
                        </div>
                        <form id="adminLoginForm">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="adminEmail" placeholder="admin@example.com" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" id="adminPassword" required>
                            </div>
                        </form>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-sign-in-alt me-2"></i>Login as Admin',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#198754',
                preConfirm: () => {
                    const email = document.getElementById('adminEmail').value;
                    const password = document.getElementById('adminPassword').value;
                    
                    if (!email || !password) {
                        Swal.showValidationMessage('Please enter both email and password');
                        return false;
                    }
                    
                    return { email, password };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit admin login form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '<?php echo e(route('admin.login')); ?>';
                    
                    const emailInput = document.createElement('input');
                    emailInput.type = 'hidden';
                    emailInput.name = 'email';
                    emailInput.value = result.value.email;
                    
                    const passwordInput = document.createElement('input');
                    passwordInput.type = 'hidden';
                    passwordInput.name = 'password';
                    passwordInput.value = result.value.password;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '<?php echo e(csrf_token()); ?>';
                    
                    form.appendChild(emailInput);
                    form.appendChild(passwordInput);
                    form.appendChild(csrfToken);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
    
    <!-- AI Chatbot -->
    <?php echo $__env->make('partials.chatbot', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH E:\XAMPP\htdocs\DramaVault\resources\views/layouts/app.blade.php ENDPATH**/ ?>