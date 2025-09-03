@extends('layouts.dashboard')

@section('title', __('instructor.my_courses'))

@section('sidebar-nav')
<a href="{{ route('instructor.dashboard') }}" class="nav-link">
    <i class="fas fa-tachometer-alt" style="margin-inline-end: 10px;"></i>
    {{ __('app.dashboard') }}
</a>
<a href="{{ route('instructor.courses.index') }}" class="nav-link active">
    <i class="fas fa-book" style="margin-inline-end: 10px;"></i>
    {{ __('instructor.my_courses') }}
</a>
<a href="{{ route('instructor.courses.create') }}" class="nav-link">
    <i class="fas fa-plus-circle" style="margin-inline-end: 10px;"></i>
    {{ __('instructor.create_new_course') }}
</a>
<a href="#" class="nav-link">
    <i class="fas fa-chart-line" style="margin-inline-end: 10px;"></i>
    {{ __('instructor.analytics') }}
</a>
<a href="#" class="nav-link">
    <i class="fas fa-dollar-sign" style="margin-inline-end: 10px;"></i>
    {{ __('instructor.earnings') }}
</a>
<a href="#" class="nav-link">
    <i class="fas fa-cog" style="margin-inline-end: 10px;"></i>
    {{ __('instructor.account_settings') }}
</a>
@endsection

