@extends('layouts.app')

@section('title', 'Session & Cookie Test - DramaVault')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h1 class="mb-4">
                <i class="fas fa-vial text-primary me-2"></i>Session & Cookie Test Dashboard
            </h1>

            <!-- Cookie Consent Status -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-cookie-bite me-2"></i>Cookie Consent Status
                    </h5>
                </div>
                <div class="card-body">
                    <div id="cookieStatus" class="mb-3">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        Checking cookies...
                    </div>
                    <button class="btn btn-primary" onclick="manageCookiePreferences()">
                        <i class="fas fa-cog me-2"></i>Manage Cookie Preferences
                    </button>
                    <button class="btn btn-danger" onclick="clearAllCookies()">
                        <i class="fas fa-trash me-2"></i>Clear Cookies & Test Again
                    </button>
                </div>
            </div>

            <!-- Session Information -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>Session Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Session ID:</strong>
                            <p class="mb-0"><code>{{ session()->getId() }}</code></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Page Views (This Session):</strong>
                            <p class="mb-0"><span class="badge bg-info">{{ session('page_views', 0) }}</span></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Last Activity:</strong>
                            <p class="mb-0">
                                @if(session('last_activity'))
                                    {{ \Carbon\Carbon::createFromTimestamp(session('last_activity'))->diffForHumans() }}
                                @else
                                    <span class="text-muted">Not tracked</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Last Page:</strong>
                            <p class="mb-0 small">
                                @if(session('last_page'))
                                    <a href="{{ session('last_page') }}" target="_blank">{{ session('last_page') }}</a>
                                @else
                                    <span class="text-muted">Not tracked</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    @auth
                    <div class="alert alert-success mt-3">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Logged In As:</strong> {{ auth()->user()->name }} ({{ auth()->user()->email }})
                    </div>
                    @else
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        You are not logged in. <a href="{{ route('login') }}">Login</a> to test authenticated session tracking.
                    </div>
                    @endauth
                </div>
            </div>

            <!-- User Activity Tracking (For Logged In Users) -->
            @auth
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user-clock me-2"></i>User Activity Tracking (Database)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Last Active At:</strong>
                            <p class="mb-0">
                                @if(auth()->user()->last_active_at)
                                    {{ auth()->user()->last_active_at->diffForHumans() }}
                                    <small class="text-muted d-block">{{ auth()->user()->last_active_at->format('M d, Y h:i A') }}</small>
                                @else
                                    <span class="text-muted">Not tracked</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Last Login:</strong>
                            <p class="mb-0">
                                @if(auth()->user()->last_login_at)
                                    {{ auth()->user()->last_login_at->diffForHumans() }}
                                    <small class="text-muted d-block">{{ auth()->user()->last_login_at->format('M d, Y h:i A') }}</small>
                                @else
                                    <span class="text-muted">Not tracked</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Session ID (DB):</strong>
                            <p class="mb-0">
                                @if(auth()->user()->session_id)
                                    <code class="small">{{ auth()->user()->session_id }}</code>
                                @else
                                    <span class="text-muted">Not stored</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Last IP Address:</strong>
                            <p class="mb-0">
                                @if(auth()->user()->last_ip_address)
                                    <code>{{ auth()->user()->last_ip_address }}</code>
                                @else
                                    <span class="text-muted">Not tracked</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <strong>User Agent:</strong>
                            <p class="mb-0 small">
                                @if(auth()->user()->user_agent)
                                    <code>{{ auth()->user()->user_agent }}</code>
                                @else
                                    <span class="text-muted">Not tracked</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <button class="btn btn-success" onclick="location.reload()">
                        <i class="fas fa-sync-alt me-2"></i>Refresh to Update Tracking
                    </button>
                </div>
            </div>
            @endauth

            <!-- Browser Cookies -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-desktop me-2"></i>Browser Cookies (Client-Side)
                    </h5>
                </div>
                <div class="card-body">
                    <div id="browserCookies" class="mb-3">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        Checking browser cookies...
                    </div>
                </div>
            </div>

            <!-- LocalStorage Data -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-database me-2"></i>LocalStorage Data
                    </h5>
                </div>
                <div class="card-body">
                    <div id="localStorageData" class="mb-3">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        Checking localStorage...
                    </div>
                </div>
            </div>

            <!-- Test Actions -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-flask me-2"></i>Test Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" onclick="testSessionWrite()">
                            <i class="fas fa-pen me-2"></i>Test: Write to Session
                        </button>
                        <button class="btn btn-info" onclick="testCookieWrite()">
                            <i class="fas fa-cookie me-2"></i>Test: Set a Cookie
                        </button>
                        <button class="btn btn-success" onclick="location.reload()">
                            <i class="fas fa-sync-alt me-2"></i>Reload Page (Increment Page Views)
                        </button>
                        <button class="btn btn-warning" onclick="testSessionTimeout()">
                            <i class="fas fa-hourglass-half me-2"></i>Test: Check Session Timeout
                        </button>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="text-center">
                <a href="{{ route('home') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Home
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check Cookie Consent Status
    checkCookieConsent();
    
    // Display Browser Cookies
    displayBrowserCookies();
    
    // Display LocalStorage
    displayLocalStorage();
});

