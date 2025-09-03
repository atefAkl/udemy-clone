@extends('layouts.student')

@section('title', __('student.student_dashboard'))
@section ('breadcrumb')
<li class="breadcrumb-item text-light active" aria-current="page">{{ __('student.student_dashboard') }}</li>
@endsection
@section('content')



<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-gradient-primary text-white border-0 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="mb-2">{{ __('student.welcome_student') }}, {{ Auth::user()->name }}!</h2>
                        <p class="mb-0 opacity-75">{{ __('student.student_welcome_message') }}</p>
                    </div>
                    <div class="col-md-4 text-{{ session('locale', 'ar') === 'ar' ? 'start' : 'end' }}">
                        <i class="bi bi-mortarboard" style="font-size: 4rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="d-flex align-items-center justify-content-center mb-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-journal-bookmark text-primary fs-2"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-primary mb-1">{{ count($enrolledCourses) }}</h3>
                <p class="text-muted mb-0">{{ __('student.enrolled_courses') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="d-flex align-items-center justify-content-center mb-3">
                    <div class="bg-success bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-check-circle text-success fs-2"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-success mb-1">{{ count($completedCourses) }}</h3>
                <p class="text-muted mb-0">{{ __('student.completed_courses') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="d-flex align-items-center justify-content-center mb-3">
                    <div class="bg-info bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-hourglass-split text-info fs-2"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-info mb-1">{{ count($inProgressCourses) }}</h3>
                <p class="text-muted mb-0">{{ __('student.in_progress') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
                <div class="d-flex align-items-center justify-content-center mb-3">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-award text-warning fs-2"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-warning mb-1">{{ $certificatesCount }}</h3>
                <p class="text-muted mb-0">{{ __('student.certificates') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- My Courses -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-book-half text-primary {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('student.my_courses') }}
            </h5>
            <div>
                <a href="{{ route('student.courses') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-list {{ session('locale', 'ar') === 'ar' ? 'ms-1' : 'me-1' }}"></i>
                    {{ __('student.view_all_courses') }}
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        @php
        $recentCourses = $recentCourses ?? [
        [
        'id' => 1,
        'title' => session('locale', 'ar') === 'ar' ? 'تطوير تطبيقات الويب بـ Laravel' : 'Web Development with Laravel',
        'status' => 'published',
        'students_count' => 45,
        'lessons_count' => 32,
        'rating' => 4.8,
        'created_at' => '2024-01-15',
        'thumbnail' => 'https://via.placeholder.com/300x200/007bff/ffffff?text=Laravel'
        ],
        [
        'id' => 2,
        'title' => session('locale', 'ar') === 'ar' ? 'أساسيات قواعد البيانات MySQL' : 'MySQL Database Fundamentals',
        'status' => 'draft',
        'students_count' => 0,
        'lessons_count' => 18,
        'rating' => 0,
        'created_at' => '2024-01-10',
        'thumbnail' => 'https://via.placeholder.com/300x200/fd7e14/ffffff?text=MySQL'
        ],
        [
        'id' => 3,
        'title' => session('locale', 'ar') === 'ar' ? 'تصميم واجهات المستخدم بـ Bootstrap' : 'UI Design with Bootstrap',
        'status' => 'published',
        'students_count' => 67,
        'lessons_count' => 25,
        'rating' => 4.6,
        'created_at' => '2024-01-05',
        'thumbnail' => 'https://via.placeholder.com/300x200/6f42c1/ffffff?text=Bootstrap'
        ]
        ];
        @endphp

        @forelse($recentCourses as $course)
        <div class="row align-items-center mb-4 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
            <div class="col-md-3">
                <img src="{{ $course['thumbnail'] }}" alt="{{ $course['title'] }}" class="img-fluid rounded">
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold mb-2">{{ $course['title'] }}</h6>
                <div class="mb-2">
                    @if($course['status'] === 'published')
                    <span class="badge bg-success">{{ __('app.published') }}</span>
                    @else
                    <span class="badge bg-warning">{{ __('app.draft') }}</span>
                    @endif
                </div>
                <div class="row text-center">
                    <div class="col-4">
                        <small class="text-muted d-block">{{ __('app.students') }}</small>
                        <strong>{{ $course['students_count'] }}</strong>
                    </div>
                    <div class="col-4">
                        <small class="text-muted d-block">{{ __('app.lessons') }}</small>
                        <strong>{{ $course['lessons_count'] }}</strong>
                    </div>
                    <div class="col-4">
                        <small class="text-muted d-block">{{ __('app.rating') }}</small>
                        <strong>
                            @if($course['rating'] > 0)
                            <i class="bi bi-star-fill text-warning"></i> {{ $course['rating'] }}
                            @else
                            <span class="text-muted">{{ __('app.no_rating') }}</span>
                            @endif
                        </strong>
                    </div>
                </div>
            </div>
            <div class="col-md-3 text-{{ session('locale', 'ar') === 'ar' ? 'start' : 'end' }}">
                <div class="btn-group-vertical w-100" role="group">
                    <a href="{{ route('instructor.courses.show', $course['id']) }}" class="btn btn-outline-primary btn-sm mb-1">
                        <i class="bi bi-eye {{ session('locale', 'ar') === 'ar' ? 'ms-1' : 'me-1' }}"></i>
                        {{ __('app.view') }}
                    </a>
                    <a href="{{ route('instructor.courses.edit', $course['id']) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-pencil {{ session('locale', 'ar') === 'ar' ? 'ms-1' : 'me-1' }}"></i>
                        {{ __('app.edit') }}
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-4">
            <i class="bi bi-book text-muted" style="font-size: 4rem;"></i>
            <h5 class="text-muted mt-3">{{ __('instructor.no_courses_yet') }}</h5>
            <p class="text-muted">{{ __('instructor.create_first_course_message') }}</p>
            <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary">
                <i class="bi bi-plus {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('instructor.create_first_course') }}
            </a>
        </div>
        @endforelse
    </div>
</div>

<!-- Performance Chart -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0">
        <h5 class="mb-0">
            <i class="bi bi-graph-up text-primary {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
            {{ __('instructor.course_performance') }}
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <canvas id="performanceChart" height="100"></canvas>
            </div>
            <div class="col-md-4">
                <h6 class="mb-3">{{ __('instructor.top_performing_courses') }}</h6>
                @php
                $topCourses = [
                ['name' => session('locale', 'ar') === 'ar' ? 'تطوير Laravel' : 'Laravel Development', 'students' => 45, 'rating' => 4.8],
                ['name' => session('locale', 'ar') === 'ar' ? 'Bootstrap UI' : 'Bootstrap UI', 'students' => 67, 'rating' => 4.6],
                ['name' => session('locale', 'ar') === 'ar' ? 'قاعدة بيانات MySQL' : 'MySQL Database', 'students' => 23, 'rating' => 4.9],
                ];
                @endphp
                @foreach($topCourses as $index => $course)
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center {{ session('locale', 'ar') === 'ar' ? 'ms-3' : 'me-3' }}" style="width: 30px; height: 30px;">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">{{ $course['name'] }}</h6>
                        <small class="text-muted">
                            {{ $course['students'] }} {{ __('app.student') }} •
                            {{ $course['rating'] }} <i class="bi bi-star-fill text-warning"></i>
                        </small>
                    </div>
                </div>
                @endforeach
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
            <i class="bi bi-speedometer text-success {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
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
            <i class="bi bi-activity text-info {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
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
        'icon' => 'bi bi-person-plus text-success'
        ],
        [
        'type' => 'new_review',
        'message' => __('app.new_five_star_review'),
        'time' => __('app.three_hours_ago'),
        'icon' => 'bi bi-star text-warning'
        ],
        [
        'type' => 'course_completed',
        'message' => __('app.student_completed_course'),
        'time' => __('app.yesterday'),
        'icon' => 'bi bi-trophy text-primary'
        ],
        [
        'type' => 'question',
        'message' => __('app.new_question_posted'),
        'time' => __('app.two_days_ago'),
        'icon' => 'bi bi-question-circle text-info'
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
            <i class="bi bi-lightning text-warning {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
            {{ __('app.quick_actions') }}
        </h6>
    </div>
    <div class="card-body">
        <div class="d-grid gap-2">
            <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary">
                <i class="bi bi-plus {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('instructor.create_new_course') }}
            </a>
            <a href="{{ route('instructor.courses.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-list {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('instructor.manage_courses_btn') }}
            </a>
            <a href="#" class="btn btn-outline-success">
                <i class="bi bi-bar-chart {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('instructor.performance_reports') }}
            </a>
            <a href="#" class="btn btn-outline-info">
                <i class="bi bi-chat-dots {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('instructor.student_messages') }}
            </a>
            <a href="#" class="btn btn-outline-secondary">
                <i class="bi bi-gear {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
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