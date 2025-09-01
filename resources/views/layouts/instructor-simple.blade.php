<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Learning Platform') }} - @yield('title', 'Instructor Panel')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @if(app()->getLocale() === 'ar')
    <!-- Arabic Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600&display=swap" rel="stylesheet">
    @endif

    <!-- Bootstrap CSS -->
    @if(app()->getLocale() === 'ar')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        body {
            font-family: @if(app()->getLocale()==='ar') 'Cairo', @else 'Figtree'@endif, sans-serif;
            background-color: #f8f9fa;
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.5rem;
        }

        .main-content {
            min-height: calc(100vh - 200px);
            padding: 2rem 0;
        }

        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e9ecef;
            font-weight: 600;
            padding: 1rem 1.25rem;
        }

        .btn {
            border-radius: 0.375rem;
        }

        .form-control,
        .form-select {
            border-radius: 0.375rem;
        }

        .breadcrumb {
            background: none;
            padding: 0;
            margin-bottom: 1.5rem;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            content: @if(app()->getLocale()==='ar') "‹"@else "›"@endif;
        }

        .instructor-nav {
            background-color: #fff;
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 0;
            margin-bottom: 2rem;
        }

        .instructor-nav .nav-link {
            color: #495057;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            margin: 0 0.25rem;
        }

        .instructor-nav .nav-link:hover,
        .instructor-nav .nav-link.active {
            background-color: #007bff;
            color: #fff;
        }

        /* RTL Support */
        @if(app()->getLocale()==='ar') .text-start {
            text-align: right !important;
        }

        .text-end {
            text-align: left !important;
        }

        .me-auto {
            margin-right: 0 !important;
            margin-left: auto !important;
        }

        .ms-auto {
            margin-left: 0 !important;
            margin-right: auto !important;
        }

        @endif
    </style>

    @stack('styles')
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-graduation-cap me-2"></i>
                {{ config('app.name', 'Learning Platform') }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">{{ __('app.home') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('courses.index') }}">{{ __('app.courses') }}</a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('instructor.dashboard') }}">{{ __('app.instructor_panel') }}</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">{{ __('app.logout') }}</button>
                                </form>
                            </li>
                        </ul>
                    </li>

                    <!-- Language Switcher -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-globe me-1"></i>
                            {{ app()->getLocale() === 'ar' ? 'العربية' : 'English' }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ url('/lang/ar') }}">العربية</a></li>
                            <li><a class="dropdown-item" href="{{ url('/lang/en') }}">English</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Instructor Navigation -->
    <div class="instructor-nav">
        <div class="container">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('instructor.dashboard') ? 'active' : '' }}"
                        href="{{ route('instructor.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i>{{ __('app.dashboard') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('instructor.courses.*') ? 'active' : '' }}"
                        href="{{ route('instructor.courses.index') }}">
                        <i class="fas fa-book me-2"></i>{{ __('app.my_courses') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('instructor.categories.*') ? 'active' : '' }}"
                        href="{{ route('instructor.categories.index') }}">
                        <i class="fas fa-tags me-2"></i>{{ __('app.categories') }}
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <!-- Breadcrumb -->
            @if(isset($breadcrumbs) && count($breadcrumbs) > 0)
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    @foreach($breadcrumbs as $breadcrumb)
                    @if($loop->last)
                    <li class="breadcrumb-item active">{{ $breadcrumb['title'] }}</li>
                    @else
                    <li class="breadcrumb-item">
                        <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                    </li>
                    @endif
                    @endforeach
                </ol>
            </nav>
            @endif

            <!-- Alert Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>{{ __('app.validation_errors') }}</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>{{ config('app.name', 'Learning Platform') }}</h5>
                    <p class="mb-0">{{ __('app.footer_description') }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('app.all_rights_reserved') }}
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>