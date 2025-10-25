// DramaVault Custom JavaScript

// Import social sharing
import './social.js';

class DramaVault {
    constructor() {
        this.init();
    }

    init() {
        this.initializeTooltips();
        this.initializeAlerts();
        this.initializeAjaxForms();
        this.initializeInfiniteScroll();
    }

    // Initialize Bootstrap tooltips
    initializeTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // Auto-hide alerts after 5 seconds
    initializeAlerts() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        });
    }

    // Handle AJAX forms
    initializeAjaxForms() {
        const ajaxForms = document.querySelectorAll('[data-ajax="true"]');
        ajaxForms.forEach(form => {
            form.addEventListener('submit', this.handleAjaxForm.bind(this));
        });
    }

    // Handle AJAX form submission
    async handleAjaxForm(event) {
        event.preventDefault();
        
        const form = event.target;
        const submitBtn = form.querySelector('[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.innerHTML = '<span class="loading-spinner"></span> Loading...';
        submitBtn.disabled = true;

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: form.method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification(result.message || 'Success!', 'success');
                if (result.redirect) {
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 1000);
                }
            } else {
                this.showNotification(result.message || 'An error occurred', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('An error occurred. Please try again.', 'error');
        } finally {
            // Restore button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }

    // Initialize infinite scroll
    initializeInfiniteScroll() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.loadMoreContent();
                }
            });
        });

        const sentinel = document.getElementById('infinite-scroll-sentinel');
        if (sentinel) {
            observer.observe(sentinel);
        }
    }

    // Load more content for infinite scroll
    async loadMoreContent() {
        const nextPage = document.querySelector('[data-next-page]');
        if (!nextPage) return;

        const nextPageUrl = nextPage.dataset.nextPage;
        if (!nextPageUrl) return;

        try {
            const response = await fetch(nextPageUrl);
            const html = await response.text();
            
            // Parse the response and append content
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.querySelector('#content-container');
            const newSentinel = doc.querySelector('#infinite-scroll-sentinel');
            
            if (newContent) {
                document.getElementById('content-container').innerHTML += newContent.innerHTML;
                
                // Update sentinel
                if (newSentinel) {
                    document.getElementById('infinite-scroll-sentinel').dataset.nextPage = newSentinel.dataset.nextPage;
                } else {
                    document.getElementById('infinite-scroll-sentinel').remove();
                }
                
                // Re-initialize components
                this.init();
            }
        } catch (error) {
            console.error('Error loading more content:', error);
        }
    }

    // Show notification
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            animation: slideInRight 0.3s ease;
        `;
        
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // Rate drama
    async rateDrama(dramaId, rating) {
        try {
            const response = await fetch(`/dramas/${dramaId}/ratings`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ rating })
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification('Rating submitted successfully!', 'success');
                // Update UI
                this.updateRatingDisplay(dramaId, result.average_rating, result.total_ratings);
            } else {
                this.showNotification(result.message || 'Error submitting rating', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Error submitting rating', 'error');
        }
    }

    // Update rating display
    updateRatingDisplay(dramaId, averageRating, totalRatings) {
        const ratingElement = document.querySelector(`[data-drama-rating="${dramaId}"]`);
        const totalElement = document.querySelector(`[data-drama-total-ratings="${dramaId}"]`);
        
        if (ratingElement) {
            ratingElement.textContent = averageRating.toFixed(1);
        }
        if (totalElement) {
            totalElement.textContent = `(${totalRatings} ratings)`;
        }
    }

    // Add to watchlist
    async addToWatchlist(dramaId, status) {
        try {
            const response = await fetch(`/dramas/${dramaId}/watchlist`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ status })
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification(result.message || 'Added to watchlist!', 'success');
            } else {
                this.showNotification(result.message || 'Error adding to watchlist', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Error adding to watchlist', 'error');
        }
    }

    // Follow user
    async followUser(userId) {
        try {
            const response = await fetch(`/users/${userId}/follow`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification('You are now following this user', 'success');
                this.updateFollowButton(userId, true);
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Error following user', 'error');
        }
    }

    // Unfollow user
    async unfollowUser(userId) {
        try {
            const response = await fetch(`/users/${userId}/unfollow`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification('You have unfollowed this user', 'success');
                this.updateFollowButton(userId, false);
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Error unfollowing user', 'error');
        }
    }

    // Update follow button state
    updateFollowButton(userId, isFollowing) {
        const button = document.querySelector(`[data-user-id="${userId}"]`);
        if (button) {
            if (isFollowing) {
                button.textContent = 'Unfollow';
                button.classList.remove('btn-primary');
                button.classList.add('btn-outline-primary');
                button.onclick = () => this.unfollowUser(userId);
            } else {
                button.textContent = 'Follow';
                button.classList.remove('btn-outline-primary');
                button.classList.add('btn-primary');
                button.onclick = () => this.followUser(userId);
            }
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.dramaVault = new DramaVault();
});

// Utility functions
const DramaVaultUtils = {
    // Format date
    formatDate: (dateString) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    },

    // Format number with commas
    formatNumber: (number) => {
        return new Intl.NumberFormat().format(number);
    },

    // Truncate text
    truncateText: (text, length) => {
        if (text.length <= length) return text;
        return text.substr(0, length) + '...';
    },

    // Debounce function
    debounce: (func, wait) => {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
};