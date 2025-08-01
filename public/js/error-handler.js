/**
 * Beautiful Error Handler for 403 Unauthorized Errors
 * Provides elegant modal popups and notifications
 */

class ErrorHandler {
    constructor() {
        this.init();
    }

    init() {
        // Initialize global error handling
        this.setupGlobalErrorHandling();
        this.setupFormErrorHandling();
        this.checkForSessionErrors();
    }

    /**
     * Setup global AJAX error handling
     */
    setupGlobalErrorHandling() {
        // Handle fetch API errors
        const originalFetch = window.fetch;
        window.fetch = function(...args) {
            return originalFetch.apply(this, args)
                .then(response => {
                    if (response.status === 403) {
                        ErrorHandler.show403Modal();
                        throw new Error('Unauthorized access');
                    }
                    return response;
                })
                .catch(error => {
                    if (error.message === 'Unauthorized access') {
                        // Already handled
                        throw error;
                    }
                    return Promise.reject(error);
                });
        };

        // Handle jQuery AJAX errors if jQuery is available
        if (window.jQuery) {
            $(document).ajaxError(function(event, xhr, settings) {
                if (xhr.status === 403) {
                    ErrorHandler.show403Modal(xhr.responseJSON?.message);
                }
            });
        }
    }

    /**
     * Setup form submission error handling
     */
    setupFormErrorHandling() {
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.tagName === 'FORM') {
                // Add loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Processing...';
                    submitBtn.disabled = true;

                    // Reset button after 5 seconds (fallback)
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }, 5000);
                }
            }
        });
    }

    /**
     * Check for session errors and show appropriate modals
     */
    checkForSessionErrors() {
        // Check if there's an unauthorized error in session
        const urlParams = new URLSearchParams(window.location.search);
        const errorType = urlParams.get('error');
        
        if (errorType === 'unauthorized') {
            this.show403Modal();
        }
    }

    /**
     * Show small 403 unauthorized popup
     */
    static show403Modal(customMessage = null) {
        const defaultMessage = 'You don\'t have permission to perform this action.';
        const finalMessage = customMessage || defaultMessage;
        
        // Create small popup HTML
        const popupHtml = `
            <div class="modal fade show" id="unauthorizedPopup" tabindex="-1" style="display: block; background: rgba(0,0,0,0.5);">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
                    <div class="modal-content error-popup">
                        <div class="modal-header error-popup-header">
                            <div class="error-popup-icon">
                                <i class="bi bi-shield-exclamation"></i>
                            </div>
                            <div>
                                <h6 class="modal-title mb-0">Access Denied</h6>
                                <small class="text-muted">Unauthorized</small>
                            </div>
                        </div>
                        <div class="modal-body p-3">
                            <p class="mb-2 text-muted" style="font-size: 0.9rem;">${finalMessage}</p>
                            <div class="reasons-list">
                                <small class="text-muted">This could be because:</small>
                                <ul class="small-reasons mt-1">
                                    <li><i class="bi bi-diagram-3 text-info"></i> Not assigned to this resource</li>
                                    <li><i class="bi bi-person-badge text-warning"></i> Insufficient permissions</li>
                                </ul>
                            </div>
                        </div>
                        <div class="modal-footer p-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="history.back()">
                                <i class="bi bi-arrow-left"></i> Back
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('unauthorizedPopup').remove()">
                                <i class="bi bi-check"></i> OK
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Remove existing popup if any
        const existingPopup = document.getElementById('unauthorizedPopup');
        if (existingPopup) {
            existingPopup.remove();
        }
        
        // Add popup to page
        document.body.insertAdjacentHTML('beforeend', popupHtml);
        
        // Add shake animation after a brief delay
        setTimeout(() => {
            const popup = document.querySelector('.error-popup');
            if (popup) {
                popup.style.animation += ', shake 0.4s ease-in-out';
            }
        }, 500);
        
        // Auto-close after 6 seconds
        setTimeout(() => {
            const popup = document.getElementById('unauthorizedPopup');
            if (popup) {
                popup.style.opacity = '0';
                popup.style.transform = 'scale(0.95)';
                setTimeout(() => popup.remove(), 300);
            }
        }, 6000);
        
        // Close on outside click
        document.getElementById('unauthorizedPopup').addEventListener('click', function(e) {
            if (e.target === this) {
                this.remove();
            }
        });
    }

    /**
     * Show success notification
     */
    static showSuccess(message, duration = 3000) {
        this.showNotification(message, 'success', duration);
    }

    /**
     * Show error notification
     */
    static showError(message, duration = 5000) {
        this.showNotification(message, 'error', duration);
    }

    /**
     * Show warning notification
     */
    static showWarning(message, duration = 4000) {
        this.showNotification(message, 'warning', duration);
    }

    /**
     * Show info notification
     */
    static showInfo(message, duration = 3000) {
        this.showNotification(message, 'info', duration);
    }

    /**
     * Show notification toast
     */
    static showNotification(message, type = 'info', duration = 3000) {
        // Remove existing notifications
        const existing = document.querySelectorAll('.error-notification');
        existing.forEach(el => el.remove());

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `error-notification alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            max-width: 500px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border: none;
            animation: slideInRight 0.3s ease-out;
        `;

        const icons = {
            success: 'bi-check-circle-fill',
            error: 'bi-exclamation-triangle-fill',
            warning: 'bi-exclamation-triangle-fill',
            info: 'bi-info-circle-fill'
        };

        notification.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="bi ${icons[type]} me-2 fs-5"></i>
                <div class="flex-grow-1">${message}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        document.body.appendChild(notification);

        // Auto remove after duration
        setTimeout(() => {
            if (notification.parentNode) {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 150);
            }
        }, duration);
    }

    /**
     * Handle authorization errors from server responses
     */
    static handleServerError(response) {
        if (response.status === 403) {
            this.show403Modal(response.data?.message);
            return true;
        }
        return false;
    }
}

// CSS for animations and popup styles
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    @keyframes popupSlideIn {
        from { 
            opacity: 0; 
            transform: translateY(-20px) scale(0.95); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0) scale(1); 
        }
    }

    .error-notification {
        animation: slideInRight 0.3s ease-out;
    }

    .error-popup {
        border: none;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        overflow: hidden;
        animation: popupSlideIn 0.3s ease-out;
    }
    
    .error-popup-header {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        border: none;
        padding: 15px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .error-popup-icon {
        font-size: 1.5rem;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    
    .small-reasons {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .small-reasons li {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 3px 0;
        font-size: 0.8rem;
        color: #6c757d;
    }
    
    .small-reasons i {
        width: 12px;
        text-align: center;
        font-size: 0.7rem;
    }
    
    .error-popup .btn-sm {
        padding: 6px 12px;
        font-size: 0.8rem;
        border-radius: 15px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .error-popup .btn-sm:hover {
        transform: translateY(-1px);
    }
`;
document.head.appendChild(style);

// Initialize error handler when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.errorHandler = new ErrorHandler();
});

// Export for global use
window.ErrorHandler = ErrorHandler;