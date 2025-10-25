@extends('layouts.app')

@section('title', 'Privacy Policy - DramaVault')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h1 class="mb-4">
                        <i class="fas fa-shield-alt text-primary me-2"></i>Privacy Policy
                    </h1>
                    
                    <p class="text-muted mb-4">
                        <strong>Last Updated:</strong> October 26, 2025
                    </p>
                    
                    <hr class="my-4">
                    
                    <!-- Introduction -->
                    <section class="mb-5">
                        <h2 class="h4 mb-3">1. Introduction</h2>
                        <p>
                            Welcome to DramaVault. We respect your privacy and are committed to protecting your personal data. 
                            This privacy policy will inform you about how we look after your personal data when you visit our 
                            website and tell you about your privacy rights and how the law protects you.
                        </p>
                    </section>
                    
                    <!-- Information We Collect -->
                    <section class="mb-5">
                        <h2 class="h4 mb-3">2. Information We Collect</h2>
                        
                        <h3 class="h5 mb-2">2.1 Personal Information</h3>
                        <p>When you register on DramaVault, we collect:</p>
                        <ul>
                            <li>Name and username</li>
                            <li>Email address</li>
                            <li>Password (encrypted)</li>
                            <li>Profile information (bio, avatar)</li>
                            <li>Theme preferences</li>
                        </ul>
                        
                        <h3 class="h5 mb-2 mt-3">2.2 Usage Data</h3>
                        <p>We automatically collect:</p>
                        <ul>
                            <li>IP address</li>
                            <li>Browser type and version</li>
                            <li>Device information</li>
                            <li>Pages visited and time spent</li>
                            <li>Click patterns and interactions</li>
                            <li>Session information</li>
                        </ul>
                        
                        <h3 class="h5 mb-2 mt-3">2.3 Content You Create</h3>
                        <ul>
                            <li>Drama/Movie ratings and reviews</li>
                            <li>Comments and discussions</li>
                            <li>Watchlist items</li>
                            <li>Following relationships</li>
                        </ul>
                    </section>
                    
                    <!-- How We Use Your Information -->
                    <section class="mb-5">
                        <h2 class="h4 mb-3">3. How We Use Your Information</h2>
                        <p>We use your data to:</p>
                        <ul>
                            <li><strong>Provide Services:</strong> Enable account creation, authentication, and personalization</li>
                            <li><strong>Improve Experience:</strong> Customize content, remember preferences, optimize features</li>
                            <li><strong>Communication:</strong> Send notifications, updates, and important information</li>
                            <li><strong>Security:</strong> Detect fraud, prevent abuse, ensure platform safety</li>
                            <li><strong>Analytics:</strong> Understand usage patterns and improve our service</li>
                            <li><strong>Legal Compliance:</strong> Meet legal obligations and enforce terms</li>
                        </ul>
                    </section>
                    
                    <!-- Cookies and Tracking -->
                    <section class="mb-5">
                        <h2 class="h4 mb-3">4. Cookies and Tracking Technologies</h2>
                        
                        <h3 class="h5 mb-2">4.1 Necessary Cookies</h3>
                        <p>
                            <span class="badge bg-success">Always Active</span>
                        </p>
                        <p>Essential for authentication, security, and basic functionality. Cannot be disabled.</p>
                        <ul>
                            <li>Session cookies (Laravel session)</li>
                            <li>CSRF protection tokens</li>
                            <li>Authentication tokens</li>
                        </ul>
                        
                        <h3 class="h5 mb-2 mt-3">4.2 Analytics Cookies</h3>
                        <p>
                            <span class="badge bg-info">Optional</span>
                        </p>
                        <p>Help us understand how you use our site.</p>
                        <ul>
                            <li>Page view tracking</li>
                            <li>User behavior analysis</li>
                            <li>Performance monitoring</li>
                        </ul>
                        
                        <h3 class="h5 mb-2 mt-3">4.3 Marketing Cookies</h3>
                        <p>
                            <span class="badge bg-warning">Optional</span>
                        </p>
                        <p>Used to deliver personalized content and advertisements.</p>
                        
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle me-2"></i>
                            You can manage your cookie preferences at any time using our 
                            <a href="#" onclick="manageCookiePreferences(); return false;" class="alert-link">Cookie Preferences</a> tool.
                        </div>
                    </section>
                    
                    <!-- Data Sharing -->
                    <section class="mb-5">
                        <h2 class="h4 mb-3">5. Data Sharing and Disclosure</h2>
                        <p>We do not sell your personal data. We may share data with:</p>
                        <ul>
                            <li><strong>Service Providers:</strong> Cloud hosting, email services, analytics tools</li>
                            <li><strong>Legal Requirements:</strong> When required by law or to protect rights</li>
                            <li><strong>Business Transfers:</strong> In case of merger, acquisition, or asset sale</li>
                            <li><strong>With Your Consent:</strong> When you explicitly agree to share</li>
                        </ul>
                    </section>
                    
                    <!-- Data Security -->
                    <section class="mb-5">
                        <h2 class="h4 mb-3">6. Data Security</h2>
                        <p>We implement security measures to protect your data:</p>
                        <ul>
                            <li>Encrypted passwords (bcrypt)</li>
                            <li>HTTPS encryption for data transmission</li>
                            <li>Secure session management</li>
                            <li>Regular security audits</li>
                            <li>IP address tracking for suspicious activity</li>
                            <li>CSRF protection on all forms</li>
                        </ul>
                    </section>
                    
                    <!-- Your Rights -->
                    <section class="mb-5">
                        <h2 class="h4 mb-3">7. Your Privacy Rights</h2>
                        <p>You have the right to:</p>
                        <ul>
                            <li><strong>Access:</strong> Request a copy of your personal data</li>
                            <li><strong>Rectification:</strong> Correct inaccurate or incomplete data</li>
                            <li><strong>Erasure:</strong> Request deletion of your data ("right to be forgotten")</li>
                            <li><strong>Restriction:</strong> Limit how we use your data</li>
                            <li><strong>Portability:</strong> Receive your data in a portable format</li>
                            <li><strong>Object:</strong> Object to certain data processing</li>
                            <li><strong>Withdraw Consent:</strong> Revoke consent at any time</li>
                        </ul>
                        
                        <div class="alert alert-success mt-3">
                            <i class="fas fa-user-cog me-2"></i>
                            To exercise these rights, visit your 
                            <a href="{{ route('users.show', auth()->user()) }}" class="alert-link">Profile Settings</a> 
                            or contact us at privacy@dramavault.com
                        </div>
                    </section>
                    
                    <!-- Data Retention -->
                    <section class="mb-5">
                        <h2 class="h4 mb-3">8. Data Retention</h2>
                        <p>We retain your data for as long as:</p>
                        <ul>
                            <li>Your account is active</li>
                            <li>Needed to provide services</li>
                            <li>Required by law</li>
                            <li>Necessary for legitimate business purposes</li>
                        </ul>
                        <p>
                            When you delete your account, we remove personal data within 30 days, 
                            except where retention is required by law.
                        </p>
                    </section>
                    
                    <!-- Third-Party Links -->
                    <section class="mb-5">
                        <h2 class="h4 mb-3">9. Third-Party Links</h2>
                        <p>
                            Our website may contain links to third-party websites. We are not responsible for 
                            their privacy practices. Please review their privacy policies separately.
                        </p>
                    </section>
                    
                    <!-- Children's Privacy -->
                    <section class="mb-5">
                        <h2 class="h4 mb-3">10. Children's Privacy</h2>
                        <p>
                            DramaVault is not intended for users under 13 years old. We do not knowingly 
                            collect data from children. If you believe a child has provided us with data, 
                            please contact us immediately.
                        </p>
                    </section>
                    
                    <!-- International Transfers -->
                    <section class="mb-5">
                        <h2 class="h4 mb-3">11. International Data Transfers</h2>
                        <p>
                            Your data may be transferred to and stored on servers in different countries. 
                            We ensure appropriate safeguards are in place to protect your data in accordance 
                            with this privacy policy.
                        </p>
                    </section>
                    
                    <!-- Changes to Policy -->
                    <section class="mb-5">
                        <h2 class="h4 mb-3">12. Changes to This Policy</h2>
                        <p>
                            We may update this privacy policy from time to time. Changes will be posted on this 
                            page with an updated "Last Updated" date. We encourage you to review this policy 
                            periodically.
                        </p>
                    </section>
                    
                    <!-- Contact Information -->
                    <section class="mb-5">
                        <h2 class="h4 mb-3">13. Contact Us</h2>
                        <p>If you have questions about this privacy policy or our practices, contact us:</p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-envelope me-2 text-primary"></i>Email: privacy@dramavault.com</li>
                            <li><i class="fas fa-globe me-2 text-primary"></i>Website: {{ url('/') }}</li>
                            <li><i class="fas fa-map-marker-alt me-2 text-primary"></i>Address: [Your Company Address]</li>
                        </ul>
                    </section>
                    
                    <hr class="my-4">
                    
                    <!-- Footer Actions -->
                    <div class="text-center mt-4">
                        <a href="{{ url()->previous() }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>Go Back
                        </a>
                        <button onclick="manageCookiePreferences()" class="btn btn-outline-secondary">
                            <i class="fas fa-cookie-bite me-2"></i>Cookie Preferences
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    border: none;
}

section h2 {
    color: var(--bs-primary);
    font-weight: 600;
    margin-top: 2rem;
}

section h3 {
    font-weight: 600;
}

section ul {
    margin-left: 1.5rem;
}

section ul li {
    margin-bottom: 0.5rem;
}

.alert a {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .card-body {
        padding: 2rem 1rem !important;
    }
}
</style>
@endpush
