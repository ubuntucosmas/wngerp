<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/wng-logo.png') }}" type="image/png">
    <title>@yield('title', 'Woodnork Green')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


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
    <!-- Bootstrap Bundle JS (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')


</body>
</html>
