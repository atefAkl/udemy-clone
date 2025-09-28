<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('app.app_name') ?? 'Learning Platform' }} - @yield('title', 'Online Learning')</title>

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
            font-family: 'Figtree', sans-serif;
        }

        [dir="rtl"] body {
            font-family: 'Cairo', sans-serif;
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.5rem;
        }

        .course-card {
            transition: transform 0.2s;
        }

        .course-card:hover {
            transform: translateY(-5px);
        }

        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .footer {
            background-color: #2c3e50;
            color: white;
        }

        .language-switcher .dropdown-toggle::after {
            display: none;
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand text-primary" href="{{ route('home') }}">
                <i class="fa-solid fa-graduation-cap" style="margin-inline-end: 10px"></i>
                {{ __('app.app_name') ?? 'Learning Platform' }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav {{ app()->getLocale() === 'ar' ? 'ms-auto' : 'me-auto' }}">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">{{ __('app.home') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('courses.index') }}">{{ __('app.courses') }}</a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <!-- Language Switcher -->
                    <li class="nav-item dropdown language-switcher {{ app()->getLocale() === 'ar' ? 'ms-3' : 'me-3' }}">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-globe {{ app()->getLocale() === 'ar' ? 'ms-1' : 'me-1' }}"></i>
                            {{ config('app.supported_locales.' . app()->getLocale() . '.name') }}
                        </a>
                        <ul class="dropdown-menu">
                            @foreach(config('app.supported_locales') as $code => $locale)
                            @if($code !== app()->getLocale())
                            <li>
                                <a class="dropdown-item" href="{{ url()->current() }}?lang={{ $code }}">
                                    <i class="fa-solid fa-language" style="margin-inline-end: 10px"></i>
                                    {{ $locale['name'] }}
                                </a>
                            </li>
                            @endif
                            @endforeach
                        </ul>
                    </li>

                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('app.login') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-white px-3 {{ app()->getLocale() === 'ar' ? 'me-2' : 'ms-2' }}" href="{{ route('register') }}">{{ __('app.sign_up') }}</a>
                    </li>
                    @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="rounded-circle {{ app()->getLocale() === 'ar' ? 'ms-1' : 'me-1' }}" width="30" height="30">
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            @if(auth()->user()->isStudent())
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="fa-solid fa-speedometer" style="margin-inline-end: 10px"></i>{{ __('app.dashboard') }}
                                </a></li>
                            @endif

                            @if(auth()->user()->isInstructor() || auth()->user()->isInstructor())
                            <li><a class="dropdown-item" href="{{ route('instructor.dashboard') }}">
                                    <i class="fa-solid fa-person-workspace" style="margin-inline-end: 10px"></i>{{ __('app.instructor_dashboard') }}
                                </a></li>
                            @endif

                            @if(auth()->user()->isAdmin())
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="fa-solid fa-gear" style="margin-inline-end: 10px"></i>{{ __('app.admin') }} {{ __('app.dashboard') }}
                                </a></li>
                            @endif

                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fa-solid fa-box-arrow-right" style="margin-inline-end: 10px"></i>{{ __('app.logout') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main style="min-height: calc(100vh - 200px)">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-0" role="alert">
            <div class="container">
                <i class="fa-solid fa-check-circle" style="margin-inline-end: 10px"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-0" role="alert">
            <div class="container">
                <i class="fa-solid fa-exclamation-triangle" style="margin-inline-end: 10px"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show m-0" role="alert">
            <div class="container">
                <i class="fa-solid fa-info-circle" style="margin-inline-end: 10px"></i>{{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>{{ config('app.name', 'Learning Platform') }}</h5>
                    <p class="mb-0">{{ __('app.empowering_learners') }}</p>
                </div>
                <div class="col-md-6 {{ app()->getLocale() === 'ar' ? 'text-md-start' : 'text-md-end' }}">
                    <p class="mb-0">&copy; {{ date('Y') }} {{ __('app.all_rights_reserved') }}</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>