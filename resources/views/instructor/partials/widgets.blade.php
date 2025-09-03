<!-- Stats Widgets -->
<div class="widgets">
    <!-- Quick Stats -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0">{{ __('Quick Stats') }}</h6>
        </div>
        <div class="card-body">
            <div class="stat-item mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="stat-label">{{ __('Total Students') }}</span>
                    <span class="stat-value">1,245</span>
                </div>
                <div class="progress mt-2" style="height: 5px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            
            <div class="stat-item mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="stat-label">{{ __('Total Courses') }}</span>
                    <span class="stat-value">24</span>
                </div>
                <div class="progress mt-2" style="height: 5px;">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            
            <div class="stat-item">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="stat-label">{{ __('Total Earnings') }}</span>
                    <span class="stat-value">$12,450</span>
                </div>
                <div class="progress mt-2" style="height: 5px;">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activities -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0">{{ __('Recent Activities') }}</h6>
        </div>
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <small class="text-muted">2 min ago</small>
                    <p class="mb-0">New student enrolled in "Web Development"</p>
                </li>
                <li class="list-group-item">
                    <small class="text-muted">1 hour ago</small>
                    <p class="mb-0">New review received for "JavaScript Basics"</p>
                </li>
                <li class="list-group-item">
                    <small class="text-muted">3 hours ago</small>
                    <p class="mb-0">Course "Advanced CSS" was approved</p>
                </li>
                <li class="list-group-item">
                    <small class="text-muted">1 day ago</small>
                    <p class="mb-0">New message from a student</p>
                </li>
            </ul>
        </div>
        <div class="card-footer text-center">
            <a href="#" class="btn btn-sm btn-link">{{ __('View All') }}</a>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">{{ __('Quick Actions') }}</h6>
        </div>
        <div class="card-body">
            <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary btn-sm w-100 mb-2">
                <i class="fas fa-plus me-1"></i> {{ __('New Course') }}
            </a>
            <a href="#" class="btn btn-outline-secondary btn-sm w-100 mb-2">
                <i class="fas fa-upload me-1"></i> {{ __('Upload Resource') }}
            </a>
            <a href="#" class="btn btn-outline-secondary btn-sm w-100">
                <i class="fas fa-bullhorn me-1"></i> {{ __('Announcement') }}
            </a>
        </div>
    </div>
</div>