@section('content')
<div class="container-fluid p-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('instructor.dashboard') }}">{{ __('app.dashboard') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ __('instructor.my_courses') }}</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">{{ __('instructor.my_courses') }}</h2>
            <p class="text-muted">{{ __('instructor.manage_your_courses') }}</p>
        </div>
        <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary">
            <i class="fas fa-plus" style="margin-inline-end: 8px;"></i>
            {{ __('instructor.create_new_course') }}
        </a>
    </div>

    <!-- Filters and Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">{{ __('courses.search') }}</label>
                    <input type="text" class="form-control" id="search" name="search"
                        value="{{ request('search') }}" placeholder="{{ __('courses.search_by_title') }}">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">{{ __('courses.status') }}</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">{{ __('courses.all_statuses') }}</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>
                            {{ __('courses.draft') }}
                        </option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>
                            {{ __('courses.published') }}
                        </option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                            {{ __('instructor.pending_review') }}
                        </option>
                    </select>
                </div>


                <div class="col-md-3">
                    <label for="category" class="form-label">{{ __('courses.category') }}</label>
                    <select class="form-select" id="category" name="category">
                        <option value="">{{ __('courses.all_categories') }}</option>

                        @forelse($courses_categories as $category)
                        <option value="{{ $category['id'] }}" {{ request('category') == $category['id'] ? 'selected' : '' }}>
                            {{ $category['name'] }}
                        </option>
                        @empty
                        <option value="">{{ __('courses.no_categories') }}</option>
                        @endforelse
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-search" style="margin-inline-end: 8px;"></i>
                            {{ __('courses.filter') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Courses Grid -->
    <div class="row">
        @forelse($courses as $course)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="position-relative">
                    @if($course->thumbnail)
                    <img src="{{ asset('storage/courses/' . $course->thumbnail) }}" class="card-img-top" alt="{{ $course->title }}" style="height: 200px; object-fit: cover;">
                    @else
                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                        <i class="fas fa-book fa-3x text-muted"></i>
                    </div>
                    @endif

                    <!-- Status Badge -->
                    <div class="position-absolute top-0 end-0 m-2">
                        @if($course->status == 'published')
                        <span class="badge bg-success">{{ __('courses.published') }}</span>
                        @elseif($course->status == 'draft')
                        <span class="badge bg-warning">{{ __('courses.draft') }}</span>
                        @else
                        <span class="badge bg-info">{{ __('instructor.pending_review') }}</span>
                        @endif
                    </div>

                    <!-- Quick Actions -->
                    <div class="position-absolute bottom-0 end-0 m-2">
                        <div class="btn-group" role="group">
                            <a href="{{ route('instructor.courses.show', $course->id) }}"
                                class="btn btn-sm btn-light" title="{{ __('courses.view_course') }}">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('instructor.courses.edit', $course->id) }}"
                                class="btn btn-sm btn-primary" title="{{ __('courses.edit_course') }}">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <h5 class="card-title">{{ $course->title }}</h5>
                    <p class="card-text text-muted small">{{ Str::limit($course->short_description, 100) }}</p>

                    <!-- Course Stats -->
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <div class="text-primary">
                                <i class="fas fa-users"></i>
                                <div class="small">{{ $course->enrollments_count ?? 0 }}</div>
                                <div class="x-small text-muted">{{ __('instructor.students') }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <div class="small">{{ number_format($course->average_rating ?? 0, 1) }}</div>
                                <div class="x-small text-muted">{{ __('instructor.rating') }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-info">
                                <i class="fas fa-play-circle"></i>
                                <div class="small">{{ $course->lessons_count ?? 0 }}</div>
                                <div class="x-small text-muted">{{ __('instructor.lessons') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            @if($course->discount_price && $course->discount_price < $course->price)
                                <span class="h6 text-success mb-0">${{ number_format($course->discount_price, 2) }}</span>
                                <small class="text-muted text-decoration-line-through ms-1">${{ number_format($course->price, 2) }}</small>
                                @else
                                <span class="h6 text-primary mb-0">${{ number_format($course->price, 2) }}</span>
                                @endif
                        </div>
                        <small class="text-muted">{{ $course->created_at->format('M d, Y') }}</small>
                    </div>

                    <!-- Category -->
                    @if($course->category)
                    <div class="mb-3">
                        <span class="badge bg-light text-dark">
                            @if($course->category->icon)
                            <i class="{{ $course->category->icon }} me-1"></i>
                            @endif
                            {{ $course->category->name }}
                        </span>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <a href="{{ route('instructor.courses.show', $course->id) }}" class="btn btn-outline-primary btn-sm flex-fill">
                            <i class="fas fa-eye me-1"></i>{{ __('instructor.view_course') }}
                        </a>
                        <a href="{{ route('instructor.courses.edit', $course->id) }}" class="btn btn-primary btn-sm flex-fill">
                            <i class="fas fa-edit me-1"></i>{{ __('instructor.edit_course') }}
                        </a>
                        @if($course->status === 'draft')
                        <form action="{{ route('instructor.courses.publish', $course->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm" title="{{ __('instructor.publish_course') }}">
                                <i class="fas fa-upload"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                <h5>{{ __('instructor.no_courses_yet') }}</h5>
                <p class="text-muted">{{ __('instructor.create_first_course_message') }}</p>
                <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>{{ __('instructor.create_first_course') }}
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($courses->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $courses->links() }}
    </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('courses.confirm_delete') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('courses.delete_course_warning') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    {{ __('courses.cancel') }}
                </button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        {{ __('courses.delete') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(courseId) {
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = `/instructor/courses/${courseId}`;
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
</script>
@endsection

@section('widgets')
<!-- Course Statistics -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0">
        <h6 class="mb-0">
            <i class="fas fa-chart-bar text-primary" style="margin-inline-end: 10px;"></i>
            {{ __('instructor.course_statistics') }}
        </h6>
    </div>
    <div class="card-body">
        <div class="d-flex align-items-center p-3 bg-primary bg-opacity-10 rounded mb-3">
            <div style="margin-inline-end: 15px;">
                <i class="fas fa-book text-primary fa-2x"></i>
            </div>
            <div>
                <h4 class="fw-bold text-primary mb-0">{{ count($courses) }}</h4>
                <small class="text-muted">{{ __('instructor.total_courses') }}</small>
            </div>
        </div>
        <div class="d-flex align-items-center p-3 bg-success bg-opacity-10 rounded mb-3">
            <div style="margin-inline-end: 15px;">
                <i class="fas fa-eye text-success fa-2x"></i>
            </div>
            <div>
                <h4 class="fw-bold text-success mb-0">{{ '' }}</h4>
                <small class="text-muted">{{ __('instructor.total_enrollments') }}</small>
            </div>
        </div>
        <div class="d-flex align-items-center p-3 bg-warning bg-opacity-10 rounded">
            <div style="margin-inline-end: 15px;">
                <i class="fas fa-dollar-sign text-warning fa-2x"></i>
            </div>
            <div>
                <h4 class="fw-bold text-warning mb-0">${{ '' }}</h4>
                <small class="text-muted">{{ __('instructor.total_value') }}</small>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0">
        <h6 class="mb-0">
            <i class="fas fa-bolt text-warning" style="margin-inline-end: 10px;"></i>
            {{ __('instructor.quick_actions') }}
        </h6>
    </div>
    <div class="card-body">
        <div class="d-grid gap-2">
            <a href="{{ route('instructor.courses.create') }}" class="btn btn-outline-primary">
                <i class="fas fa-plus" style="margin-inline-end: 10px;"></i>{{ __('instructor.create_course') }}
            </a>
            <a href="#" class="btn btn-outline-success">
                <i class="fas fa-chart-line" style="margin-inline-end: 10px;"></i>{{ __('instructor.view_analytics') }}
            </a>
            <a href="#" class="btn btn-outline-info">
                <i class="fas fa-dollar-sign" style="margin-inline-end: 10px;"></i>{{ __('instructor.earnings_report') }}
            </a>
            <a href="#" class="btn btn-outline-secondary">
                <i class="fas fa-cog" style="margin-inline-end: 10px;"></i>{{ __('instructor.course_settings') }}
            </a>
        </div>
    </div>
</div>
@endsection