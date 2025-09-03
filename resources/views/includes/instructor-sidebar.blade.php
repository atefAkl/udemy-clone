<div class="dashboard-sidebar" id="dashboardSidebar">
    <!-- Brand -->
    <div class="sidebar-brand bg-secondary">
        <h5 class="text-white">
            <i class="fas fa-graduation-cap" style="margin-inline-end: 10px;"></i>
            {{ __('app.app_name') }}
        </h5>
        <small class="opacity-75 text-white">Instructor Dashboard</small>
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
    <div class="sidebar-header">
        <h6 class="mb-0">{{ __('sidebar.navigation') }}</h6>
    </div>
    <nav class="nav flex-column">
        @php
        $isUserManagementActive = request()->routeIs('admin.users.*');
        $isContentManagementActive = request()->routeIs('admin.courses.*', 'admin.categories.*', 'admin.reviews.*');
        @endphp

        <!-- User Management Section -->
        <div class="nav-section">
            <div class="nav-header-collapsible {{ $isUserManagementActive ? 'active' : '' }}"
                data-bs-toggle="collapse"
                data-bs-target="#userManagementMenu"
                aria-expanded="{{ $isUserManagementActive ? 'true' : 'false' }}">
                <i class="fa fa-users me-2"></i>
                <span>{{ __('instructor.courses_management') }}</span>
                <i class="fa fa-chevron-down collapse-icon ms-auto"></i>
            </div>
            <div class="collapse {{ $isUserManagementActive ? 'show' : '' }}" id="userManagementMenu">
                <ul class="nav flex-column nav-submenu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users.index') && !request('role') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                            <i class="fa fa-users"></i>{{ __('instructor.my_courses') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users.index') && request('role') === 'instructor' ? 'active' : '' }}" href="{{ route('admin.users.index', ['search'=>'', 'role'=>'instructor']) }}">
                            <i class="fa fa-user-tie"></i>{{ __('instructor.incoming_exams') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.users.index') && request('role') === 'student' ? 'active' : '' }}" href="{{ route('admin.users.index', ['search'=>'','role'=>'student']) }}">
                            <i class="fa fa-user-graduate"></i>{{ __('instructor.certificates') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Content Management Section -->
        <div class="nav-section">
            <div class="nav-header-collapsible {{ $isContentManagementActive ? 'active' : '' }}"
                data-bs-toggle="collapse"
                data-bs-target="#contentManagementMenu"
                aria-expanded="{{ $isContentManagementActive ? 'true' : 'false' }}">
                <i class="fa fa-book me-2"></i>
                <span>{{ __('instructor.account') }}</span>
                <i class="fa fa-chevron-down collapse-icon ms-auto"></i>
            </div>
            <div class="collapse {{ $isContentManagementActive ? 'show' : '' }}" id="contentManagementMenu">
                <ul class="nav flex-column nav-submenu">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.courses.index') ? 'active' : '' }}" href="{{ route('admin.courses.index') }}">
                            <i class="fa fa-book"></i>{{ __('instructor.earnings') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                            <i class="fa fa-tags"></i>{{ __('instructor.coupons') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}">
                            <i class="fa fa-star"></i>{{ __('instructor.reviews') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar Footer -->
    <div class="quick-actions mt-auto p-3 border-top">
        <!-- Language Switcher -->
        <div class="mb-1">
            <label class="form-label small text-muted">{{ __('Quick Actions') }}</label>
            <div class="dropdown my-0 py-0">
                <button class="btn py-0" type="button" data-bs-toggle="dropdown">
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

            <a href="{{ route('home') }}" class="btn btn-sm py-0">
                <i class="fas fa-home" style="margin-inline-end: 10px;"></i>{{ __('sidebar.back_to_site') }}
            </a>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm py-0">
                    <i class="fas fa-sign-out-alt" style="margin-inline-end: 10px;"></i>{{ __('app.logout') }}
                </button>
            </form>
        </div>
    </div>
</div>