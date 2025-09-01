<!DOCTYPE html>
<html lang="{{ session('locale', 'ar') }}" dir="{{ session('locale', 'ar') === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ config('app.name') }}</title>

    <!-- Bootstrap CSS -->
    @if(session('locale', 'ar') === 'ar')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Cairo Font for Arabic -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>

<body>
    <!-- Sidebar Toggle Button (Mobile) -->
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Widgets Toggle Button (Mobile/Tablet) -->
    <button class="widgets-toggle" id="widgetsToggle">
        <i class="fas fa-th-list"></i>
    </button>

    <!-- Sidebar -->
    <div class="dashboard-sidebar" id="dashboardSidebar">
        <!-- Brand -->
        <div class="sidebar-brand">
            <h5>
                <i class="fas fa-graduation-cap" style="margin-inline-end: 10px;"></i>
                {{ config('app.name') }}
            </h5>
            <small class="opacity-75">Dashboard</small>
        </div>

        <!-- User Info -->
        <div class="sidebar-user">
            <div class="d-flex align-items-center">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=007bff&color=fff" alt="{{ Auth::user()->name }}">
                <div>
                    <div class="fw-semibold">{{ Auth::user()->name }}</div>
                    <small class="text-muted">{{ ucfirst(Auth::user()->role) }}</small>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="sidebar-header p-3 border-bottom">
            <h6 class="mb-0 text-muted">{{ __('app.navigation') }}</h6>
        </div>
        <nav class="nav flex-column p-2">
            @include('includes.sidebar')
        </nav>

        <!-- Sidebar Footer -->
        <div class="mt-auto p-3 border-top">
            <!-- Language Switcher -->
            <div class="mb-3">
                <label class="form-label small text-muted">{{ __('app.language') }}</label>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-globe" style="margin-inline-end: 10px;"></i>
                        {{ session('locale', 'ar') === 'ar' ? 'العربية' : 'English' }}
                    </button>
                    <ul class="dropdown-menu w-100">
                        <li><a class="dropdown-item" href="{{ route('lang.switch', 'ar') }}">
                                <i class="fas fa-flag" style="margin-inline-end: 10px;"></i>العربية
                            </a></li>
                        <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">
                                <i class="fas fa-flag" style="margin-inline-end: 10px;"></i>English
                            </a></li>
                    </ul>
                </div>
            </div>

            <div class="d-grid gap-2">
                <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-home" style="margin-inline-end: 10px;"></i>{{ __('app.back_to_site') }}
                </a>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                        <i class="fas fa-sign-out-alt" style="margin-inline-end: 10px;"></i>{{ __('app.logout') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="dashboard-main" id="dashboardMain">
        <!-- Header -->
        <div class="dashboard-header px-4 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 small">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="text-decoration-none">
                                    <i class="fas fa-home"></i> {{ __('app.home') }}
                                </a>
                            </li>
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <!-- Search Form -->
                    <form class="d-flex" method="GET" action="{{ route('search') }}">
                        <input class="form-control form-control-sm me-2" type="search" placeholder="{{ __('app.search') }}" aria-label="Search" name="query">
                        <button class="btn btn-sm btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-4">
            <!-- Flash Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle" style="margin-inline-end: 10px;"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle" style="margin-inline-end: 10px;"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Widgets Sidebar -->
    <div class="widgets-sidebar" id="widgetsSidebar">
        @yield('widgets')
    </div>

    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Dashboard JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('dashboardSidebar');
            const widgets = document.getElementById('widgetsSidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const widgetsToggle = document.getElementById('widgetsToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const isRTL = document.documentElement.dir === 'rtl';

            // Sidebar toggle functionality
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    sidebarOverlay.classList.toggle('show');
                });
            }

            // Widgets toggle functionality
            if (widgetsToggle) {
                widgetsToggle.addEventListener('click', function() {
                    widgets.classList.toggle('collapsed');
                });
            }

            // Close sidebar when clicking overlay
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.add('collapsed');
                    sidebarOverlay.classList.remove('show');
                });
            }

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    sidebar.classList.remove('collapsed');
                    sidebarOverlay.classList.remove('show');
                }
                if (window.innerWidth >= 1200) {
                    widgets.classList.remove('collapsed');
                }
            });

            // Initialize based on screen size
            if (window.innerWidth < 768) {
                sidebar.classList.add('collapsed');
            }
            if (window.innerWidth < 1200) {
                widgets.classList.add('collapsed');
            }
        });
    </script>

    @stack('scripts')
</body>

</html>