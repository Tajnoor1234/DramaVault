<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand fw-bold fs-3" href="{{ route('home') }}">
            <i class="fas fa-film me-2"></i>DramaVault
        </a>
        
        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="fas fa-home me-1"></i>Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dramas.*') ? 'active' : '' }}" href="{{ route('dramas.index') }}">
                        <i class="fas fa-film me-1"></i>Drama/Movies
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('news.*') ? 'active' : '' }}" href="{{ route('news.index') }}">
                        <i class="fas fa-newspaper me-1"></i>News
                    </a>
                </li>
                
                <!-- User Dropdown with Auth Check -->
                <li class="nav-item dropdown">
                    @auth
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('users.*', 'watchlist.*', 'profile.*') ? 'active' : '' }}" 
                       href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user me-1"></i>User
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('users.show', auth()->user()) }}">
                                <i class="fas fa-user-circle me-2"></i>My Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('watchlist.index') }}">
                                <i class="fas fa-bookmark me-2"></i>Watchlist
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <h6 class="dropdown-header">Activity</h6>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('users.show', auth()->user()) }}#comments">
                                <i class="fas fa-comments me-2"></i>My Comments
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('users.show', auth()->user()) }}#reviews">
                                <i class="fas fa-star me-2"></i>My Reviews
                            </a>
                        </li>
                    </ul>
                    @else
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#authRequiredModal">
                        <i class="fas fa-user me-1"></i>User
                    </a>
                    @endauth
                </li>
            </ul>
            
            <!-- Right Navigation -->
            <ul class="navbar-nav ms-auto">
                <!-- Search -->
                <li class="nav-item me-2">
                    <form action="{{ route('dramas.index') }}" method="GET" class="d-flex">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-sm" 
                                   placeholder="Search dramas/movies..." value="{{ request('search') }}">
                            <button class="btn btn-outline-light btn-sm" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </li>
                
                <!-- Theme Toggle -->
                <li class="nav-item me-2">
                    <button class="btn btn-outline-light btn-sm" onclick="toggleTheme()">
                        <i id="themeIcon" class="fas {{ (auth()->check() && auth()->user()->theme_preference === 'dark') ? 'fa-sun' : 'fa-moon' }}"></i>
                    </button>
                </li>
                
                @auth
                <!-- Account Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                        <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="rounded-circle me-2" width="32" height="32">
                        {{ auth()->user()->username }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('users.show', auth()->user()) }}">
                                <i class="fas fa-user me-2"></i>View Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-cog me-2"></i>Settings
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                @else
                <!-- Guest Menu -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt me-1"></i>Login
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-light text-primary ms-2" href="{{ route('register') }}">
                        <i class="fas fa-user-plus me-1"></i>Register
                    </a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- Floating Admin Lock Icon -->
@php
    $isAdmin = auth()->check() && auth()->user()->role === 'admin';
@endphp
<a href="{{ $isAdmin ? route('admin.dashboard') : route('login', ['redirect' => 'admin']) }}" 
   class="floating-admin-btn btn btn-dark shadow-lg" 
   title="{{ $isAdmin ? 'Admin Dashboard' : 'Admin Login' }}"
   data-bs-toggle="tooltip"
   data-bs-placement="left">
    <i class="fas fa-lock"></i>
</a>

<!-- Auth Required Modal -->
<div class="modal fade" id="authRequiredModal" tabindex="-1" aria-labelledby="authRequiredModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="authRequiredModalLabel">
                    <i class="fas fa-lock me-2"></i>Authentication Required
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-user-lock fa-4x text-primary mb-3"></i>
                <h5 class="mb-3">Sign In Required</h5>
                <p class="text-muted mb-4">
                    To access comments, reviews, follows, and watchlist features, you need to sign in or create an account.
                </p>
                <div class="d-grid gap-2">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i>Sign In
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Create Account
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.floating-admin-btn {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    z-index: 1000;
    transition: all 0.3s ease;
    border: 3px solid #fff;
}

.floating-admin-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3) !important;
    background-color: #000 !important;
}

.floating-admin-btn i {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}
</style>

<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Auth required for protected actions
        @guest
        document.querySelectorAll('.auth-required').forEach(function(element) {
            element.addEventListener('click', function(e) {
                e.preventDefault();
                var modal = new bootstrap.Modal(document.getElementById('authRequiredModal'));
                modal.show();
            });
        });
        @endguest
    });
</script>
