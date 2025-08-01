<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Access Denied</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>
    <!-- Small Popup Modal for 403 Error -->
    <div class="modal fade show" id="error403Modal" tabindex="-1" style="display: block; background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
            <div class="modal-content error-modal">
                <!-- Header -->
                <div class="modal-header error-header">
                    <div class="error-icon-small">
                        <i class="bi bi-shield-exclamation"></i>
                    </div>
                    <div>
                        <h5 class="modal-title">Access Denied</h5>
                        <small class="text-muted">You don't have permission</small>
                    </div>
                </div>

                <!-- Body -->
                <div class="modal-body">
                    <div class="error-message">
                        <p class="mb-3">This could be because:</p>
                        <ul class="error-reasons">
                            <li><i class="bi bi-diagram-3 text-info"></i> You're not assigned to this project/enquiry</li>
                            <li><i class="bi bi-person-badge text-warning"></i> Your role lacks required permissions</li>
                            <li><i class="bi bi-clock text-secondary"></i> Your session may have expired</li>
                        </ul>
                    </div>

                    <div class="help-section">
                        <small class="text-muted">
                            <i class="bi bi-question-circle"></i>
                            Need help? Contact your Project Manager
                        </small>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="history.back()">
                        <i class="bi bi-arrow-left"></i> Go Back
                    </button>
                </div>

                <!-- Auto-close countdown -->
                <div class="auto-close-bar">
                    <div class="progress" style="height: 3px;">
                        <div class="progress-bar bg-primary" id="autoCloseProgress"></div>
                    </div>
                    <!-- <small class="auto-close-text">Auto-redirecting in <span id="countdown">8</span>s</small> -->
                </div>
            </div>
        </div>
    </div>

    <style>
    .error-modal {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        overflow: hidden;
        animation: modalSlideIn 0.4s ease-out;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-30px) scale(0.9);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .error-header {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        border: none;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .error-icon-small {
        font-size: 2rem;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .modal-title {
        font-weight: 700;
        margin: 0;
        font-size: 1.3rem;
    }

    .modal-body {
        padding: 25px;
    }

    .error-reasons {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .error-reasons li {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 0;
        font-size: 0.9rem;
        color: #6c757d;
    }

    .error-reasons i {
        width: 16px;
        text-align: center;
    }

    .help-section {
        background: rgba(108, 117, 125, 0.1);
        padding: 15px;
        border-radius: 8px;
        margin-top: 20px;
        text-align: center;
    }

    .modal-footer {
        padding: 15px 25px;
        border-top: 1px solid rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }

    .btn-sm {
        padding: 8px 16px;
        font-size: 0.875rem;
        border-radius: 20px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,123,255,0.3);
    }

    .btn-outline-secondary:hover {
        transform: translateY(-1px);
    }

    .auto-close-bar {
        background: #f8f9fa;
        padding: 10px 20px;
        text-align: center;
        position: relative;
    }

    .auto-close-text {
        color: #6c757d;
        font-size: 0.8rem;
    }

    .progress-bar {
        transition: width 1s linear;
    }

    /* Shake animation for attention */
    .shake {
        animation: shake 0.5s ease-in-out;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>


    // Prevent modal from being dismissed by clicking outside
    document.getElementById('error403Modal').addEventListener('click', function(e) {
        e.stopPropagation();
    });
    </script>
</body>
</html>