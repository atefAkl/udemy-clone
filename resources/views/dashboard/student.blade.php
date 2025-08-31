@extends('layouts.dashboard')

@section('title', __('app.student_dashboard'))

@section('page-title', __('app.student_dashboard'))

@section('breadcrumb')
<li class="breadcrumb-item active">{{ __('app.student_dashboard') }}</li>
@endsection

@section('sidebar-nav')

<li class="nav-item">
    <a class="nav-link" href="{{ route('dashboard') }}">
        <i class="fas fa-tachometer-alt" style="margin-inline-end: 10px;"></i>
        {{ __('app.my_dashboard') }}
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('courses.index') }}">
        <i class="fas fa-book-open" style="margin-inline-end: 10px;"></i>
        {{ __('app.my_courses') }}
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="#">
        <i class="fas fa-chart-line" style="margin-inline-end: 10px;"></i>
        {{ __('app.my_progress') }}
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="#">
        <i class="fas fa-certificate" style="margin-inline-end: 10px;"></i>
        {{ __('app.my_certificates') }}
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="#">
        <i class="fas fa-cog" style="margin-inline-end: 10px;"></i>
        {{ __('app.account_settings') }}
    </a>
</li>
@endsection

@section('content')
<!-- Temporary Welcome Message - Show only once per session -->
@if(!session('welcome_shown'))
<div class="alert alert-primary alert-dismissible fade show position-relative mb-4" id="welcomeMessage" role="alert">
    <div class="d-flex align-items-center">
        <div class="me-3">
            <i class="fas fa-graduation-cap fa-2x"></i>
        </div>
        <div class="flex-grow-1">
            <h5 class="alert-heading mb-1">{{ __('app.welcome_student', ['name' => Auth::user()->name]) }}</h5>
            <p class="mb-0">{{ __('app.continue_learning_journey') }}</p>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <!-- Progress bar -->
    <div class="progress mt-3" style="height: 4px;">
        <div class="progress-bar" id="welcomeProgress" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
</div>
@endif

