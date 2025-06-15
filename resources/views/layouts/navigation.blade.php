<nav x-data="{ open: false }" class="navbar navbar-expand-lg navbar-light bg-light dark:bg-dark border-bottom shadow-sm sticky-top">
    <!-- Primary Navigation Menu -->
    <div class="container-fluid px-4 py-2">
        <a href="{{ route('dashboard') }}" class="navbar-brand d-flex align-items-center">
            <x-application-logo class="h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
            <span class="ms-2 fw-bold text-dark dark:text-light">{{ __('Inventory Dashboard') }}</span>
        </a>
        <button class="navbar-toggler" type="button" @click="open = ! open">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div :class="{'show': open}" class="collapse navbar-collapse">
            <!-- Navigation Links -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-house-door me-2"></i> {{ __('Dashboard') }}
                    </a>
                </li>
            </ul>

            <!-- Settings Dropdown -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <button class="btn btn-light dropdown-toggle" id="settingsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-2"></i> {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="settingsDropdown">
                        <li>
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <i class="bi bi-person me-2"></i> {{ __('Profile') }}
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> {{ __('Log Out') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="d-lg-none bg-light dark:bg-dark">
        <ul class="list-unstyled px-3 py-2 border-top border-gray-300 dark:border-gray-600">
            <li>
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door me-2"></i> {{ __('Dashboard') }}
                </a>
            </li>
            <li class="mt-3">
                <a href="{{ route('profile.edit') }}" class="nav-link">
                    <i class="bi bi-person me-2"></i> {{ __('Profile') }}
                </a>
            </li>
            <li class="mt-3 text-danger">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link text-danger">
                        <i class="bi bi-box-arrow-right me-2"></i> {{ __('Log Out') }}
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>