<!DOCTYPE html>
<html lang="{{ session('locale', 'ar') }}" dir="{{ session('locale', 'ar') === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ __('app.app_name') }}</title>
    @if(session('locale', 'ar') === 'ar')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/student-dashboard.css') }}" rel="stylesheet">
</head>

<body class="p-0">
    <!-- Top Navbar -->
    <nav class=" navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid px-5">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                <i class="fa-solid fa-graduation-cap me-2"></i>{{ __('app.app_name') }}
            </a>
            <ul class="navbar-nav ms-auto d-flex flex-row gap-2 align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="#" title="{{ __('student.my_enrollments') }}"><i class="fa-solid fa-list"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" title="{{ __('student.wishlist') }}"><i class="fa-solid fa-heart"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" title="{{ __('student.cart') }}"><i class="fa-solid fa-cart-shopping"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" title="{{ __('student.notifications') }}"><i class="fa-solid fa-bell"></i></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user-circle"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                            <li><a class="dropdown-item" href="#">{{ __('student.profile') }}</a></li>
                            <li><a class="dropdown-item" href="#">{{ __('student.settings') }}</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><button class="dropdown-item" type="submit">{{ __('student.logout') }}</a></li>
                        </form>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container-fluid m-0 p-0">
        <!-- Welcome Section -->
        <div class="bg-dark p-5">
            <div class="container">

                <h2 class="fs-1 fw-bold mb-2 text-white">{{ __('student.my_learning') }}!</h2>
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
            </div>
        </div>

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer mt-auto">
        <div class="container">
            <div class="footer-links">
                <ul>
                    <li><a href="#">{{ __('student.udemy_com') }}</a></li>
                    <li><a href="#">{{ __('student.about_us') }}</a></li>
                    <li><a href="#">{{ __('student.events') }}</a></li>
                    <li><a href="#">{{ __('student.partners') }}</a></li>
                </ul>
                <ul>
                    <li><a href="#">{{ __('student.contact_us') }}</a></li>
                    <li><a href="#">{{ __('student.help') }}</a></li>
                    <li><a href="#">{{ __('student.terms') }}</a></li>
                    <li><a href="#">{{ __('student.privacy') }}</a></li>
                </ul>
                <ul>
                    <li><a href="#">{{ __('student.cookie_settings') }}</a></li>
                    <li><a href="#">{{ __('student.sitemap') }}</a></li>
                </ul>
                <ul class="footer-social">
                    <li><a href="#"><i class="fab fa-linkedin"></i></a></li>
                    <li><a href="#"><i class="fab fa-facebook"></i></a></li>
                    <li><a href="#"><i class="fab fa-x-twitter"></i></a></li>
                    <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                </ul>
                <ul>
                    <li><button class="btn btn-outline-light btn-sm"><a href="{{ route('lang.switch', session('locale', 'ar') === 'ar' ? 'en' : 'ar') }}"><i class="fa-solid fa-globe"></i> {{ session('locale', 'ar') === 'ar' ? 'العربية' : 'English' }}</a></button></li>
                </ul>
            </div>
            <div class="footer-bottom">
                &copy; 2025 {{ __('app.app_name') }}
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/student-dashboard.js') }}"></script>
    @stack('scripts')
</body>

</html>