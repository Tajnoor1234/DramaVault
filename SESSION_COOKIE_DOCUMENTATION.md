# Session & Cookie Management Documentation

## Overview
This document describes the session and cookie management system implemented in DramaVault.

## Features Implemented

### 1. Cookie Consent Banner
**Location:** `resources/views/partials/cookie-consent.blade.php`

Features:
- ✅ Cookie consent banner with "Accept All" and "Manage Preferences" buttons
- ✅ Modal for managing cookie preferences (Analytics, Marketing)
- ✅ Necessary cookies always enabled (authentication, security)
- ✅ LocalStorage-based consent tracking
- ✅ Bootstrap 5 styled with dark mode support
- ✅ Auto-hides after consent is given

### 2. Session Management

#### Middleware: TrackUserActivity
**Location:** `app/Http/Middleware/TrackUserActivity.php`

Functionality:
- ✅ Tracks last activity time for authenticated users
- ✅ Stores last visited page URL in session
- ✅ Counts page views per session
- ✅ Updates `last_active_at` in users table

#### Enhanced Login Controller
**Location:** `app/Http/Controllers/Auth/LoginController.php`

Updates:
- ✅ Records session ID on login
- ✅ Stores IP address and user agent
- ✅ Sets login timestamp
- ✅ Creates session data: user_id, user_role, logged_in_at

### 3. Database Schema

#### New Migration: `2025_10_26_000001_add_session_fields_to_users_table.php`

Fields Added to Users Table:
```php
- last_active_at (timestamp)   // Last user activity
- session_id (string)          // Current session ID
- last_ip_address (ipAddress)  // Last login IP
- user_agent (text)            // Browser/device info
```

### 4. Helper Classes

#### SessionHelper
**Location:** `app/Helpers/SessionHelper.php`

Methods:
```php
SessionHelper::get($key, $default)     // Get session value
SessionHelper::put($key, $value)       // Store session value
SessionHelper::has($key)               // Check if key exists
SessionHelper::forget($key)            // Remove item
SessionHelper::flush()                 // Clear all session
SessionHelper::regenerate()            // Regenerate session ID
SessionHelper::all()                   // Get all session data
SessionHelper::flash($key, $value)     // Flash data
SessionHelper::getPageViews()          // Get page view count
SessionHelper::getLastActivity()       // Get last activity time
SessionHelper::getLastPage()           // Get last visited URL
SessionHelper::isActive()              // Check if session active
SessionHelper::getInfo()               // Get debug info
```

#### CookieHelper
**Location:** `app/Helpers/CookieHelper.php`

Methods:
```php
CookieHelper::make($name, $value, $minutes)    // Create cookie
CookieHelper::get($name, $default)             // Get cookie value
CookieHelper::has($name)                       // Check if exists
CookieHelper::forget($name)                    // Delete cookie
CookieHelper::forever($name, $value)           // Forever cookie
CookieHelper::queue($name, $value, $minutes)   // Queue cookie
CookieHelper::hasConsent()                     // Check consent
CookieHelper::getPreferences()                 // Get preferences
CookieHelper::setConsent($n, $a, $m)          // Set consent
```

### 5. Frontend Integration

#### Session Timeout Handler
**Location:** `resources/views/layouts/app.blade.php`

Features:
- ✅ 30-minute inactivity timeout
- ✅ Tracks user activity (mouse, keyboard, scroll, touch)
- ✅ Shows SweetAlert warning on timeout
- ✅ Redirects to login page
- ✅ Uses sessionStorage for activity tracking

#### Cookie Consent JavaScript

Features:
- ✅ Auto-shows banner if no consent given
- ✅ Stores preferences in localStorage
- ✅ Integrates with Bootstrap modals
- ✅ SweetAlert success notifications
- ✅ Slide animations

### 6. Configuration

#### Session Config
**File:** `config/session.php`

Current Settings:
```php
'driver' => 'database'           // Sessions stored in DB
'lifetime' => 120                // 2 hours (120 minutes)
'expire_on_close' => false       // Session persists
'encrypt' => false               // Not encrypted
```

#### Middleware Registration
**File:** `bootstrap/app.php`

Registered:
```php
// Global middleware
$middleware->append(\App\Http\Middleware\TrackUserActivity::class);

// Alias
'track.activity' => \App\Http\Middleware\TrackUserActivity::class
```

## Usage Examples

### In Controllers
```php
use App\Helpers\SessionHelper;
use App\Helpers\CookieHelper;

// Store session data
SessionHelper::put('cart', $items);

// Get session data
$cart = SessionHelper::get('cart', []);

// Set cookie
return response()->view('page')->cookie(
    CookieHelper::make('preference', 'dark', 60)
);

// Check consent
if (CookieHelper::hasConsent()) {
    // Load analytics
}
```