<!-- Current Courses -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 pb-0">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-play-circle text-primary" style="margin-inline-end: 10px;"></i>
                {{ __('app.current_courses') }}
            </h5>
            <a href="{{ route('courses.index') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-plus" style="margin-inline-end: 10px;"></i>
                {{ __('app.browse_more') }}
            </a>
        </div>
    </div>
    <div class="card-body">
        @php
        $currentCourses = [
        [
        'title' => __('app.locale') === 'ar' ? 'تطوير تطبيقات الويب بـ Laravel' : 'Laravel Web Development',
        'instructor' => __('app.locale') === 'ar' ? 'أحمد محمد' : 'Ahmed Mohammed',
        'progress' => 75,
        'image' => 'https://via.placeholder.com/300x200/007bff/ffffff?text=Laravel',
        'next_lesson' => __('app.locale') === 'ar' ? 'إنشاء نظام المصادقة' : 'Creating Authentication System',
        'total_lessons' => 45,
        'completed_lessons' => 34
        ],
        [
        'title' => __('app.locale') === 'ar' ? 'تصميم واجهات المستخدم بـ Bootstrap' : 'Bootstrap UI Design',
        'instructor' => __('app.locale') === 'ar' ? 'فاطمة أحمد' : 'Fatima Ahmed',
        'progress' => 40,
        'image' => 'https://via.placeholder.com/300x200/6f42c1/ffffff?text=Bootstrap',
        'next_lesson' => __('app.locale') === 'ar' ? 'النظام الشبكي المتقدم' : 'Advanced Grid System',
        'total_lessons' => 30,
        'completed_lessons' => 12
        ],
        [
        'title' => __('app.locale') === 'ar' ? 'أساسيات قواعد البيانات MySQL' : 'MySQL Database Fundamentals',
        'instructor' => __('app.locale') === 'ar' ? 'محمد علي' : 'Mohammed Ali',
        'progress' => 90,
        'image' => 'https://via.placeholder.com/300x200/fd7e14/ffffff?text=MySQL',
        'next_lesson' => __('app.locale') === 'ar' ? 'تحسين الاستعلامات' : 'Query Optimization',
        'total_lessons' => 25,
        'completed_lessons' => 23
        ]
        ];
        @endphp

        @foreach($currentCourses as $course)
        <div class="row align-items-center mb-4 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
            <div class="col-md-3">
                <img src="{{ $course['image'] }}" alt="{{ $course['title'] }}" class="img-fluid rounded">
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold mb-2">{{ $course['title'] }}</h6>
                <p class="text-muted small mb-2">
                    <i class="fas fa-user" style="margin-inline-end: 10px;"></i>{{ $course['instructor'] }}
                </p>
                <div class="progress mb-2" style="height: 8px;">
                    <div class="progress-bar" role="progressbar" style="width: {{ $course['progress'] }}%"></div>
                </div>
                <small class="text-muted">
                    {{ $course['completed_lessons'] }}/{{ $course['total_lessons'] }} {{ __('app.lessons_completed') }} ({{ $course['progress'] }}%)
                </small>
                <p class="small text-primary mb-0 mt-1">
                    <i class="fas fa-play" style="margin-inline-end: 10px;"></i>{{ __('app.next_lesson') }}: {{ $course['next_lesson'] }}
                </p>
            </div>
            <div class="col-md-3 text-end">
                <a href="#" class="btn btn-primary btn-sm mb-2 w-100">
                    <i class="fas fa-play" style="margin-inline-end: 10px;"></i>{{ __('app.continue_learning_btn') }}
                </a>
                <a href="#" class="btn btn-outline-secondary btn-sm w-100">
                    <i class="fas fa-info-circle" style="margin-inline-end: 10px;"></i>{{ __('app.course_details') }}
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Completed Courses Section -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0">
        <h5 class="mb-0">
            <i class="fas fa-trophy text-success" style="margin-inline-end: 10px;"></i>
            {{ __('app.completed_courses') }}
        </h5>
    </div>
    <div class="card-body">
        @php
        $completedCourses = [
        [
        'title' => __('app.locale') === 'ar' ? 'أساسيات JavaScript' : 'JavaScript Fundamentals',
        'instructor' => __('app.locale') === 'ar' ? 'سارة محمد' : 'Sarah Mohammed',
        'completed_date' => '2024-01-15',
        'rating' => 5,
        'certificate' => true,
        'image' => 'https://via.placeholder.com/200x120/f1c40f/ffffff?text=JS'
        ],
        [
        'title' => __('app.locale') === 'ar' ? 'تصميم المواقع بـ HTML & CSS' : 'HTML & CSS Web Design',
        'instructor' => __('app.locale') === 'ar' ? 'عمر أحمد' : 'Omar Ahmed',
        'completed_date' => '2024-01-10',
        'rating' => 4,
        'certificate' => true,
        'image' => 'https://via.placeholder.com/200x120/e74c3c/ffffff?text=HTML'
        ]
        ];
        @endphp

        <div class="row">
            @foreach($completedCourses as $course)
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <img src="{{ $course['image'] }}" class="card-img-top" alt="{{ $course['title'] }}">
                    <div class="card-body">
                        <h6 class="card-title">{{ $course['title'] }}</h6>
                        <p class="card-text small text-muted">
                            <i class="fas fa-user" style="margin-inline-end: 10px;"></i>{{ $course['instructor'] }}
                        </p>
                        <div class="mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $course['rating'] ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                        </div>
                        <p class="small text-muted mb-2">
                            <i class="fas fa-calendar" style="margin-inline-end: 10px;"></i>{{ __('app.completed_on') }} {{ $course['completed_date'] }}
                        </p>
                        @if($course['certificate'])
                        <div class="d-grid">
                            <a href="#" class="btn btn-success btn-sm">
                                <i class="fas fa-download" style="margin-inline-end: 10px;"></i>{{ __('app.download_certificate') }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@if(!session('welcome_shown'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const welcomeMessage = document.getElementById('welcomeMessage');
        const progressBar = document.getElementById('welcomeProgress');

        if (welcomeMessage && progressBar) {
            let timeLeft = 30; // 30 seconds
            const totalTime = 30;

            const timer = setInterval(function() {
                timeLeft--;
                const percentage = (timeLeft / totalTime) * 100;
                progressBar.style.width = percentage + '%';
                progressBar.setAttribute('aria-valuenow', percentage);

                if (timeLeft <= 0) {
                    clearInterval(timer);
                    welcomeMessage.style.transition = 'opacity 0.5s ease-out';
                    welcomeMessage.style.opacity = '0';
                    setTimeout(() => {
                        if (welcomeMessage.parentNode) {
                            welcomeMessage.parentNode.removeChild(welcomeMessage);
                        }
                    }, 500);
                }
            }, 1000);

            // Allow manual close
            const closeButton = welcomeMessage.querySelector('.btn-close');
            if (closeButton) {
                closeButton.addEventListener('click', function() {
                    clearInterval(timer);
                });
            }

            // Mark welcome as shown in session
            fetch('{{ route("dashboard.welcome.shown") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            });
        }
    });
</script>
@endif
@endsection

@section('widgets')
<!-- Statistics Cards -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0">
        <h6 class="mb-0">
            <i class="fas fa-chart-bar text-primary" style="margin-inline-end: 10px;"></i>
            {{ __('app.statistics') }}
        </h6>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-12">
                <div class="d-flex align-items-center p-3 bg-primary bg-opacity-10 rounded" style="flex-direction: rtl;">
                    <div style="margin-inline-end: 15px;">
                        <i class="fas fa-book-open text-primary fa-2x"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold text-primary mb-0">{{ $enrolledCourses ?? 5 }}</h4>
                        <small class="text-muted">{{ __('app.enrolled_courses') }}</small>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex align-items-center p-3 bg-success bg-opacity-10 rounded" style="flex-direction: rtl;">
                    <div style="margin-inline-end: 15px;">
                        <i class="fas fa-trophy text-success fa-2x"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold text-success mb-0">{{ count($completedCourses) ?? 2 }}</h4>
                        <small class="text-muted">{{ __('app.completed_courses') }}</small>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex align-items-center p-3 bg-warning bg-opacity-10 rounded" style="flex-direction: rtl;">
                    <div style="margin-inline-end: 15px;">
                        <i class="fas fa-clock text-warning fa-2x"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold text-warning mb-0">{{ $totalHours ?? 45 }}</h4>
                        <small class="text-muted">{{ __('app.learning_hours') }}</small>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex align-items-center p-3 bg-info bg-opacity-10 rounded" style="flex-direction: rtl;">
                    <div style="margin-inline-end: 15px;">
                        <i class="fas fa-certificate text-info fa-2x"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold text-info mb-0">{{ $certificates ?? 2 }}</h4>
                        <small class="text-muted">{{ __('app.certificates_earned') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0">
        <h6 class="mb-0">
            <i class="fas fa-history text-success" style="margin-inline-end: 10px;"></i>
            {{ __('app.recent_activity') }}
        </h6>
    </div>
    <div class="card-body">
        @php
        $activities = [
        [
        'type' => 'lesson_completed',
        'title' => __('app.locale') === 'ar' ? 'أكملت درس "إنشاء قاعدة البيانات"' : 'Completed lesson "Database Creation"',
        'course' => __('app.locale') === 'ar' ? 'أساسيات MySQL' : 'MySQL Fundamentals',
        'time' => __('app.locale') === 'ar' ? 'منذ ساعتين' : '2 hours ago',
        'icon' => 'fas fa-check-circle text-success'
        ],
        [
        'type' => 'course_enrolled',
        'title' => __('app.locale') === 'ar' ? 'تسجلت في دورة جديدة' : 'Enrolled in new course',
        'course' => __('app.locale') === 'ar' ? 'تطوير APIs بـ Laravel' : 'Laravel API Development',
        'time' => __('app.locale') === 'ar' ? 'أمس' : 'Yesterday',
        'icon' => 'fas fa-plus-circle text-primary'
        ],
        [
        'type' => 'certificate_earned',
        'title' => __('app.locale') === 'ar' ? 'حصلت على شهادة إتمام' : 'Earned completion certificate',
        'course' => __('app.locale') === 'ar' ? 'أساسيات JavaScript' : 'JavaScript Fundamentals',
        'time' => __('app.locale') === 'ar' ? 'منذ 3 أيام' : '3 days ago',
        'icon' => 'fas fa-award text-warning'
        ]
        ];
        @endphp

        @foreach($activities as $activity)
        <div class="d-flex align-items-start mb-3 {{ !$loop->last ? 'pb-3 border-bottom' : '' }}" style="flex-direction: rtl;">
            <div style="margin-inline-end: 12px;">
                <i class="{{ $activity['icon'] }}"></i>
            </div>
            <div class="flex-grow-1">
                <p class="mb-1 small">{{ $activity['title'] }}</p>
                <p class="text-primary small mb-1">{{ $activity['course'] }}</p>
                <small class="text-muted">{{ $activity['time'] }}</small>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Notifications -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0">
        <h6 class="mb-0">
            <i class="fas fa-bell text-info" style="margin-inline-end: 10px;"></i>
            {{ __('app.notifications') }}
        </h6>
    </div>
    <div class="card-body">
        @php
        $notifications = [
        [
        'title' => __('app.locale') === 'ar' ? 'درس جديد متاح' : 'New lesson available',
        'message' => __('app.locale') === 'ar' ? 'تم إضافة درس جديد في دورة Laravel' : 'New lesson added to Laravel course',
        'time' => __('app.locale') === 'ar' ? 'منذ ساعة' : '1 hour ago',
        'type' => 'new_lesson',
        'unread' => true
        ],
        [
        'title' => __('app.locale') === 'ar' ? 'تذكير بالمواعيد' : 'Schedule reminder',
        'message' => __('app.locale') === 'ar' ? 'لديك جلسة مباشرة غداً الساعة 8 مساءً' : 'Live session tomorrow at 8 PM',
        'time' => __('app.locale') === 'ar' ? 'منذ 3 ساعات' : '3 hours ago',
        'type' => 'reminder',
        'unread' => true
        ],
        [
        'title' => __('app.locale') === 'ar' ? 'تحديث الدورة' : 'Course update',
        'message' => __('app.locale') === 'ar' ? 'تم تحديث محتوى دورة Bootstrap' : 'Bootstrap course content updated',
        'time' => __('app.locale') === 'ar' ? 'أمس' : 'Yesterday',
        'type' => 'update',
        'unread' => false
        ]
        ];
        @endphp

        @foreach($notifications as $notification)
        <div class="d-flex align-items-start mb-3 {{ !$loop->last ? 'pb-3 border-bottom' : '' }} {{ $notification['unread'] ? 'bg-light rounded p-2' : '' }}" style="flex-direction: rtl;">
            <div style="margin-inline-end: 12px;">
                @if($notification['type'] == 'new_lesson')
                <i class="fas fa-play-circle text-primary"></i>
                @elseif($notification['type'] == 'reminder')
                <i class="fas fa-clock text-warning"></i>
                @else
                <i class="fas fa-sync text-info"></i>
                @endif
            </div>
            <div class="flex-grow-1">
                <p class="mb-1 small fw-bold">{{ $notification['title'] }}</p>
                <p class="mb-1 small text-muted">{{ $notification['message'] }}</p>
                <small class="text-muted">{{ $notification['time'] }}</small>
            </div>
            @if($notification['unread'])
            <div style="margin-inline-start: 10px;">
                <span class="badge bg-primary rounded-pill" style="width: 8px; height: 8px;"></span>
            </div>
            @endif
        </div>
        @endforeach

        <div class="text-center mt-3">
            <a href="#" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-eye" style="margin-inline-end: 10px;"></i>{{ __('app.view_all_notifications') }}
            </a>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0">
        <h6 class="mb-0">
            <i class="fas fa-bolt text-warning" style="margin-inline-end: 10px;"></i>
            {{ __('app.quick_actions') }}
        </h6>
    </div>
    <div class="card-body">
        <div class="d-grid gap-2">
            <a href="{{ route('courses.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-search" style="margin-inline-end: 10px;"></i>{{ __('app.browse_courses') }}
            </a>
            <a href="#" class="btn btn-outline-success">
                <i class="fas fa-download" style="margin-inline-end: 10px;"></i>{{ __('app.download_certificates') }}
            </a>
            <a href="#" class="btn btn-outline-info">
                <i class="fas fa-user-edit" style="margin-inline-end: 10px;"></i>{{ __('app.update_profile') }}
            </a>
            <a href="#" class="btn btn-outline-secondary">
                <i class="fas fa-cog" style="margin-inline-end: 10px;"></i>{{ __('app.account_settings') }}
            </a>
        </div>
    </div>
</div>
@endsection