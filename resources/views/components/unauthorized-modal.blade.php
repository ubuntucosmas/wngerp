<!-- Unauthorized Access Modal -->
<div class="modal fade" id="unauthorizedModal" tabindex="-1" aria-labelledby="unauthorizedModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: 20px; overflow: hidden; box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);">
            <!-- Modal Header -->
            <div class="modal-header" style="background: linear-gradient(135deg, #dc3545, #c82333); border: none; padding: 2rem;">
                <div class="w-100 text-center">
                    <div style="font-size: 3rem; color: white; margin-bottom: 1rem;">
                        <i class="bi bi-shield-exclamation"></i>
                    </div>
                    <h4 class="modal-title text-white fw-bold" id="unauthorizedModalLabel">
                        Access Denied
                    </h4>
                    <p class="text-white-50 mb-0">You don't have permission to perform this action</p>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body" style="padding: 2rem;">
                <div class="text-center mb-4">
                    <div class="alert alert-danger" style="border-radius: 15px; border: none; background: rgba(220, 53, 69, 0.1);">
                        <strong style="color: #dc3545;">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Authorization Required
                        </strong>
                        <p class="mb-0 mt-2" style="color: #721c24;">
                            This action requires higher permissions or you're trying to access a resource you're not assigned to.
                        </p>
                    </div>
                </div>
                
                <div class="row text-center">
                    <div class="col-12">
                        <h6 class="fw-bold mb-3" style="color: #495057;">Possible Reasons:</h6>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="p-3" style="background: #f8f9fa; border-radius: 12px;">
                                    <i class="bi bi-person-x text-warning fs-4 mb-2"></i>
                                    <p class="small mb-0">Not assigned to this project/enquiry</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3" style="background: #f8f9fa; border-radius: 12px;">
                                    <i class="bi bi-shield-lock text-info fs-4 mb-2"></i>
                                    <p class="small mb-0">Insufficient role permissions</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 p-3" style="background: linear-gradient(135deg, #e3f2fd, #f3e5f5); border-radius: 12px;">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle text-primary fs-5 me-3"></i>
                        <div>
                            <strong style="color: #1976d2;">Need Help?</strong>
                            <p class="mb-0 small" style="color: #424242;">
                                Contact your Project Manager or System Administrator if you believe this is an error.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer" style="border: none; padding: 1.5rem 2rem; background: #f8f9fa;">
                <div class="w-100 d-flex justify-content-center gap-3">
                    <button type="button" class="btn btn-outline-secondary" onclick="history.back()" style="border-radius: 25px; padding: 10px 25px;">
                        <i class="bi bi-arrow-left me-2"></i>Go Back
                    </button>
                    <button type="button" class="btn btn-primary"  style="border-radius: 25px; padding: 10px 25px; background: linear-gradient(135deg, #667eea, #764ba2); border: none;">
                        <i class="bi bi-house me-2"></i>Dashboard
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Modal Animation */
.modal.fade .modal-dialog {
    transform: scale(0.8) translateY(-50px);
    transition: all 0.3s ease-out;
}

.modal.show .modal-dialog {
    transform: scale(1) translateY(0);
}

/* Pulse animation for icon */
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

#unauthorizedModal .bi-shield-exclamation {
    animation: pulse 2s infinite;
}

/* Custom button hover effects */
#unauthorizedModal .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}
</style>

<script>
// Auto-show small popup if there's a 403 error in session
@if(session('error') === 'unauthorized_access')
document.addEventListener('DOMContentLoaded', function() {
    const customMessage = '{{ session('error_message') ?? 'You don\'t have permission to perform this action.' }}';
    
    // Use the ErrorHandler to show small popup
    if (window.ErrorHandler) {
        ErrorHandler.show403Modal(customMessage);
    } else {
        // Fallback if ErrorHandler not loaded yet
        setTimeout(() => {
            if (window.ErrorHandler) {
                ErrorHandler.show403Modal(customMessage);
            }
        }, 100);
    }
});
@endif

// Function to show unauthorized popup programmatically
window.showUnauthorizedModal = function(customMessage = null) {
    if (window.ErrorHandler) {
        ErrorHandler.show403Modal(customMessage);
    } else {
        console.warn('ErrorHandler not available');
    }
};

// Note: AJAX error handling is now managed by ErrorHandler in error-handler.js
</script>