function checkCookieConsent() {
    const consent = localStorage.getItem('cookies_consent');
    const analytics = localStorage.getItem('cookies_analytics');
    const marketing = localStorage.getItem('cookies_marketing');
    
    let html = '';
    
    if (consent === 'true') {
        html += '<div class="alert alert-success mb-2">';
        html += '<i class="fas fa-check-circle me-2"></i>';
        html += '<strong>Cookie Consent Given!</strong>';
        html += '</div>';
        
        html += '<div class="row">';
        html += '<div class="col-md-4"><span class="badge bg-success w-100">✓ Necessary Cookies</span></div>';
        html += '<div class="col-md-4"><span class="badge bg-' + (analytics === 'true' ? 'success' : 'secondary') + ' w-100">' + (analytics === 'true' ? '✓' : '✗') + ' Analytics Cookies</span></div>';
        html += '<div class="col-md-4"><span class="badge bg-' + (marketing === 'true' ? 'success' : 'secondary') + ' w-100">' + (marketing === 'true' ? '✓' : '✗') + ' Marketing Cookies</span></div>';
        html += '</div>';
    } else {
        html += '<div class="alert alert-warning">';
        html += '<i class="fas fa-exclamation-triangle me-2"></i>';
        html += '<strong>No Cookie Consent Found!</strong> The cookie banner should appear.';
        html += '</div>';
    }
    
    document.getElementById('cookieStatus').innerHTML = html;
}

function displayBrowserCookies() {
    const cookies = document.cookie.split(';');
    let html = '<table class="table table-sm table-striped">';
    html += '<thead><tr><th>Cookie Name</th><th>Value</th></tr></thead><tbody>';
    
    if (cookies.length > 0 && cookies[0] !== '') {
        cookies.forEach(cookie => {
            const [name, ...valueParts] = cookie.trim().split('=');
            const value = valueParts.join('=');
            html += '<tr><td><code>' + name + '</code></td><td class="small">' + (value.length > 50 ? value.substring(0, 50) + '...' : value) + '</td></tr>';
        });
    } else {
        html += '<tr><td colspan="2" class="text-center text-muted">No cookies found</td></tr>';
    }
    
    html += '</tbody></table>';
    html += '<p class="mb-0 small text-muted"><strong>Total Cookies:</strong> ' + (cookies[0] !== '' ? cookies.length : 0) + '</p>';
    
    document.getElementById('browserCookies').innerHTML = html;
}

function displayLocalStorage() {
    let html = '<table class="table table-sm table-striped">';
    html += '<thead><tr><th>Key</th><th>Value</th></tr></thead><tbody>';
    
    if (localStorage.length > 0) {
        for (let i = 0; i < localStorage.length; i++) {
            const key = localStorage.key(i);
            const value = localStorage.getItem(key);
            html += '<tr><td><code>' + key + '</code></td><td>' + value + '</td></tr>';
        }
    } else {
        html += '<tr><td colspan="2" class="text-center text-muted">No localStorage data found</td></tr>';
    }
    
    html += '</tbody></table>';
    html += '<p class="mb-0 small text-muted"><strong>Total Items:</strong> ' + localStorage.length + '</p>';
    
    document.getElementById('localStorageData').innerHTML = html;
}

function clearAllCookies() {
    // Clear localStorage
    localStorage.clear();
    
    // Clear cookies (best effort - some are httpOnly)
    document.cookie.split(";").forEach(function(c) { 
        document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/"); 
    });
    
    Swal.fire({
        icon: 'success',
        title: 'Cleared!',
        text: 'All cookies and localStorage have been cleared. Reloading page...',
        timer: 2000,
        showConfirmButton: false
    }).then(() => {
        location.reload();
    });
}

function testSessionWrite() {
    Swal.fire({
        icon: 'info',
        title: 'Testing Session Write',
        text: 'Reloading page to see if page_views increments...',
        timer: 1500,
        showConfirmButton: false
    }).then(() => {
        location.reload();
    });
}

function testCookieWrite() {
    document.cookie = "test_cookie=test_value_" + Date.now() + "; path=/; max-age=3600";
    
    Swal.fire({
        icon: 'success',
        title: 'Cookie Set!',
        text: 'A test cookie has been set. Reloading to display it...',
        timer: 1500,
        showConfirmButton: false
    }).then(() => {
        location.reload();
    });
}

function testSessionTimeout() {
    const lastActivity = sessionStorage.getItem('last_activity');
    const now = Date.now();
    const timeout = 30 * 60 * 1000; // 30 minutes
    
    if (lastActivity) {
        const timeElapsed = now - parseInt(lastActivity);
        const timeRemaining = timeout - timeElapsed;
        const minutesRemaining = Math.floor(timeRemaining / 60000);
        
        Swal.fire({
            icon: 'info',
            title: 'Session Timeout Status',
            html: `
                <p><strong>Last Activity:</strong> ${Math.floor(timeElapsed / 1000)} seconds ago</p>
                <p><strong>Time Until Timeout:</strong> ${minutesRemaining} minutes</p>
                <p><strong>Session Status:</strong> ${timeRemaining > 0 ? '<span class="text-success">Active</span>' : '<span class="text-danger">Expired</span>'}</p>
            `
        });
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'No Session Data',
            text: 'Session timeout tracking not initialized yet.'
        });
    }
}
</script>
@endpush
