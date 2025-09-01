<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/wng-logo.png') }}" type="image/png">
    <title>@yield('title', 'Woodnork Green')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- <style>
        /* Loading Spinner */
        .page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .spinner {
            width: 60px;
            height: 60px;
            position: relative;
        }

        .spinner:before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border: 4px solid rgba(12, 45, 72, 0.1);
            border-radius: 50%;
            border-top-color: #0C2D48;
            animation: spin 0.4s linear infinite;
        }

        .spinner:after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 30px;
            height: 16px;
            border: 4px solid #28a745;
            border-top: none;
            border-right: none;
            transform: translate(-50%, -65%) rotate(-45deg) scale(0);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.27, 1.55);
        }

        .spinner.complete:before {
            animation: none;
            border-color: #28a745;
        }

        .spinner.complete:after {
            transform: translate(-50%, -65%) rotate(-45deg) scale(1);
            opacity: 1;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style> -->


    <style>
        /* Sidebar Styles */
        .sidebar {
            width: 200px;
            background-color: #f8f8f8;
            height: 100vh;
            position: fixed;
            transition: width 0.3s ease-in-out;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.3);
            z-index: 1000;
        }
        .sidebar.collapsed {
            width: 50px; /* Reduced width */
        }
        .sidebar .sidebar-label {
            display: inline-block; /* Show labels by default */
            transition: opacity 0.3s ease-in-out;
        }

        .sidebar.collapsed .sidebar-label {
            opacity: 0; /* Hide labels in collapsed state */
            pointer-events: none; /* Prevent interaction with hidden text */
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 10px; /* Space between icon and label */
            padding: 10px;
        }
        .sidebar .nav-link.active {
            background-color: #0badd3;
        }
        .sidebar.collapsed .nav-link {
            width: 50px; /* Match the collapsed sidebar width */
            text-align: center; /* Center the icon */
            padding: 10px; /* Adjust spacing to keep it visually balanced */
        }

        .sidebar.collapsed .nav-link.active {
            background-color: #0badd3; /* Highlight color */
            width: 50px; /* Restrict highlight to collapsed width */
        }

        .sidebar.collapsed .nav-text {
            opacity: 0; /* Hide text when collapsed */
            visibility: hidden; /* Remove it from the layout */
            width: 0;
            overflow: hidden; /* Prevent any wrapping issues */
        }


        .navbar {
            margin: 5px;
            margin-top: 0;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.3);
            background-color:rgb(255, 255, 255);
        }
        .navbar-brand {
            font-size: 1 rem;
            color: black;
        }
        .nav-link {
            display: flex;
            align-items: center;
            color: black;
            font-size: 14px;
        }
        .nav-link i {
            font-size: 20px; /* Icon size */
            margin-right: 10px; /* Spacing */
        }
       
        .nav-link:hover {
            background-color: #0badd3;
            color: white;
        }

        /* Header Area */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            color: white;
        }
        .company-logo {
            font-size: 1.2rem;
            color:  #c8da30;
            display: block;
        }
        .company-logo.hidden {
            display: none; /* Hide when collapsed */
        }
        .burger-menu {
            background-color: transparent;
            border: none;
            color: black;
            position: absolute;
            top: 10px;
            left: 10px; /* Ensure burger stays in place when collapsed */
        }
        .burger-menu:hover{
            color: #c8da30;
        }
        /* Main Content Styles */
        .content {
            margin-left: 200px; /* Adjust for expanded sidebar */
            transition: margin-left 0.3s ease-in-out;
        }
        .sidebar.collapsed + .content {
            margin-left: 50px; /* Adjust for collapsed sidebar */
        }
        .sidebar:not(.collapsed) .burger-menu {
            position: absolute;
            top: 10px;
            right: -120px; /* Right-aligned when sidebar is expanded */
        }
        .sidebar.collapsed .burger-menu {
            position: absolute;
            top: 5px;
            left: 5px; /* Left-aligned when sidebar is collapsed */
        }
       
       
        .form-control {
            max-width: 300px;
        }
      
        .badge {
            font-size: 0.75rem;
            position: absolute;
            top: 5px;
            right: 5px;
        }
        .dropdown-menu .dropdown-item {
            background-color: transparent !important; /* Prevent background highlighting */
            color: #343a40; /* Neutral dark gray */
            transition: color 0.2s ease-in-out;
        }

        .dropdown-menu .dropdown-item:hover {
            color: #212529; /* Slightly darker text on hover */
            background-color: transparent !important; /* Ensures hover background is removed */
        }

        .dropdown-menu {
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* Soft shadow for floating effect */
        }

        .text-danger {
            color: #dc3545 !important; /* Keep consistent text color for danger links */
            text-decoration: none !important; /* Remove underlining for Logout */
        }

        /* Global Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .alert {
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Page Loader -->
    <div class="page-loader" id="pageLoader">
        <div class="spinner" id="spinner"></div>
    </div>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar vh-100" id="sidebar">
        <div class="header">
            <!-- Company Logo -->
            <img src="{{ asset('images/wng-logo.png') }}" alt="Company Logo" class="company-logo mt-2" id="companyLogo" style="max-height: 25px;">
            
            <!-- Burger Menu Button -->
            <button class="btn btn-outline burger-menu" id="sidebarToggle">☰</button>
        </div>
            <hr>
            
            @include('layouts.sidebar')
        </nav>

        <!-- Main Content -->
        <div class="content flex-grow-1" id="main-content">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg sticky-top bg-grey">
                <div class="container-fluid">
                    <!-- Navbar Brand -->
                    <span class="navbar-brand">@yield('navbar-title')</span>

                    @if(Auth::check() && Auth::user()->hasRole('super-admin'))
                        <form action="{{ route('admin.setDepartment') }}" method="POST" class="mb-3 px-2">
                            @csrf
                            <div class="form-group">
                                <label for="active_department" class="form-label lead text-dark fw-bold">SELECT DEPARTMENT TO ACCESS</label>
                                <select name="active_department" id="active_department" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="">-- Select Department --</option>
                                    <option value="stores" {{ session('active_department') === 'stores' ? 'selected' : '' }}>Stores</option>
                                    <option value="projects" {{ session('active_department') === 'projects' ? 'selected' : '' }}>Projects</option>
                                    <!-- <option value="procurement" {{ session('active_department') === 'procurement' ? 'selected' : '' }}>Procurement</option>
                                    <option value="HR" {{ session('active_department') === 'HR' ? 'selected' : '' }}>HR</option>
                                    <option value="IT" {{ session('active_department') === 'IT' ? 'selected' : '' }}>IT</option> -->
                                </select>
                            </div>
                        </form>
                    @endif
                    <!-- Right-Side Icons -->
                    <ul class="navbar-nav ms-auto">
                               <!-- Displayin the name of the logged in user -->
                        @auth
                        <li class="nav-item">
                            <a class="nav-link" href="#"> {{ auth()->user()->name }}</a>
                        </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                          
                        @endauth
                        <!-- Notifications in Master Layout -->
                        @if(auth()->user()->role === 'store')
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-bell"></i>
                                <span class="badge bg-danger rounded-pill">{{ $lowStockItems->count() ?? 0 }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown" style="max-height: 300px; overflow-y: auto;">
                                @forelse ($lowStockItems as $item)
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            {{ $item->sku }}: {{ $item->item_name }} — Only {{ $item->stock_on_hand }} left!
                                        </a>
                                    </li>
                                @empty
                                    <li>
                                        <a class="dropdown-item text-muted" href="#">No items are low in stock.</a>
                                    </li>
                                @endforelse
                            </ul>
                        </li>
                        @endif


                        <!-- Profile -->
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown" style="box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);">
                                <li><a class="dropdown-item" href="#" style="color: #343a40; background-color: transparent;">View Profile</a></li>
                                <li><a class="dropdown-item" href="#" style="color: #343a40; background-color: transparent;">Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li class="nav-item text-center">
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-link nav-link text-danger" style="text-decoration: none; padding: 10;">
                                            <i class="bi bi-box-arrow-right"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                        </li>
                    </ul>
                </div>
            </nav>



            <!-- Flash Message -->
            <div class="container mt-3">
                @if(session('success'))
                    <div id="flash-message" class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div id="flash-message" class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->has('error'))
                    <div class="alert alert-danger">
                        {{ $errors->first('error') }}
                    </div>
                @endif
            </div>
                @yield('content')
        </div>
    </div>
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const companyLogo = document.getElementById('companyLogo');

            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('collapsed');
            
            // Hide or show the company logo based on sidebar state
            if (sidebar.classList.contains('collapsed')) {
                companyLogo.classList.add('hidden'); // Hide logo
            } else {
                companyLogo.classList.remove('hidden'); // Show logo
            }
        });
                    // Set timeout for flash message
            setTimeout(() => {
                const flashMessage = document.getElementById('flash-message');
                if (flashMessage) {
                    flashMessage.style.transition = 'opacity 0.5s ease-out';
                    flashMessage.style.opacity = '0';
                    setTimeout(() => flashMessage.remove(), 500); // Remove from DOM after fade-out
                }
            }, 3000); // Adjust timeout duration (in milliseconds)

            setTimeout(() => {
                const errorAlert = document.querySelector('.alert-danger');
                if (errorAlert) {
                    errorAlert.style.transition = 'opacity 0.5s ease-out';
                    errorAlert.style.opacity = '0';
                    setTimeout(() => errorAlert.remove(), 500); // Remove from DOM
                }
            }, 3000); // Duration in milliseconds (3 seconds)


    </script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Bootstrap Bundle JS (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Error Handler for Beautiful 403 Modals -->
<script src="{{ asset('js/error-handler.js') }}"></script>
    
    @stack('scripts')
    @stack('modals')
    
    <!-- Include Unauthorized Modal Component -->
    @include('components.unauthorized-modal')
    
    <script>
        // Hide loader when page is fully loaded
        window.addEventListener('load', function() {
            const spinner = document.getElementById('spinner');
            if (spinner) spinner.classList.add('complete');
            
            // Hide loader after animation completes
            setTimeout(() => {
                const pageLoader = document.getElementById('pageLoader');
                if (pageLoader) pageLoader.style.display = 'none';
            }, 600);
        });

        // Session Expiration Handler
        $(document).ready(function() {
            // Set up CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let sessionWarningShown = false;
            let sessionExpired = false;

            // Function to handle session expiration
            function handleSessionExpiration(message = 'Your session has expired. Please log in again.') {
                if (sessionExpired) return; // Prevent multiple redirects
                sessionExpired = true;
                
                // Show a more user-friendly notification
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Session Expired',
                        text: message,
                        icon: 'warning',
                        confirmButtonText: 'Login Again',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(() => {
                        window.location.href = '{{ route("login") }}';
                    });
                } else {
                    alert(message);
                    window.location.href = '{{ route("login") }}';
                }
            }

            // Global AJAX error handler for session expiration
            $(document).ajaxError(function(event, xhr, settings) {
                if (xhr.status === 401) {
                    let response = xhr.responseJSON;
                    let message = response && response.message ? response.message : 'Your session has expired. Please log in again.';
                    handleSessionExpiration(message);
                }
            });

            // Check session status periodically (every 2 minutes)
            setInterval(function() {
                if (sessionExpired) return; // Don't check if already expired
                
                $.ajax({
                    url: '{{ route("session.check") }}',
                    type: 'GET',
                    timeout: 10000, // 10 second timeout
                    success: function(response) {
                        if (!response.authenticated) {
                            handleSessionExpiration();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            handleSessionExpiration();
                        }
                        // Ignore other errors (network issues, etc.)
                    }
                });
            }, 120000); // 2 minutes = 120000 milliseconds

            // Warn user before session expires (2 minutes before expiration)
            @if(config('session.lifetime') > 2)
            setTimeout(function() {
                if (sessionExpired || sessionWarningShown) return;
                sessionWarningShown = true;
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Session Expiring Soon',
                        text: 'Your session will expire in 2 minutes. Would you like to extend it?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Extend Session',
                        cancelButtonText: 'Let it expire'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '{{ route("session.extend") }}',
                                type: 'POST',
                                success: function() {
                                    Swal.fire('Success', 'Your session has been extended', 'success');
                                    sessionWarningShown = false; // Allow future warnings
                                },
                                error: function() {
                                    Swal.fire('Error', 'Failed to extend session', 'error');
                                }
                            });
                        }
                    });
                } else {
                    if (confirm('Your session will expire in 2 minutes. Click OK to extend your session.')) {
                        $.ajax({
                            url: '{{ route("session.extend") }}',
                            type: 'POST',
                            success: function() {
                                alert('Session extended successfully');
                                sessionWarningShown = false; // Allow future warnings
                            },
                            error: function() {
                                alert('Failed to extend session');
                            }
                        });
                    }
                }
            }, {{ (config('session.lifetime') - 2) * 60000 }}); // Convert minutes to milliseconds, minus 2 minutes
            @endif

            // Handle browser tab visibility changes
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden && !sessionExpired) {
                    // Tab became visible, check session status
                    $.ajax({
                        url: '{{ route("session.check") }}',
                        type: 'GET',
                        success: function(response) {
                            if (!response.authenticated) {
                                handleSessionExpiration('Your session expired while you were away. Please log in again.');
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 401) {
                                handleSessionExpiration('Your session expired while you were away. Please log in again.');
                            }
                        }
                    });
                }
            });

            // Handle page unload/reload to prevent session checks
            window.addEventListener('beforeunload', function() {
                sessionExpired = true; // Prevent session checks during page transition
            });
        });
    </script>
</body>
</html>