### In Blade Templates
```blade
<!-- Session data -->
@if(session()->has('success'))
    {{ session('success') }}
@endif

<!-- Cookie consent check -->
@if(!request()->hasCookie('cookies_consent'))
    <!-- Show notice -->
@endif
```

### JavaScript Usage
```javascript
// Cookie consent
acceptAllCookies()           // Accept all cookies
manageCookiePreferences()    // Show preferences modal
savePreferences()            // Save custom preferences

// LocalStorage
localStorage.getItem('cookies_consent')
localStorage.getItem('cookies_analytics')
localStorage.getItem('cookies_marketing')
```

## Cookie Categories

### 1. Necessary Cookies (Always Active)
- Authentication tokens (Laravel session)
- CSRF protection
- Security features
- Basic functionality

### 2. Analytics Cookies (Optional)
- Page view tracking
- User behavior analysis
- Performance monitoring
- Can be disabled by user

### 3. Marketing Cookies (Optional)
- Personalized content
- Advertisement tracking
- Third-party integrations
- Can be disabled by user

## Security Features

### Session Security
- ✅ CSRF protection enabled
- ✅ Session regeneration on login
- ✅ IP address tracking
- ✅ User agent validation
- ✅ Inactivity timeout
- ✅ Secure cookie flags (in production)

### Cookie Security
- ✅ HttpOnly flag for sensitive cookies
- ✅ SameSite attribute set
- ✅ Secure flag in production
- ✅ User-controlled preferences
- ✅ 1-year consent duration

## Testing

### Test Session Functionality
```bash
# Start server
php artisan serve

# Login to create session
# Visit pages to increment page_views
# Check database for session_id, last_active_at
```

### Test Cookie Consent
1. Visit any page (banner should appear)
2. Click "Accept All" (banner disappears)
3. Reload page (banner stays hidden)
4. Clear localStorage (banner reappears)
5. Click "Manage Preferences" (modal opens)
6. Toggle options and save

### Test Session Timeout
1. Login to the site
2. Wait 30 minutes without activity
3. Try to interact (should show timeout alert)
4. Click OK (redirects to login)

## Future Enhancements

Potential additions:
- [ ] Database-backed cookie consent logs
- [ ] Admin panel for viewing active sessions
- [ ] Force logout from all devices
- [ ] Session analytics dashboard
- [ ] Remember me functionality
- [ ] Multi-device session management
- [ ] Google Analytics integration (with consent)
- [ ] Cookie policy page
- [ ] Privacy policy page

## Troubleshooting

### Cookie Banner Not Showing
- Check if localStorage has 'cookies_consent' = 'true'
- Clear localStorage: `localStorage.clear()`
- Check browser console for errors

### Session Timeout Not Working
- Verify SESSION_LIFETIME in .env
- Check middleware is registered
- Ensure user is authenticated
- Check browser console for errors

### Session Data Not Persisting
- Check SESSION_DRIVER in .env (should be 'database')
- Verify sessions table exists
- Run: `php artisan migrate`
- Clear cache: `php artisan cache:clear`

## Files Modified/Created

### Created Files
1. `resources/views/partials/cookie-consent.blade.php` - Cookie consent UI
2. `app/Http/Middleware/TrackUserActivity.php` - Session tracking
3. `database/migrations/2025_10_26_000001_add_session_fields_to_users_table.php` - DB schema
4. `app/Helpers/SessionHelper.php` - Session utilities
5. `app/Helpers/CookieHelper.php` - Cookie utilities
6. `SESSION_COOKIE_DOCUMENTATION.md` - This file

### Modified Files
1. `bootstrap/app.php` - Registered middleware
2. `app/Http/Controllers/Auth/LoginController.php` - Enhanced login tracking
3. `resources/views/layouts/app.blade.php` - Added consent banner, session timeout

## Configuration Files
No changes needed to:
- `.env` (uses existing SESSION_* variables)
- `config/session.php` (already configured for database)
- `config/cookie.php` (uses Laravel defaults)

## Maintenance

### Regular Tasks
- Monitor sessions table size
- Clean expired sessions: `php artisan session:table`
- Review cookie consent logs (if implemented)
- Update cookie policy as needed

### Performance
- Sessions stored in database (scalable)
- Cookie banner uses localStorage (no server load)
- Minimal JavaScript overhead
- Lazy-loads only when needed

---

**Last Updated:** October 26, 2025
**Laravel Version:** 12.35.1
**PHP Version:** 8.2.12
