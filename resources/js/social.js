// Social sharing functionality
class SocialSharing {
    constructor() {
        this.init();
    }

    init() {
        this.setupCopyToClipboard();
        this.setupShareAPI();
    }

    setupCopyToClipboard() {
        // Handled by the function below
    }

    setupShareAPI() {
        // Add native share button if available
        if (navigator.share) {
            this.addNativeShareButton();
        }
    }

    addNativeShareButton() {
        const shareContainer = document.querySelector('.social-sharing .d-flex');
        if (shareContainer) {
            const nativeShareBtn = document.createElement('button');
            nativeShareBtn.className = 'btn btn-outline-primary btn-sm';
            nativeShareBtn.innerHTML = '<i class="fas fa-share-alt me-1"></i>Share';
            nativeShareBtn.addEventListener('click', this.nativeShare.bind(this));
            shareContainer.appendChild(nativeShareBtn);
        }
    }

    async nativeShare() {
        const dramaTitle = document.querySelector('h1').textContent;
        const dramaUrl = window.location.href;

        try {
            await navigator.share({
                title: dramaTitle,
                text: `Check out ${dramaTitle} on DramaVault`,
                url: dramaUrl,
            });
        } catch (err) {
            console.log('Error sharing:', err);
        }
    }
}

// Copy to clipboard function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        // Show success message
        const originalEvent = event;
        const button = originalEvent.target.closest('button');
        const originalText = button.innerHTML;
        
        button.innerHTML = '<i class="fas fa-check me-1"></i>Copied!';
        button.classList.remove('btn-outline-dark');
        button.classList.add('btn-success');
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-dark');
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy: ', err);
        alert('Failed to copy link to clipboard');
    });
}

// Initialize social sharing
document.addEventListener('DOMContentLoaded', function() {
    window.socialSharing = new SocialSharing();
});