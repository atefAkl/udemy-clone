@extends('layouts.instructor-simple')

@section('title', __('app.edit_course'))

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}">{{ __('app.instructor_dashboard') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('instructor.courses.index') }}">{{ __('app.my_courses') }}</a></li>
        <li class="breadcrumb-item active">{{ __('app.edit_course') }}</li>
    </ol>
</nav>
@endsection

@section('sidebar-nav')
@php
$isRTL = session('locale', 'ar') === 'ar';
$align = $isRTL ? 'text-end' : 'text-start';
$reverseAlign = $isRTL ? 'text-start' : 'text-end';
$marginEnd = $isRTL ? 'ms-2' : 'me-2';
$marginStart = $isRTL ? 'me-2' : 'ms-2';
@endphp

<div class="nav-section">
    <h6 class="nav-section-title">{{ __('app.course_management') }}</h6>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('instructor.courses.index') }}">
                <i class="fas fa-list {{ $marginEnd }}"></i>{{ __('app.all_courses') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('instructor.courses.create') }}">
                <i class="fas fa-plus {{ $marginEnd }}"></i>{{ __('app.create_course') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('instructor.courses.edit', $course) }}">
                <i class="fas fa-edit {{ $marginEnd }}"></i>{{ __('app.edit_course') }}
            </a>
        </li>
    </ul>
</div>

<div class="nav-section">
    <h6 class="nav-section-title">{{ __('app.quick_actions') }}</h6>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('instructor.courses.show', $course) }}">
                <i class="fas fa-eye {{ $marginEnd }}"></i>{{ __('app.view_course') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('instructor.dashboard') }}">
                <i class="fas fa-chart-bar {{ $marginEnd }}"></i>{{ __('app.analytics') }}
            </a>
        </li>
    </ul>
</div>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">{{ __('app.edit_course') }}</h1>
        <p class="text-muted mb-0">{{ __('app.update_course_information') }}</p>
    </div>
    <a href="{{ route('instructor.courses.show', $course) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left {{ $marginEnd }}"></i>{{ __('app.back_to_course') }}
    </a>
</div>

