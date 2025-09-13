@extends('layouts.instructor-wide')

@section('title', __('instructor.instructor_dashboard'))

@push('styles')
<style>
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: none;
        border-radius: 0.5rem;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    }

    .bg-soft-primary {
        background-color: rgba(78, 115, 223, 0.1) !important;
    }

    .bg-soft-success {
        background-color: rgba(28, 200, 138, 0.1) !important;
    }

    .bg-soft-info {
        background-color: rgba(54, 185, 204, 0.1) !important;
    }

    .bg-soft-warning {
        background-color: rgba(246, 194, 62, 0.1) !important;
    }

    .bg-soft-danger {
        background-color: rgba(231, 74, 59, 0.1) !important;
    }

    .text-soft-primary {
        color: rgba(78, 115, 223, 0.8) !important;
    }
</style>
@endpush


@section('content')
<div class="container px-4">
    <!-- Welcome Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">{{ __('instructor.welcome_back') }}, {{ Auth::user()->name }}!</h1>
            <p class="text-muted mb-0">{{ __('instructor.dashboard_overview') }}</p>
        </div>
        <div>
            <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>{{ __('instructor.create_course') }}
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Courses -->
        <div class="col-md-6 col-xl-3">
            <div class="dashboard-card p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="card-icon primary">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="mt-3">
                            <div class="card-value">{{ $stats['total_courses'] ?? 0 }}</div>
                            <div class="card-label">{{ __('instructor.total_courses') }}</div>
                        </div>
                    </div>
                    <div class="card-trend text-success">
                        <i class="fas fa-arrow-up me-1"></i> 12%
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top">
                    <span class="badge bg-soft-success text-success">
                        <i class="fas fa-check-circle me-1"></i>
                        {{ $stats['published_courses'] ?? 0 }} {{ __('instructor.published') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Total Students -->
        <div class="col-md-6 col-xl-3">
            <div class="dashboard-card p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="card-icon success">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="mt-3">
                            <div class="card-value">{{ $stats['total_students'] ?? 0 }}</div>
                            <div class="card-label">{{ __('instructor.total_students') }}</div>
                        </div>
                    </div>
                    <div class="card-trend text-success">
                        <i class="fas fa-arrow-up me-1"></i> 8.2%
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top">
                    <span class="text-muted small">
                        <i class="fas fa-user-plus me-1"></i> 24 {{ __('instructor.new_this_month') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Total Earnings -->
        <div class="col-md-6 col-xl-3">
            <div class="dashboard-card p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="card-icon warning">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="mt-3">
                            <div class="card-value">${{ number_format($stats['total_revenue'] ?? 0, 0) }}</div>
                            <div class="card-label">{{ __('instructor.total_earnings') }}</div>
                        </div>
                    </div>
                    <div class="card-trend text-success">
                        <i class="fas fa-arrow-up me-1"></i> 15.3%
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top">
                    <span class="text-muted small">
                        <i class="fas fa-calendar-alt me-1"></i> {{ __('instructor.this_month') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Average Rating -->
        <div class="col-md-6 col-xl-3">
            <div class="dashboard-card p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="card-icon danger">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="mt-3">
                            <div class="card-value">4.8 <small class="text-muted">/ 5.0</small></div>
                            <div class="card-label">{{ __('instructor.avg_rating') }}</div>
                        </div>
                    </div>
                    <div class="card-trend text-success">
                        <i class="fas fa-arrow-up me-1"></i> 2.1%
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top">
                    <div class="text-warning">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <small class="text-muted ms-2">(1,024 {{ __('instructor.ratings') }})</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row g-4">
        <!-- Recent Courses -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100  overflow-auto">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                    <h6 class="mb-0 fw-bold">{{ __('instructor.recent_courses') }}</h6>
                    <a href="{{ route('instructor.courses.index') }}" class="btn btn-sm btn-outline-primary">
                        {{ __('instructor.view_all') }}
                    </a>
                </div>
                <div class="card-body p-0 overflow-auto">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>{{ __('instructor.course') }}</th>
                                    <th>{{ __('instructor.students') }}</th>
                                    <th>{{ __('instructor.rating') }}</th>
                                    <th>{{ __('instructor.status') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="">
                                @foreach($recentCourses ?? [] as $course)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $course['thumbnail'] ?? 'https://via.placeholder.com/60' }}"
                                                alt="{{ $course['title'] }}"
                                                class="rounded me-3"
                                                width="40" height="40" style="object-fit: cover;">
                                            <div>
                                                <h6 class="mb-0">{{ $course->title }}</h6>
                                                <small class="text-muted">{{ $course->category->name ?? 'Uncategorized' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $course->students->count() ?? 0 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="text-warning me-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <=($course['rating'] ?? 0))
                                                    <i class="fas fa-star"></i>
                                                    @elseif($i - 0.5 <= ($course['rating'] ?? 0))
                                                        <i class="fas fa-star-half-alt"></i>
                                                        @else
                                                        <i class="far fa-star"></i>
                                                        @endif
                                                        @endfor
                                            </span>
                                            <small class="text-muted">({{ $course['reviews'] ?? 0 }})</small>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                        $statusClass = [
                                        'published' => 'success',
                                        'draft' => 'secondary',
                                        'pending' => 'warning',
                                        'rejected' => 'danger'
                                        ][$course['status'] ?? 'draft'] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">
                                            {{ ucfirst($course['status'] ?? 'draft') }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-link text-muted" type="button"
                                                id="courseDropdown{{ $loop->index }}"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end"
                                                aria-labelledby="courseDropdown{{ $loop->index }}">
                                                <li>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="fas fa-eye me-2"></i>{{ __('instructor.view') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('instructor.courses.edit', $course->id) }}">
                                                        <i class="fas fa-edit me-2"></i>{{ __('instructor.edit') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="#">
                                                        <i class="fas fa-trash-alt me-2"></i>{{ __('instructor.delete') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('widgets')
<!-- Quick Stats -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white">
        <h6 class="mb-0">
            <i class="fa fa-speedometer text-success {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
            {{ __('instructor.quick_stats') }}
        </h6>
    </div>
    <div class="card-body">
        <div class="row text-center">
            <div class="col-6 mb-3">
                <div class="border-{{ session('locale', 'ar') === 'ar' ? 'start' : 'end' }}">
                    <h4 class="text-primary mb-1">4.7</h4>
                    <small class="text-muted">{{ __('instructor.average_rating') }}</small>
                </div>
            </div>
            <div class="col-6 mb-3">
                <h4 class="text-success mb-1">89%</h4>
                <small class="text-muted">{{ __('instructor.completion_rate') }}</small>
            </div>
            <div class="col-6">
                <div class="border-{{ session('locale', 'ar') === 'ar' ? 'start' : 'end' }}">
                    <h4 class="text-info mb-1">234</h4>
                    <small class="text-muted">{{ __('instructor.total_reviews') }}</small>
                </div>
            </div>
            <div class="col-6">
                <h4 class="text-warning mb-1">12</h4>
                <small class="text-muted">{{ __('instructor.this_month') }}</small>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white">
        <h6 class="mb-0">
            <i class="fa fa-activity text-info {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
            {{ __('app.recent_activity') }}
        </h6>
    </div>
    <div class="card-body">
        @php
        $activities = [
        [
        'type' => 'new_enrollment',
        'message' => __('app.new_student_enrolled'),
        'time' => __('app.one_hour_ago'),
        'icon' => 'fa fa-person-plus text-success'
        ],
        [
        'type' => 'new_review',
        'message' => __('app.new_five_star_review'),
        'time' => __('app.three_hours_ago'),
        'icon' => 'fa fa-star text-warning'
        ],
        [
        'type' => 'course_completed',
        'message' => __('app.student_completed_course'),
        'time' => __('app.yesterday'),
        'icon' => 'fa fa-trophy text-primary'
        ],
        [
        'type' => 'question',
        'message' => __('app.new_question_posted'),
        'time' => __('app.two_days_ago'),
        'icon' => 'fa fa-question-circle text-info'
        ]
        ];
        @endphp

        @foreach($activities as $activity)
        <div class="d-flex align-items-start mb-3 {{ !$loop->last ? 'pb-3 border-bottom' : '' }}">
            <div class="{{ session('locale', 'ar') === 'ar' ? 'ms-3' : 'me-3' }}">
                <i class="{{ $activity['icon'] }}"></i>
            </div>
            <div class="flex-grow-1">
                <p class="mb-1 small">{{ $activity['message'] }}</p>
                <small class="text-muted">{{ $activity['time'] }}</small>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Quick Actions -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h6 class="mb-0">
            <i class="fa fa-lightning text-warning {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
            {{ __('app.quick_actions') }}
        </h6>
    </div>
    <div class="card-body">
        <div class="d-grid gap-2">
            <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary">
                <i class="fa fa-plus {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('instructor.create_new_course') }}
            </a>
            <a href="{{ route('instructor.courses.index') }}" class="btn btn-outline-primary">
                <i class="fa fa-list {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('instructor.manage_courses_btn') }}
            </a>
            <a href="#" class="btn btn-outline-success">
                <i class="fa fa-bar-chart {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('instructor.performance_reports') }}
            </a>
            <a href="#" class="btn btn-outline-info">
                <i class="fa fa-chat-dots {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('instructor.student_messages') }}
            </a>
            <a href="#" class="btn btn-outline-secondary">
                <i class="fa fa-gear {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('app.profile_settings') }}
            </a>
        </div>
    </div>
</div>
@endsection

@if(session('locale', 'ar') === 'ar')
<style>
    .breadcrumb-item+.breadcrumb-item::before {
        float: left;
        padding-right: 0.5rem;
        padding-left: 0;
        content: "←";
    }

    .border-end {
        border-left: 1px solid #dee2e6 !important;
        border-right: none !important;
    }

    .border-start {
        border-right: 1px solid #dee2e6 !important;
        border-left: none !important;
    }
</style>
@else
<style>
    .border-end {
        border-right: 1px solid #dee2e6 !important;
        border-left: none !important;
    }

    .border-start {
        border-left: 1px solid #dee2e6 !important;
        border-right: none !important;
    }
</style>
@endif

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .card-header {
        border-bottom: 1px solid #dee2e6;
    }

    .nav-pills .nav-link.active {
        background-color: #0d6efd;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('performanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @if(session('locale', 'ar') === 'ar')['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو']
                @else['January', 'February', 'March', 'April', 'May', 'June']
                @endif,
                datasets: [{
                    label: '{{ __("app.new_enrollments") }}',
                    data: [12, 19, 3, 5, 2, 3],
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    tension: 0.4
                }, {
                    label: '{{ __("app.revenue") }} ($)',
                    data: [300, 450, 200, 400, 150, 300],
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        rtl: {
                            {
                                session('locale', 'ar') === 'ar' ? 'true' : 'false'
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>

@include('components.welcome-message')