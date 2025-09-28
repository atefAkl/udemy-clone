@extends('layouts.instructor-wide')

@section('title', __('instructor.view_course'))



@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('instructor.dashboard') }}">{{ __('instructor.dashboard') }}</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('instructor.courses.index') }}">{{ __('instructor.my_courses') }}</a>
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
                            <span class="badge bg-primary">{{ $course->category->name ?? __('instructor.category') }}</span>
                            <span class="badge bg-secondary">{{ ucfirst($course->level) }}</span>
                            <span class="badge bg-info">{{ $course->duration }} {{ __('instructor.hours') }}</span>
                            @if($course->status === 'published')
                            <span class="badge bg-success">{{ __('instructor.published') }}</span>
                            @elseif($course->status === 'draft')
                            <span class="badge bg-warning">{{ __('instructor.draft') }}</span>
                            @else
                            <span class="badge bg-secondary">{{ __('instructor.pending_review') }}</span>
                            @endif
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-star-fill text-warning {{ session('locale', 'ar') === 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                <span class="fw-bold">4.5</span>
                                <span class="text-muted">({{ $course->enrollments_count }} {{ __('instructor.students') }})</span>
                            </div>
                            <div class="text-muted">
                                <i class="fa fa-play-circle {{ session('locale', 'ar') === 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                {{ $course->lessons_count }} {{ __('instructor.lessons') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 text-{{ session('locale', 'ar') === 'ar' ? 'start' : 'end' }}">
                        <div class="mb-3">
                            <h3 class="text-primary mb-0">
                                @if($course->price > 0)
                                ${{ number_format($course->price, 2) }}
                                @else
                                {{ __('instructor.free') }}
                                @endif
                            </h3>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="{{ route('instructor.courses.edit', $course) }}" class="btn btn-primary">
                                <i class="fa fa-pencil {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('instructor.edit_course') }}
                            </a>
                            @if($course->status === 'draft')
                            <button class="btn btn-success" onclick="publishCourse({{ $course->id }})">
                                <i class="fa fa-upload {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('instructor.publish_course') }}
                            </button>
                            @endif
                            <button class="btn btn-outline-danger" onclick="deleteCourse({{ $course->id }})">
                                <i class="fa fa-trash {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                                {{ __('instructor.delete_course') }}
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
        <div class="card border-0 shadow-sm text-center" style="height: 10rem;">
            <div class="card-body">
                <div class="text-primary mb-2">
                    <i class="fa fa-users fs-1"></i>
                </div>
                <h4 class="mb-1 fw-bold">{{ $course->enrollments_count ?? 0 }}</h4>
                <p class="text-muted mb-0">{{ __('instructor.total_students') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center" style="height: 10rem;">
            <div class="card-body">
                <div class="text-success mb-2">
                    <i class="fa fa-dollar fs-1"></i>
                </div>
                <h4 class="mb-1 fw-bold">${{ number_format($course->enrollments_count ?? 0 * $course->price, 2) }}</h4>
                <p class="text-muted mb-0">{{ __('instructor.total_earnings') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center" style="height: 10rem;">
            <div class="card-body">
                <div class="text-warning mb-2">
                    <i class="fa fa-star fs-1"></i>
                </div>
                <h4 class="mb-1 fw-bold">4.5</h4>
                <p class="text-muted mb-0">{{ __('instructor.average_rating') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center" style="height: 10rem;">
            <div class="card-body">
                <div class="text-info mb-2">
                    <i class="fa fa-play-circle fs-1"></i>
                </div>
                <h4 class="mb-1 fw-bold">{{ $course->lessons_count }}</h4>
                <p class="text-muted mb-0">{{ __('instructor.lessons') }}</p>
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
                    <i class="fa fa-play-circle {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('instructor.lessons') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="students-tab" data-bs-toggle="tab" data-bs-target="#students" type="button" role="tab">
                    <i class="fa fa-people {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('instructor.students') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
                    <i class="fa fa-star {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                    {{ __('instructor.reviews') }}
                </button>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="courseTabContent">
            <!-- Lessons Tab -->
            <div class="tab-pane fade show active" id="lessons" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">{{ __('instructor.course_lessons') }}</h5>
                    <button class="btn btn-primary">
                        <i class="fa fa-plus {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('instructor.add_lesson') }}
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
                                <small class="text-muted">{{ $lesson->duration }} {{ __('instructor.minutes') }}</small>
                            </div>
                            <div class="col-md-2">
                                <span class="badge bg-{{ $lesson->is_free ? 'success' : 'secondary' }}">
                                    {{ $lesson->is_free ? __('instructor.free') : __('instructor.premium') }}
                                </span>
                            </div>
                            <div class="col-md-1">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                        <i class="fa fa-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#"><i class="fa fa-pencil {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('instructor.edit') }}</a></li>
                                        <li><a class="dropdown-item text-danger" href="#"><i class="fa fa-trash {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('instructor.delete') }}</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="fa fa-play-circle text-muted" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mt-3">{{ __('instructor.no_lessons_yet') }}</h5>
                    <p class="text-muted">{{ __('instructor.start_adding_lessons') }}</p>
                    <button class="btn btn-primary">
                        <i class="fa fa-plus {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                        {{ __('instructor.add_first_lesson') }}
                    </button>
                </div>
                @endforelse
            </div>

            <!-- Students Tab -->
            <div class="tab-pane fade" id="students" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">{{ __('instructor.enrolled_students') }}</h5>
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control" placeholder="{{ __('instructor.search_students') }}">
                        <button class="btn btn-outline-secondary">
                            <i class="fa fa-search"></i>
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
                                <small class="text-muted">{{ __('instructor.enrolled') }}: {{ $enrollment->created_at->format('M d, Y') }}</small>
                            </div>
                            <div class="col-md-2">
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar" style="width: {{ $enrollment->progress }}%"></div>
                                </div>
                                <small class="text-muted">{{ $enrollment->progress }}% {{ __('instructor.complete') }}</small>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-envelope {{ session('locale', 'ar') === 'ar' ? 'ms-1' : 'me-1' }}"></i>
                                    {{ __('instructor.message') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="fa fa-people text-muted" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mt-3">{{ __('instructor.no_students_enrolled') }}</h5>
                    <p class="text-muted">{{ __('instructor.promote_course_to_get_students') }}</p>
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
                                <i class="fa fa-star-fill text-warning"></i>
                                <i class="fa fa-star-fill text-warning"></i>
                                <i class="fa fa-star-fill text-warning"></i>
                                <i class="fa fa-star-fill text-warning"></i>
                                <i class="fa fa-star-half text-warning"></i>
                            </div>
                            <p class="text-muted">{{ __('instructor.based_on') }} 25 {{ __('instructor.reviews') }}</p>
                        </div>
                    </div>
                    <div class="col-md-8">
                        @for($i = 5; $i >= 1; $i--)
                        <div class="mb-2">
                            <div class="d-flex align-items-center">
                                <span class="me-2">{{ $i }}</span>
                                <i class="fa fa-star-fill text-warning me-2"></i>
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
                                                <i class="fa fa-star-fill text-warning small"></i>
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
        <h6 class="mb-0">{{ __('instructor.quick_actions') }}</h6>
    </div>
    <div class="card-body">
        <div class="d-grid gap-2">
            <a href="{{ route('instructor.courses.edit', $course) }}" class="btn btn-outline-primary btn-sm">
                <i class="fa fa-pencil {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('instructor.edit_course') }}
            </a>
            <button class="btn btn-outline-success btn-sm">
                <i class="fa fa-plus {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('instructor.add_lesson') }}
            </button>
            <button class="btn btn-outline-info btn-sm">
                <i class="fa fa-share {{ session('locale', 'ar') === 'ar' ? 'ms-2' : 'me-2' }}"></i>
                {{ __('instructor.share_course') }}
            </button>
        </div>
    </div>
</div>

<!-- Course Performance -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white">
        <h6 class="mb-0">{{ __('instructor.performance') }}</h6>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <small class="text-muted">{{ __('instructor.completion_rate') }}</small>
                <small class="fw-bold">78%</small>
            </div>
            <div class="progress" style="height: 6px;">
                <div class="progress-bar bg-success" style="width: 78%"></div>
            </div>
        </div>
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <small class="text-muted">{{ __('instructor.engagement') }}</small>
                <small class="fw-bold">85%</small>
            </div>
            <div class="progress" style="height: 6px;">
                <div class="progress-bar bg-primary" style="width: 85%"></div>
            </div>
        </div>
        <div class="mb-0">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <small class="text-muted">{{ __('instructor.satisfaction') }}</small>
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
        <h6 class="mb-0">{{ __('instructor.recent_activity') }}</h6>
    </div>
    <div class="card-body">
        <div class="activity-item mb-3">
            <div class="d-flex align-items-start">
                <div class="activity-icon bg-success text-white rounded-circle {{ session('locale', 'ar') === 'ar' ? 'ms-3' : 'me-3' }}" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa fa-person-plus small"></i>
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
                    <i class="fa fa-star small"></i>
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
                    <i class="fa fa-check small"></i>
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
        if (confirm('{{ __("instructor.confirm_publish_course") }}')) {
            fetch(`/instructor/courses/${courseId}/publish`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'instructorlication/json',
                }
            }).then(response => {
                if (response.ok) {
                    location.reload();
                }
            });
        }
    }

    function deleteCourse(courseId) {
        if (confirm('{{ __("instructor.delete_course_warning") }}')) {
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