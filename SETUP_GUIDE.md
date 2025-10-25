# Quick Setup Guide - Sessions & Cookies

## Already Done ✅

The following have been automatically set up:

1. ✅ Cookie consent banner created
2. ✅ Session tracking middleware registered
3. ✅ Database migration run (users table updated)
4. ✅ Privacy policy page created
5. ✅ Helper classes created
6. ✅ Footer links updated
7. ✅ All caches cleared

## Verify Installation

### 1. Check Database
```sql
-- Verify new columns exist
DESCRIBE users;

-- Should see: last_active_at, session_id, last_ip_address, user_agent
```

### 2. Test Cookie Banner
```
1. Open your site in incognito/private window
2. You should see cookie consent banner at bottom
3. Click "Accept All" → Banner disappears
4. Reload page → Banner stays hidden
```

### 3. Test Session Tracking
```
1. Login to your account
2. Visit a few pages
3. Check database:
   
   SELECT name, last_active_at, last_ip_address 
   FROM users 
   WHERE id = YOUR_ID;
   
4. Should see recent timestamp and your IP
```

### 4. Test Privacy Policy
```
Visit: http://localhost:8000/privacy-policy
Should load complete privacy policy page
```

## Optional Adjustments

### Adjust Session Timeout

**File:** `resources/views/layouts/app.blade.php`
**Line:** ~80

```javascript
// Change from 30 to desired minutes
const SESSION_TIMEOUT = 30 * 60 * 1000;

// Examples:
const SESSION_TIMEOUT = 60 * 60 * 1000;  // 60 minutes
const SESSION_TIMEOUT = 15 * 60 * 1000;  // 15 minutes
```

### Change Session Lifetime

**File:** `.env`

```env
# Current: 120 minutes (2 hours)
SESSION_LIFETIME=120

# Change to:
SESSION_LIFETIME=60   # 1 hour
SESSION_LIFETIME=180  # 3 hours
```

Then run: `php artisan config:clear`

### Customize Cookie Banner Colors

**File:** `resources/views/partials/cookie-consent.blade.php`
**Line:** ~90-130

```css
.cookie-consent-banner {
    background: #fff;  /* Change banner background */
}

.dark-mode .cookie-consent-banner {
    background: #2d3748;  /* Change dark mode background */
}
```

## Production Checklist

Before deploying to production:

- [ ] Update privacy policy with real company info:
  - Company address
  - Contact email
  - Data protection officer (if required)

- [ ] Set secure cookie flags in `.env`:
```env
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```

- [ ] Enable HTTPS on your server

- [ ] Test all cookie functions in production environment

- [ ] Consider adding Terms of Service page

- [ ] Set up proper email for privacy@dramavault.com

- [ ] Review and adjust data retention periods

- [ ] Add analytics integration (if using analytics cookies)

## Maintenance

### Clear Old Sessions
```bash
# If using database sessions
php artisan session:gc

# Or set up automatic cleanup in config/session.php
'lottery' => [2, 100],  # 2% chance per request
```

### Monitor Session Table Size
```sql
SELECT COUNT(*) as session_count FROM sessions;
```

### Clear User Session Data
```php
// In UserController delete method
$user->update([
    'session_id' => null,
    'last_active_at' => null,
    'last_ip_address' => null,
    'user_agent' => null
]);
```

## Troubleshooting

### Cookie Banner Not Showing
```javascript
// In browser console:
localStorage.clear();
location.reload();
```

### Session Not Tracking
```bash
# Check middleware is registered
php artisan route:list

# Should see TrackUserActivity in middleware column
```

### Migration Already Ran Error
```bash
# If you see "Table already exists":
# This is fine - migration already completed
# No action needed
```

### Privacy Policy 404
```bash
php artisan route:clear
php artisan route:list | grep privacy
```

## Support & Documentation

📖 **Full Documentation:** `SESSION_COOKIE_DOCUMENTATION.md`
📋 **Summary:** `SESSION_COOKIE_SUMMARY.md`
🚀 **This Guide:** `SETUP_GUIDE.md`

## Files Location Reference

```
Project Root/
├── app/
│   ├── Helpers/
│   │   ├── SessionHelper.php
│   │   └── CookieHelper.php
│   └── Http/
│       └── Middleware/
│           └── TrackUserActivity.php
├── resources/
│   └── views/
│       ├── partials/
│       │   └── cookie-consent.blade.php
│       ├── privacy-policy.blade.php
│       └── layouts/
│           └── app.blade.php (modified)
├── database/
│   └── migrations/
│       └── 2025_10_26_000001_add_session_fields_to_users_table.php
└── Documentation/
    ├── SESSION_COOKIE_DOCUMENTATION.md
    ├── SESSION_COOKIE_SUMMARY.md
    └── SETUP_GUIDE.md (this file)
```

## Quick Commands

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Check migrations
php artisan migrate:status

# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback --step=1
```

## Testing URLs

- Home: `http://localhost:8000/`
- Privacy Policy: `http://localhost:8000/privacy-policy`
- Profile (logged in): `http://localhost:8000/users/{id}`

## Browser Console Tests

```javascript
// Check consent status
console.log(localStorage.getItem('cookies_consent'));

// Check preferences
console.log({
    consent: localStorage.getItem('cookies_consent'),
    analytics: localStorage.getItem('cookies_analytics'),
    marketing: localStorage.getItem('cookies_marketing')
});

// Clear consent (to test banner again)
localStorage.removeItem('cookies_consent');
location.reload();

// Trigger preferences modal
manageCookiePreferences();

// Accept all cookies
acceptAllCookies();
```

---

**Setup Status:** ✅ Complete  
**Ready for:** Testing and Production  
**Last Updated:** October 26, 2025