<form action="{{ route('instructor.courses.update', $course) }}" method="POST" enctype="multipart/form-data" id="courseForm">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('app.basic_information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">{{ __('app.course_title') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $course->title) }}" required>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="short_description" class="form-label">{{ __('app.short_description') }}</label>
                        <textarea class="form-control @error('short_description') is-invalid @enderror" id="short_description" name="short_description" rows="3">{{ old('short_description', $course->short_description) }}</textarea>
                        @error('short_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">{{ __('app.course_description') }} <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="6" required>{{ old('description', $course->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">{{ __('app.category') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                <option value="">{{ __('app.select_category') }}</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="level" class="form-label">{{ __('app.course_level') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('level') is-invalid @enderror" id="level" name="level" required>
                                <option value="">{{ __('app.select_level') }}</option>
                                <option value="beginner" {{ old('level', $course->level) === 'beginner' ? 'selected' : '' }}>{{ __('app.beginner') }}</option>
                                <option value="intermediate" {{ old('level', $course->level) === 'intermediate' ? 'selected' : '' }}>{{ __('app.intermediate') }}</option>
                                <option value="advanced" {{ old('level', $course->level) === 'advanced' ? 'selected' : '' }}>{{ __('app.advanced') }}</option>
                            </select>
                            @error('level')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('app.pricing') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">{{ __('app.course_price') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $course->price) }}" min="0" step="0.01" required>
                            </div>
                            @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="discount_price" class="form-label">{{ __('app.discount_price') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control @error('discount_price') is-invalid @enderror" id="discount_price" name="discount_price" value="{{ old('discount_price', $course->discount_price) }}" min="0" step="0.01">
                            </div>
                            <small class="form-text text-muted">{{ __('app.optional_discount_price') }}</small>
                            @error('discount_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Image -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('app.course_image') }}</h5>
                </div>
                <div class="card-body">
                    @if($course->thumbnail)
                    <div class="mb-3">
                        <label class="form-label">{{ __('app.current_image') }}</label>
                        <div>
                            <img src="{{ asset('storage/courses/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="img-thumbnail" style="max-width: 200px;">
                        </div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="thumbnail" class="form-label">{{ __('app.upload_new_image') }}</label>
                        <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" id="thumbnail" name="thumbnail" accept="image/*">
                        <small class="form-text text-muted">{{ __('app.image_requirements') }}</small>
                        @error('thumbnail')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Course Requirements -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('app.course_requirements') }}</h5>
                </div>
                <div class="card-body">
                    <div id="requirements-container">
                        @if(old('requirements', $course->requirements))
                        @foreach(old('requirements', $course->requirements) as $index => $requirement)
                        <div class="input-group mb-2 requirement-item">
                            <input type="text" class="form-control" name="requirements[]" value="{{ $requirement }}" placeholder="{{ __('app.requirement_placeholder') }}">
                            <button type="button" class="btn btn-outline-danger remove-requirement">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        @endforeach
                        @else
                        <div class="input-group mb-2 requirement-item">
                            <input type="text" class="form-control" name="requirements[]" placeholder="{{ __('app.requirement_placeholder') }}">
                            <button type="button" class="btn btn-outline-danger remove-requirement">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        @endif
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="add-requirement">
                        <i class="fas fa-plus {{ $marginEnd }}"></i>{{ __('app.add_requirement') }}
                    </button>
                </div>
            </div>

            <!-- What You'll Learn -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('app.what_you_learn') }}</h5>
                </div>
                <div class="card-body">
                    <div id="learning-container">
                        @if(old('what_you_learn', $course->what_you_learn))
                        @foreach(old('what_you_learn', $course->what_you_learn) as $index => $learning)
                        <div class="input-group mb-2 learning-item">
                            <input type="text" class="form-control" name="what_you_learn[]" value="{{ $learning }}" placeholder="{{ __('app.learning_placeholder') }}">
                            <button type="button" class="btn btn-outline-danger remove-learning">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        @endforeach
                        @else
                        <div class="input-group mb-2 learning-item">
                            <input type="text" class="form-control" name="what_you_learn[]" placeholder="{{ __('app.learning_placeholder') }}">
                            <button type="button" class="btn btn-outline-danger remove-learning">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        @endif
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="add-learning">
                        <i class="fas fa-plus {{ $marginEnd }}"></i>{{ __('app.add_learning_point') }}
                    </button>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save {{ $marginEnd }}"></i>{{ __('app.update_course') }}
                </button>
                <a href="{{ route('instructor.courses.show', $course) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times {{ $marginEnd }}"></i>{{ __('app.cancel') }}
                </a>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Course Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">{{ __('app.course_status') }}</h6>
                </div>
                <div class="card-body text-center">
                    @if($course->status === 'published')
                    <div class="text-success mb-3">
                        <i class="fas fa-check-circle fa-3x"></i>
                    </div>
                    <h6 class="text-success">{{ __('app.published') }}</h6>
                    <p class="text-muted small">{{ __('app.course_is_live') }}</p>
                    @elseif($course->status === 'draft')
                    <div class="text-warning mb-3">
                        <i class="fas fa-edit fa-3x"></i>
                    </div>
                    <h6 class="text-warning">{{ __('app.draft') }}</h6>
                    <p class="text-muted small">{{ __('app.course_in_draft') }}</p>
                    @else
                    <div class="text-info mb-3">
                        <i class="fas fa-clock fa-3x"></i>
                    </div>
                    <h6 class="text-info">{{ __('app.pending_review') }}</h6>
                    <p class="text-muted small">{{ __('app.course_under_review') }}</p>
                    @endif
                </div>
            </div>

            <!-- Course Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">{{ __('app.course_statistics') }}</h6>
                </div>
                <div class="card-body">
                    <div class="stat-item mb-3">
                        <div class="stat-value">{{ $course->enrollments_count ?? 0 }}</div>
                        <div class="stat-label">{{ __('app.enrolled_students') }}</div>
                    </div>
                    <div class="stat-item mb-3">
                        <div class="stat-value">{{ $course->lessons_count ?? 0 }}</div>
                        <div class="stat-label">{{ __('app.total_lessons') }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ number_format($course->average_rating ?? 0, 1) }}</div>
                        <div class="stat-label">{{ __('app.average_rating') }}</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">{{ __('app.quick_actions') }}</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('instructor.courses.show', $course) }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                        <i class="fas fa-eye {{ $marginEnd }}"></i>{{ __('app.view_course') }}
                    </a>
                    @if($course->status === 'draft')
                    <form action="{{ route('instructor.courses.publish', $course) }}" method="POST" class="mb-2">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success btn-sm w-100">
                            <i class="fas fa-upload {{ $marginEnd }}"></i>{{ __('app.publish_course') }}
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('instructor.courses.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="fas fa-list {{ $marginEnd }}"></i>{{ __('app.all_courses') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('widgets')
<div class="widget-card">
    <div class="widget-header">
        <h6 class="widget-title">{{ __('app.editing_tips') }}</h6>
    </div>
    <div class="widget-body">
        <div class="tip-item mb-3">
            <div class="tip-icon">
                <i class="fas fa-lightbulb text-warning"></i>
            </div>
            <div class="tip-content">
                <small>{{ __('app.course_title_tip') }}</small>
            </div>
        </div>
        <div class="tip-item mb-3">
            <div class="tip-icon">
                <i class="fas fa-image text-info"></i>
            </div>
            <div class="tip-content">
                <small>{{ __('app.course_image_tip') }}</small>
            </div>
        </div>
        <div class="tip-item">
            <div class="tip-icon">
                <i class="fas fa-dollar-sign text-success"></i>
            </div>
            <div class="tip-content">
                <small>{{ __('app.pricing_tip') }}</small>
            </div>
        </div>
    </div>
</div>
@endsection

@if(session('locale', 'ar') === 'ar')
<style>
    /* RTL-specific styles */
    .input-group .btn {
        border-right: 1px solid #dee2e6;
        border-left: 0;
    }

    .tip-item {
        display: flex;
        flex-direction: row-reverse;
        align-items: flex-start;
    }

    .tip-icon {
        margin-left: 0.5rem;
        margin-right: 0;
    }
</style>
@else
<style>
    /* LTR-specific styles */
    .tip-item {
        display: flex;
        align-items: flex-start;
    }

    .tip-icon {
        margin-right: 0.5rem;
    }
</style>
@endif

<style>
    .widget-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
    }

    .widget-header {
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e9ecef;
    }

    .widget-title {
        margin: 0;
        font-weight: 600;
        color: #495057;
    }

    .stat-item {
        text-align: center;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: bold;
        color: #007bff;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .nav-section {
        margin-bottom: 2rem;
    }

    .nav-section-title {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #6c757d;
        margin-bottom: 0.5rem;
        padding: 0 1rem;
    }

    .tip-item {
        margin-bottom: 1rem;
    }

    .tip-content {
        flex: 1;
    }
</style>

<script>
    // Add requirement functionality
    document.getElementById('add-requirement').addEventListener('click', function() {
        const container = document.getElementById('requirements-container');
        const newItem = document.createElement('div');
        newItem.className = 'input-group mb-2 requirement-item';
        newItem.innerHTML = `
        <input type="text" class="form-control" name="requirements[]" placeholder="{{ __('app.requirement_placeholder') }}">
        <button type="button" class="btn btn-outline-danger remove-requirement">
            <i class="fas fa-trash"></i>
        </button>
    `;
        container.appendChild(newItem);
    });

    // Remove requirement functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-requirement')) {
            e.target.closest('.requirement-item').remove();
        }
    });

    // Add learning point functionality
    document.getElementById('add-learning').addEventListener('click', function() {
        const container = document.getElementById('learning-container');
        const newItem = document.createElement('div');
        newItem.className = 'input-group mb-2 learning-item';
        newItem.innerHTML = `
        <input type="text" class="form-control" name="what_you_learn[]" placeholder="{{ __('app.learning_placeholder') }}">
        <button type="button" class="btn btn-outline-danger remove-learning">
            <i class="fas fa-trash"></i>
        </button>
    `;
        container.appendChild(newItem);
    });

    // Remove learning point functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-learning')) {
            e.target.closest('.learning-item').remove();
        }
    });

    // Form validation
    document.getElementById('courseForm').addEventListener('submit', function(e) {
        const title = document.getElementById('title').value.trim();
        const description = document.getElementById('description').value.trim();
        const category = document.getElementById('category_id').value;
        const level = document.getElementById('level').value;
        const price = document.getElementById('price').value;

        if (!title || !description || !category || !level || !price) {
            e.preventDefault();
            alert('{{ __("app.please_fill_required_fields") }}');
        }
    });
</script>