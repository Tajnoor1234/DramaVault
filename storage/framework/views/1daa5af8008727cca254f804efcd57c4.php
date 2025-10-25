<!-- Cookie Consent Banner -->
<div id="cookie-consent-banner" class="cookie-consent-banner" style="display: none;">
    <div class="cookie-consent-content">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mb-3 mb-lg-0">
                    <h5 class="mb-2">
                        <i class="fas fa-cookie-bite me-2"></i>Cookie Consent
                    </h5>
                    <p class="mb-0 text-muted">
                        We use cookies and similar technologies to help personalize content, tailor and measure ads, 
                        and provide a better experience. By clicking accept, you agree to this, as outlined in our 
                        <a href="<?php echo e(route('privacy-policy')); ?>" class="text-primary" target="_blank">Privacy Policy</a>.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <button type="button" class="btn btn-outline-secondary me-2" onclick="manageCookiePreferences()">
                        <i class="fas fa-cog me-1"></i>Manage Preferences
                    </button>
                    <button type="button" class="btn btn-primary" onclick="acceptAllCookies()">
                        <i class="fas fa-check me-1"></i>Accept All
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cookie Preferences Modal -->
<div class="modal fade" id="cookiePreferencesModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-cookie-bite me-2"></i>Cookie Preferences
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-4">
                    We use cookies to enhance your browsing experience and analyze site traffic. 
                    You can choose which cookies you want to accept.
                </p>
                
                <!-- Necessary Cookies (Always On) -->
                <div class="cookie-category mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h6 class="mb-1">
                                <i class="fas fa-shield-alt text-success me-2"></i>Necessary Cookies
                            </h6>
                            <small class="text-muted">Required for the website to function properly</small>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" checked disabled>
                            <label class="form-check-label text-muted">Always Active</label>
                        </div>
                    </div>
                    <p class="small text-muted mb-0">
                        These cookies are essential for authentication, security, and basic functionality.
                    </p>
                </div>
                
                <hr>
                
                <!-- Analytics Cookies -->
                <div class="cookie-category mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h6 class="mb-1">
                                <i class="fas fa-chart-line text-info me-2"></i>Analytics Cookies
                            </h6>
                            <small class="text-muted">Help us understand how you use our site</small>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="analyticsToggle">
                        </div>
                    </div>
                    <p class="small text-muted mb-0">
                        These cookies help us understand user behavior and improve our service.
                    </p>
                </div>
                
                <hr>
                
                <!-- Marketing Cookies -->
                <div class="cookie-category mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h6 class="mb-1">
                                <i class="fas fa-bullhorn text-warning me-2"></i>Marketing Cookies
                            </h6>
                            <small class="text-muted">Used to deliver personalized content</small>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="marketingToggle">
                        </div>
                    </div>
                    <p class="small text-muted mb-0">
                        These cookies may be used to show you relevant advertisements.
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="savePreferences()">
                    <i class="fas fa-save me-1"></i>Save Preferences
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.cookie-consent-banner {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: #fff;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
    z-index: 9999;
    padding: 20px 0;
    animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
    from {
        transform: translateY(100%);
    }
    to {
        transform: translateY(0);
    }
}

.cookie-consent-banner h5 {
    color: #333;
    font-size: 1.1rem;
}

.cookie-consent-banner p {
    font-size: 0.9rem;
    line-height: 1.5;
}

.dark-mode .cookie-consent-banner {
    background: #2d3748;
    border-top: 1px solid #4a5568;
}

.dark-mode .cookie-consent-banner h5 {
    color: #fff;
}

.cookie-category {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.dark-mode .cookie-category {
    background: #1a202c;
}

.form-check-input {
    cursor: pointer;
}

.form-check-input:disabled {
    cursor: not-allowed;
}
</style>

<script>
// Cookie Consent Management
(function() {
    // Check if user has already consented
    function hasConsented() {
        return localStorage.getItem('cookies_consent') === 'true';
    }
    
    // Show banner if user hasn't consented
    function showBanner() {
        if (!hasConsented()) {
            document.getElementById('cookie-consent-banner').style.display = 'block';
        }
    }
    
    // Accept all cookies
    window.acceptAllCookies = function() {
        setCookiePreferences({
            necessary: true,
            analytics: true,
            marketing: true
        });
        hideBanner();
    };
    
    // Manage preferences
    window.manageCookiePreferences = function() {
        // Load current preferences
        const prefs = getCookiePreferences();
        document.getElementById('analyticsToggle').checked = prefs.analytics;
        document.getElementById('marketingToggle').checked = prefs.marketing;
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('cookiePreferencesModal'));
        modal.show();
    };
    
    // Save preferences
    window.savePreferences = function() {
        const preferences = {
            necessary: true, // Always true
            analytics: document.getElementById('analyticsToggle').checked,
            marketing: document.getElementById('marketingToggle').checked
        };
        
        setCookiePreferences(preferences);
        hideBanner();
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('cookiePreferencesModal'));
        modal.hide();
        
        // Show success message
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Preferences Saved!',
                text: 'Your cookie preferences have been updated.',
                timer: 2000,
                showConfirmButton: false
            });
        }
    };
    
    // Get cookie preferences
    function getCookiePreferences() {
        return {
            necessary: localStorage.getItem('cookies_necessary') === 'true',
            analytics: localStorage.getItem('cookies_analytics') === 'true',
            marketing: localStorage.getItem('cookies_marketing') === 'true'
        };
    }
    
    // Set cookie preferences
    function setCookiePreferences(prefs) {
        localStorage.setItem('cookies_consent', 'true');
        localStorage.setItem('cookies_necessary', prefs.necessary);
        localStorage.setItem('cookies_analytics', prefs.analytics);
        localStorage.setItem('cookies_marketing', prefs.marketing);
        
        // If analytics disabled, you can remove analytics scripts here
        if (!prefs.analytics) {
            console.log('Analytics disabled');
            // Remove Google Analytics, etc.
        }
        
        // If marketing disabled, you can remove marketing scripts here
        if (!prefs.marketing) {
            console.log('Marketing cookies disabled');
        }
    }
    
    // Hide banner
    function hideBanner() {
        const banner = document.getElementById('cookie-consent-banner');
        banner.style.animation = 'slideDown 0.3s ease-out';
        setTimeout(() => {
            banner.style.display = 'none';
        }, 300);
    }
    
    // Show banner on page load
    document.addEventListener('DOMContentLoaded', showBanner);
})();
</script>

<style>
@keyframes slideDown {
    from {
        transform: translateY(0);
    }
    to {
        transform: translateY(100%);
    }
}
</style>
<?php /**PATH E:\XAMPP\htdocs\DramaVault\resources\views/partials/cookie-consent.blade.php ENDPATH**/ ?>