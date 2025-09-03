<nav class="top-nav">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Search Bar -->
            <div class="search-bar">
                <form action="#" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="{{ __('Search...') }}" aria-label="Search">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Right Side Navigation -->
            <div class="d-flex align-items-center">
                <!-- Notifications -->
                <div class="nav-item dropdown me-3">
                    <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            3
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <h6 class="dropdown-header">{{ __('Notifications') }}</h6>
                        <a class="dropdown-item" href="#">{{ __('New student enrolled') }}</a>
                        <a class="dropdown-item" href="#">{{ __('New message received') }}</a>
                        <a class="dropdown-item" href="#">{{ __('Course review received') }}</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-center" href="#">{{ __('View all') }}</a>
                    </div>
                </div>
                
                <!-- Messages -->
                <div class="nav-item dropdown me-3">
                    <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-envelope"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">
                            5
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <h6 class="dropdown-header">{{ __('Messages') }}</h6>
                        <a class="dropdown-item" href="#">{{ __('New message from John') }}</a>
                        <a class="dropdown-item" href="#">{{ __('Course inquiry') }}</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-center" href="#">{{ __('View all messages') }}</a>
                    </div>
                </div>
                
                <!-- User Dropdown -->
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="rounded-circle me-2" width="32" height="32">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> {{ __('Profile') }}</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> {{ __('Settings') }}</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i> {{ __('Logout') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
