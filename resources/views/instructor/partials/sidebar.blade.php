<aside class="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('instructor.dashboard') }}" class="logo">
            @if(session('locale', 'ar') === 'ar')
                <h2>{{ config('app.name_ar', config('app.name')) }}</h2>
            @else
                <h2>{{ config('app.name') }}</h2>
            @endif
        </a>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('instructor.dashboard') }}" class="nav-link {{ request()->routeIs('instructor.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    <span>{{ __('Dashboard') }}</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('instructor.courses.index') }}" class="nav-link {{ request()->routeIs('instructor.courses.*') ? 'active' : '' }}">
                    <i class="fas fa-book me-2"></i>
                    <span>{{ __('My Courses') }}</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-users me-2"></i>
                    <span>{{ __('Students') }}</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-chart-line me-2"></i>
                    <span>{{ __('Analytics') }}</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-comments me-2"></i>
                    <span>{{ __('Messages') }}</span>
                </a>
            </li>
            
            <li class="nav-item mt-4">
                <a href="#" class="nav-link">
                    <i class="fas fa-cog me-2"></i>
                    <span>{{ __('Settings') }}</span>
                </a>
            </li>
        </ul>
    </nav>
    
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link nav-link">
                <i class="fas fa-sign-out-alt me-2"></i>
                <span>{{ __('Logout') }}</span>
            </button>
        </form>
    </div>
</aside>
