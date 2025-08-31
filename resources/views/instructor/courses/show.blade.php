@extends('layouts.dashboard')

@section('title', __('app.view_course'))

@section('sidebar-nav')
<div class="nav-header">
    <h6 class="text-muted mb-3">{{ __('app.instructor') }}</h6>
</div>
<ul class="nav nav-pills flex-column">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('instructor.dashboard') }}">
            <i class="bi bi-speedometer2 {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
            {{ __('app.dashboard') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('instructor.courses.index') }}">
            <i class="bi bi-book {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
            {{ __('app.my_courses') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="bi bi-people {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
            {{ __('app.students') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="bi bi-bar-chart {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
            {{ __('app.analytics') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="bi bi-cash-coin {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
            {{ __('app.earnings') }}
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#">
            <i class="bi bi-gear {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
            {{ __('app.settings') }}
        </a>
    </li>
</ul>
@endsection

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('instructor.dashboard') }}">{{ __('app.dashboard') }}</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('instructor.courses.index') }}">{{ __('app.my_courses') }}</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">{{ $course->title }}</li>
    </ol>
</nav>

<!-- Course Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <img src="{{ $course->thumbnail ?? 'https://via.placeholder.com/300x200?text=Course+Image' }}"
                            alt="{{ $course->title }}" class="img-fluid rounded">
                    </div>
                    <div class="col-md-6">
                        <h2 class="mb-2">{{ $course->title }}</h2>
                        <p class="text-muted mb-3">{{ $course->description }}</p>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge bg-primary">{{ $course->category->name ?? __('app.category') }}</span>
                            <span class="badge bg-secondary">{{ ucfirst($course->level) }}</span>
                            <span class="badge bg-info">{{ $course->duration }} {{ __('app.hours') }}</span>
                            @if($course->status === 'published')
                            <span class="badge bg-success">{{ __('app.published') }}</span>
                            @elseif($course->status === 'draft')
                            <span class="badge bg-warning">{{ __('app.draft') }}</span>
                            @else
                            <span class="badge bg-secondary">{{ __('app.pending_review') }}</span>
                            @endif
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-star-fill text-warning {{ session('locale', 'ar') === 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                <span class="fw-bold">4.5</span>
                                <span class="text-muted">({{ $course->enrollments_count }} {{ __('app.students') }})</span>
                            </div>
                            <div class="text-muted">
                                <i class="bi bi-play-circle {{ session('locale', 'ar') === 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                {{ $course->lessons_count }} {{ __('app.lessons') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 text-{{ session('locale', 'ar') === 'ar' ? 'start' : 'end' }}">
                        <div class="mb-3">
                            <h3 class="text-primary mb-0">
                                @if($course->price > 0)
                                ${{ number_format($course->price, 2) }}
                                @else
                                {{ __('app.free') }}
                                @endif
                            </h3>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="{{ route('instructor.courses.edit', $course) }}" class="btn btn-primary">
                                <i class="bi bi-pencil {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('app.edit_course') }}
                            </a>
                            @if($course->status === 'draft')
                            <button class="btn btn-success" onclick="publishCourse({{ $course->id }})">
                                <i class="bi bi-upload {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('app.publish_course') }}
                            </button>
                            @endif
                            <button class="btn btn-outline-danger" onclick="deleteCourse({{ $course->id }})">
                                <i class="bi bi-trash {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('app.delete_course') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Course Statistics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <div class="text-primary mb-2">
                    <i class="bi bi-people fs-1"></i>
                </div>
                <h4 class="mb-1">{{ $course->enrollments_count }}</h4>
                <p class="text-muted mb-0">{{ __('app.total_students') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <div class="text-success mb-2">
                    <i class="bi bi-cash-coin fs-1"></i>
                </div>
                <h4 class="mb-1">${{ number_format($course->enrollments_count * $course->price, 2) }}</h4>
                <p class="text-muted mb-0">{{ __('app.total_earnings') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <div class="text-warning mb-2">
                    <i class="bi bi-star-fill fs-1"></i>
                </div>
                <h4 class="mb-1">4.5</h4>
                <p class="text-muted mb-0">{{ __('app.average_rating') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <div class="text-info mb-2">
                    <i class="bi bi-play-circle fs-1"></i>
                </div>
                <h4 class="mb-1">{{ $course->lessons_count }}</h4>
                <p class="text-muted mb-0">{{ __('app.lessons') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Course Content Tabs -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <ul class="nav nav-tabs card-header-tabs" id="courseTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="lessons-tab" data-bs-toggle="tab" data-bs-target="#lessons" type="button" role="tab">
                    <i class="bi bi-play-circle {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('app.lessons') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="students-tab" data-bs-toggle="tab" data-bs-target="#students" type="button" role="tab">
                    <i class="bi bi-people {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('app.students') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
                    <i class="bi bi-star {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('app.reviews') }}
                </button>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="courseTabContent">
            <!-- Lessons Tab -->
            <div class="tab-pane fade show active" id="lessons" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">{{ __('app.course_lessons') }}</h5>
                    <button class="btn btn-primary">
                        <i class="bi bi-plus {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('app.add_lesson') }}
                    </button>
                </div>

                @forelse($course->lessons ?? [] as $index => $lesson)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-1">
                                <div class="text-center">
                                    <span class="badge bg-primary rounded-pill">{{ $index + 1 }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-1">{{ $lesson->title }}</h6>
                                <p class="text-muted mb-0">{{ Str::limit($lesson->description, 100) }}</p>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted">{{ $lesson->duration }} {{ __('app.minutes') }}</small>
                            </div>
                            <div class="col-md-2">
                                <span class="badge bg-{{ $lesson->is_free ? 'success' : 'secondary' }}">
                                    {{ $lesson->is_free ? __('app.free') : __('app.premium') }}
                                </span>
                            </div>
                            <div class="col-md-1">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#"><i class="bi bi-pencil {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('app.edit') }}</a></li>
                                        <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('app.delete') }}</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="bi bi-play-circle text-muted" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mt-3">{{ __('app.no_lessons_yet') }}</h5>
                    <p class="text-muted">{{ __('app.start_adding_lessons') }}</p>
                    <button class="btn btn-primary">
                        <i class="bi bi-plus {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('app.add_first_lesson') }}
                    </button>
                </div>
                @endforelse
            </div>

            <!-- Students Tab -->
            <div class="tab-pane fade" id="students" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">{{ __('app.enrolled_students') }}</h5>
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control" placeholder="{{ __('app.search_students') }}">
                        <button class="btn btn-outline-secondary">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>

                @forelse($course->enrollments ?? [] as $enrollment)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <img src="https://via.placeholder.com/50x50?text=User" alt="Student" class="rounded-circle" width="50" height="50">
                            </div>
                            <div class="col-md-4">
                                <h6 class="mb-1">{{ $enrollment->user->name }}</h6>
                                <small class="text-muted">{{ $enrollment->user->email }}</small>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted">{{ __('app.enrolled') }}: {{ $enrollment->created_at->format('M d, Y') }}</small>
                            </div>
                            <div class="col-md-2">
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar" style="width: {{ $enrollment->progress }}%"></div>
                                </div>
                                <small class="text-muted">{{ $enrollment->progress }}% {{ __('app.complete') }}</small>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-envelope {{ session('locale', 'ar') === 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                    {{ __('app.message') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="bi bi-people text-muted" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mt-3">{{ __('app.no_students_enrolled') }}</h5>
                    <p class="text-muted">{{ __('app.promote_course_to_get_students') }}</p>
                </div>
                @endforelse
            </div>

            <!-- Reviews Tab -->
            <div class="tab-pane fade" id="reviews" role="tabpanel">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="text-center">
                            <h2 class="text-warning mb-2">4.5</h2>
                            <div class="mb-2">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-half text-warning"></i>
                            </div>
                            <p class="text-muted">{{ __('app.based_on') }} 25 {{ __('app.reviews') }}</p>
                        </div>
                    </div>
                    <div class="col-md-8">
                        @for($i = 5; $i >= 1; $i--)
                        <div class="mb-2">
                            <div class="d-flex align-items-center">
                                <span class="me-2">{{ $i }}</span>
                                <i class="bi bi-star-fill text-warning me-2"></i>
                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                    <div class="progress-bar bg-warning" style="width: {{ rand(10, 80) }}%"></div>
                                </div>
                                <span class="text-muted">{{ rand(10, 80) }}%</span>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>

                <!-- Individual Reviews -->
                <div class="border-top pt-4">
                    @for($i = 1; $i <= 3; $i++)
                        <div class="mb-4">
                        <div class="d-flex align-items-start">
                            <img src="https://via.placeholder.com/50x50?text=User" alt="Student" class="rounded-circle me-3" width="50" height="50">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1">Student Name {{ $i }}</h6>
                                        <div class="mb-1">
                                            @for($j = 1; $j <= 5; $j++)
                                                <i class="bi bi-star-fill text-warning small"></i>
                                                @endfor
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $i }} days ago</small>
                                </div>
                                <p class="mb-0">This is an excellent course! The instructor explains everything clearly and the content is very well structured.</p>
                            </div>
                        </div>
                </div>
                @endfor
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('widgets')
<!-- Course Actions -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white">
        <h6 class="mb-0">{{ __('app.quick_actions') }}</h6>
    </div>
    <div class="card-body">
        <div class="d-grid gap-2">
            <a href="{{ route('instructor.courses.edit', $course) }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-pencil {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('app.edit_course') }}
            </a>
            <button class="btn btn-outline-success btn-sm">
                <i class="bi bi-plus {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('app.add_lesson') }}
            </button>
            <button class="btn btn-outline-info btn-sm">
                <i class="bi bi-share {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('app.share_course') }}
            </button>
        </div>
    </div>
</div>

<!-- Course Performance -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white">
        <h6 class="mb-0">{{ __('app.performance') }}</h6>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <small class="text-muted">{{ __('app.completion_rate') }}</small>
                <small class="fw-bold">78%</small>
            </div>
            <div class="progress" style="height: 6px;">
                <div class="progress-bar bg-success" style="width: 78%"></div>
            </div>
        </div>
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <small class="text-muted">{{ __('app.engagement') }}</small>
                <small class="fw-bold">85%</small>
            </div>
            <div class="progress" style="height: 6px;">
                <div class="progress-bar bg-primary" style="width: 85%"></div>
            </div>
        </div>
        <div class="mb-0">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <small class="text-muted">{{ __('app.satisfaction') }}</small>
                <small class="fw-bold">92%</small>
            </div>
            <div class="progress" style="height: 6px;">
                <div class="progress-bar bg-warning" style="width: 92%"></div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h6 class="mb-0">{{ __('app.recent_activity') }}</h6>
    </div>
    <div class="card-body">
        <div class="activity-item mb-3">
            <div class="d-flex align-items-start">
                <div class="activity-icon bg-success text-white rounded-circle {{ session('locale', 'ar') === 'ar' ? 'ms-3' : 'me-3' }}" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-person-plus small"></i>
                </div>
                <div class="flex-grow-1">
                    <p class="mb-1 small">New student enrolled</p>
                    <small class="text-muted">2 hours ago</small>
                </div>
            </div>
        </div>
        <div class="activity-item mb-3">
            <div class="d-flex align-items-start">
                <div class="activity-icon bg-warning text-white rounded-circle {{ session('locale', 'ar') === 'ar' ? 'ms-3' : 'me-3' }}" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-star small"></i>
                </div>
                <div class="flex-grow-1">
                    <p class="mb-1 small">New 5-star review</p>
                    <small class="text-muted">5 hours ago</small>
                </div>
            </div>
        </div>
        <div class="activity-item mb-0">
            <div class="d-flex align-items-start">
                <div class="activity-icon bg-info text-white rounded-circle {{ session('locale', 'ar') === 'ar' ? 'ms-3' : 'me-3' }}" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-check small"></i>
                </div>
                <div class="flex-grow-1">
                    <p class="mb-1 small">Lesson completed</p>
                    <small class="text-muted">1 day ago</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@if(session('locale', 'ar') === 'ar')
<style>
    .activity-icon {
        margin-inline-start: 12px;
        margin-inline-end: 0;
    }

    .nav-tabs .nav-link {
        text-align: right;
    }

    .breadcrumb-item+.breadcrumb-item::before {
        float: left;
        padding-right: 0.5rem;
        padding-left: 0;
        content: "←";
    }
</style>
@else
<style>
    .activity-icon {
        margin-inline-end: 12px;
        margin-inline-start: 0;
    }

    .nav-tabs .nav-link {
        text-align: left;
    }
</style>
@endif

<style>
    .activity-item:not(:last-child) {
        border-bottom: 1px solid #f8f9fa;
        padding-bottom: 1rem;
    }

    .nav-tabs .nav-link.active {
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
    }

    .progress {
        background-color: #f8f9fa;
    }
</style>

<script>
    function publishCourse(courseId) {
        if (confirm('{{ __("app.confirm_publish_course") }}')) {
            fetch(`/instructor/courses/${courseId}/publish`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            }).then(response => {
                if (response.ok) {
                    location.reload();
                }
            });
        }
    }

    function deleteCourse(courseId) {
        if (confirm('{{ __("app.delete_course_warning") }}')) {
            fetch(`/instructor/courses/${courseId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            }).then(response => {
                if (response.ok) {
                    window.location.href = '{{ route("instructor.courses.index") }}';
                }
            });
        }
    }
</script>