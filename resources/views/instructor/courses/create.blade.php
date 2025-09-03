@extends('layouts.instructor-simple')

@section('title', __('instructor.create_course'))

@section('sidebar-nav')
<a href="{{ route('instructor.dashboard') }}" class="nav-link">
    <i class="fas fa-tachometer-alt" style="margin-inline-end: 10px;"></i>
    {{ __('instructor.dashboard') }}
</a>
<a href="{{ route('instructor.courses.index') }}" class="nav-link">
    <i class="fas fa-book" style="margin-inline-end: 10px;"></i>
    {{ __('instructor.my_courses') }}
</a>
<a href="{{ route('instructor.courses.create') }}" class="nav-link active">
    <i class="fas fa-plus-circle" style="margin-inline-end: 10px;"></i>
    {{ __('instructor.create_course') }}
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
    {{ __('app.settings') }}
</a>
@endsection

@section('content')
<div class="container-fluid p-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('instructor.dashboard') }}">{{ __('instructor.dashboard') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('instructor.courses.index') }}">{{ __('instructor.my_courses') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ __('instructor.create_course') }}</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">{{ __('instructor.create_new_course') }}</h2>
            <p class="text-muted">{{ __('instructor.create_course_description') }}</p>
        </div>
        <a href="{{ route('instructor.courses.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left" style="margin-inline-end: 8px;"></i>
            {{ __('instructor.back_to_courses') }}
        </a>
    </div>

    <!-- Course Creation Form -->
    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('instructor.courses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Basic Information Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle text-primary" style="margin-inline-end: 10px;"></i>
                            {{ __('courses.basic_information') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="title" class="form-label">{{ __('courses.course_title') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title') }}" maxlength="100"
                                    placeholder="{{ __('courses.enter_course_title') }}" required>
                                <div class="form-text">{{ __('courses.title_max_100_chars') }}</div>
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="short_description" class="form-label">{{ __('courses.short_description') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('short_description') is-invalid @enderror"
                                    id="short_description" name="short_description" value="{{ old('short_description') }}" maxlength="160"
                                    placeholder="{{ __('courses.enter_short_description') }}" required>
                                <div class="form-text">{{ __('courses.short_description_max_160_chars') }}</div>
                                @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">{{ __('courses.course_description') }} <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                    id="description" name="description" rows="4" maxlength="500"
                                    placeholder="{{ __('app.enter_course_description') }}" required>{{ old('description') }}</textarea>
                                <div class="form-text">{{ __('app.description_max_500_chars') }}</div>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">{{ __('app.category') }} <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror"
                                    id="category_id" name="category_id" required>
                                    <option value="">{{ __('app.select_category') }}</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="language" class="form-label">{{ __('app.language') }} <span class="text-danger">*</span></label>
                                <select class="form-select @error('language') is-invalid @enderror"
                                    id="language" name="language" required>
                                    <option value="">{{ __('app.select_language') }}</option>
                                    <option value="ar" {{ old('language') == 'ar' ? 'selected' : '' }}>{{ __('app.arabic') }}</option>
                                    <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>{{ __('app.english') }}</option>
                                </select>
                                @error('language')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="target_level" class="form-label">{{ __('app.target_level') }} <span class="text-danger">*</span></label>
                                <select class="form-select @error('target_level') is-invalid @enderror"
                                    id="target_level" name="target_level" required>
                                    <option value="">{{ __('app.select_target_level') }}</option>
                                    <option value="beginner" {{ old('target_level') == 'beginner' ? 'selected' : '' }}>{{ __('app.beginner') }}</option>
                                    <option value="intermediate" {{ old('target_level') == 'intermediate' ? 'selected' : '' }}>{{ __('app.intermediate') }}</option>
                                    <option value="advanced" {{ old('target_level') == 'advanced' ? 'selected' : '' }}>{{ __('app.advanced') }}</option>
                                    <option value="professional" {{ old('target_level') == 'professional' ? 'selected' : '' }}>{{ __('app.professional') }}</option>
                                </select>
                                @error('target_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">{{ __('app.price') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                        id="price" name="price" value="{{ old('price', 0) }}" min="0" step="0.01" required>
                                </div>
                                <div class="form-text">{{ __('app.set_zero_for_free_course') }}</div>
                                @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Launch Schedule Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-alt text-success" style="margin-inline-end: 10px;"></i>
                            {{ __('app.launch_schedule') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="launch_date" class="form-label">{{ __('app.launch_date') }}</label>
                                <input type="date" class="form-control @error('launch_date') is-invalid @enderror"
                                    id="launch_date" name="launch_date" value="{{ old('launch_date') }}">
                                @error('launch_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="launch_time" class="form-label">{{ __('app.launch_time') }}</label>
                                <input type="datetime-local" class="form-control @error('launch_time') is-invalid @enderror"
                                    id="launch_time" name="launch_time" value="{{ old('launch_time') }}">
                                @error('launch_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Media Content Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-photo-video text-info" style="margin-inline-end: 10px;"></i>
                            {{ __('app.media_content') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="thumbnail" class="form-label">{{ __('app.course_thumbnail') }}</label>
                                <input type="file" class="form-control @error('thumbnail') is-invalid @enderror"
                                    id="thumbnail" name="thumbnail" accept="image/*">
                                <div class="form-text">{{ __('app.recommended_size_1920x1080') }}</div>
                                @error('thumbnail')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="preview_video" class="form-label">{{ __('app.preview_video') }}</label>
                                <input type="file" class="form-control @error('preview_video') is-invalid @enderror"
                                    id="preview_video" name="preview_video" accept="video/*">
                                <div class="form-text">{{ __('app.duration_3_to_10_minutes') }}</div>
                                @error('preview_video')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Structure Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-list-ul text-warning" style="margin-inline-end: 10px;"></i>
                            {{ __('app.course_structure') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="requirements" class="form-label">{{ __('app.requirements') }}</label>
                                <textarea class="form-control @error('requirements') is-invalid @enderror"
                                    id="requirements" name="requirements" rows="3"
                                    placeholder="{{ __('app.enter_course_requirements') }}">{{ old('requirements') }}</textarea>
                                <div class="form-text">{{ __('app.requirements_can_be_customized_later') }}</div>
                                @error('requirements')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="objectives" class="form-label">{{ __('app.objectives') }}</label>
                                <textarea class="form-control @error('objectives') is-invalid @enderror"
                                    id="objectives" name="objectives" rows="3"
                                    placeholder="{{ __('app.enter_course_objectives') }}">{{ old('objectives') }}</textarea>
                                <div class="form-text">{{ __('app.objectives_can_be_customized_later') }}</div>
                                @error('objectives')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Certificate & Access Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-certificate text-primary" style="margin-inline-end: 10px;"></i>
                            {{ __('app.certificate_and_access') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="has_certificate"
                                        name="has_certificate" value="1" {{ old('has_certificate') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="has_certificate">
                                        {{ __('app.course_includes_certificate') }}
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="access_duration_type" class="form-label">{{ __('app.content_access') }}</label>
                                <select class="form-select @error('access_duration_type') is-invalid @enderror"
                                    id="access_duration_type" name="access_duration_type">
                                    <option value="unlimited" {{ old('access_duration_type', 'unlimited') == 'unlimited' ? 'selected' : '' }}>
                                        {{ __('app.unlimited_access') }}
                                    </option>
                                    <option value="limited" {{ old('access_duration_type') == 'limited' ? 'selected' : '' }}>
                                        {{ __('app.limited_access') }}
                                    </option>
                                </select>
                                @error('access_duration_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3" id="access_duration_field" style="display: none;">
                                <label for="access_duration_value" class="form-label">{{ __('app.access_duration_days') }}</label>
                                <input type="number" class="form-control @error('access_duration_value') is-invalid @enderror"
                                    id="access_duration_value" name="access_duration_value" value="{{ old('access_duration_value') }}" min="1">
                                <div class="form-text">{{ __('app.number_of_days_after_completion') }}</div>
                                @error('access_duration_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-3 mb-4">
                    <button type="submit" name="action" value="draft" class="btn btn-outline-primary">
                        <i class="fas fa-save" style="margin-inline-end: 8px;"></i>
                        {{ __('app.save_as_draft') }}
                    </button>
                    <button type="submit" name="action" value="publish" class="btn btn-primary">
                        <i class="fas fa-rocket" style="margin-inline-end: 8px;"></i>
                        {{ __('app.create_and_publish') }}
                    </button>
                    <a href="{{ route('instructor.courses.index') }}" class="btn btn-secondary">
                        {{ __('app.cancel') }}
                    </a>
                </div>
            </form>
        </div>

        <!-- Sidebar Tips -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb" style="margin-inline-end: 10px;"></i>
                        {{ __('app.course_creation_tips') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">{{ __('app.compelling_title') }}</h6>
                        <p class="small text-muted">{{ __('app.title_tip') }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-success">{{ __('app.clear_description') }}</h6>
                        <p class="small text-muted">{{ __('app.description_tip') }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-info">{{ __('app.attractive_thumbnail') }}</h6>
                        <p class="small text-muted">{{ __('app.thumbnail_tip') }}</p>
                    </div>
                    <div class="mb-0">
                        <h6 class="text-warning">{{ __('app.competitive_pricing') }}</h6>
                        <p class="small text-muted">{{ __('app.pricing_tip') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Access duration field toggle
        const accessDurationType = document.getElementById('access_duration_type');
        const accessDurationField = document.getElementById('access_duration_field');

        function toggleAccessDurationField() {
            if (accessDurationType.value === 'limited') {
                accessDurationField.style.display = 'block';
                document.getElementById('access_duration_value').required = true;
            } else {
                accessDurationField.style.display = 'none';
                document.getElementById('access_duration_value').required = false;
            }
        }

        // Initial check
        toggleAccessDurationField();

        // Listen for changes
        accessDurationType.addEventListener('change', toggleAccessDurationField);

        // Character count for text fields
        const titleField = document.getElementById('title');
        const shortDescField = document.getElementById('short_description');
        const descField = document.getElementById('description');

        function updateCharCount(field, maxLength) {
            const currentLength = field.value.length;
            const helpText = field.nextElementSibling;
            if (helpText && helpText.classList.contains('form-text')) {
                helpText.innerHTML = `${currentLength}/${maxLength} characters`;
                if (currentLength > maxLength * 0.9) {
                    helpText.style.color = '#dc3545';
                } else if (currentLength > maxLength * 0.8) {
                    helpText.style.color = '#fd7e14';
                } else {
                    helpText.style.color = '#6c757d';
                }
            }
        }

        if (titleField) {
            titleField.addEventListener('input', () => updateCharCount(titleField, 100));
        }

        if (shortDescField) {
            shortDescField.addEventListener('input', () => updateCharCount(shortDescField, 160));
        }

        if (descField) {
            descField.addEventListener('input', () => updateCharCount(descField, 500));
        }

        // File upload preview
        const thumbnailInput = document.getElementById('thumbnail');
        const previewVideoInput = document.getElementById('preview_video');

        if (thumbnailInput) {
            thumbnailInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Create preview if doesn't exist
                        let preview = document.getElementById('thumbnail-preview');
                        if (!preview) {
                            preview = document.createElement('img');
                            preview.id = 'thumbnail-preview';
                            preview.style.maxWidth = '200px';
                            preview.style.maxHeight = '120px';
                            preview.style.marginTop = '10px';
                            preview.style.borderRadius = '8px';
                            preview.style.border = '2px solid #dee2e6';
                            thumbnailInput.parentNode.appendChild(preview);
                        }
                        preview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        if (previewVideoInput) {
            previewVideoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const fileSize = (file.size / 1024 / 1024).toFixed(2); // MB
                    let sizeInfo = document.getElementById('video-size-info');
                    if (!sizeInfo) {
                        sizeInfo = document.createElement('div');
                        sizeInfo.id = 'video-size-info';
                        sizeInfo.style.marginTop = '5px';
                        sizeInfo.style.fontSize = '0.875rem';
                        previewVideoInput.parentNode.appendChild(sizeInfo);
                    }
                    sizeInfo.innerHTML = `File size: ${fileSize} MB`;
                    sizeInfo.style.color = fileSize > 100 ? '#dc3545' : '#28a745';
                }
            });
        }

        // Form validation before submit
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    // Scroll to first invalid field
                    const firstInvalid = form.querySelector('.is-invalid');
                    if (firstInvalid) {
                        firstInvalid.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        firstInvalid.focus();
                    }
                }
            });
        }
    });
</script>
@endsection

@section('widgets')
<!-- Course Statistics -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0">
        <h6 class="mb-0">
            <i class="fas fa-chart-bar text-primary" style="margin-inline-end: 10px;"></i>
            {{ __('app.your_statistics') }}
        </h6>
    </div>
    <div class="card-body">
        <div class="d-flex align-items-center p-3 bg-primary bg-opacity-10 rounded mb-3">
            <div style="margin-inline-end: 15px;">
                <i class="fas fa-book text-primary fa-2x"></i>
            </div>
            <div>
                <h4 class="fw-bold text-primary mb-0">{{ $totalCourses ?? 0 }}</h4>
                <small class="text-muted">{{ __('app.total_courses') }}</small>
            </div>
        </div>
        <div class="d-flex align-items-center p-3 bg-success bg-opacity-10 rounded mb-3">
            <div style="margin-inline-end: 15px;">
                <i class="fas fa-users text-success fa-2x"></i>
            </div>
            <div>
                <h4 class="fw-bold text-success mb-0">{{ $totalStudents ?? 0 }}</h4>
                <small class="text-muted">{{ __('app.total_students') }}</small>
            </div>
        </div>
        <div class="d-flex align-items-center p-3 bg-warning bg-opacity-10 rounded">
            <div style="margin-inline-end: 15px;">
                <i class="fas fa-star text-warning fa-2x"></i>
            </div>
            <div>
                <h4 class="fw-bold text-warning mb-0">{{ $averageRating ?? '0.0' }}</h4>
                <small class="text-muted">{{ __('app.average_rating') }}</small>
            </div>
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
            <a href="{{ route('instructor.courses.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-list" style="margin-inline-end: 10px;"></i>{{ __('app.view_all_courses') }}
            </a>
            <a href="#" class="btn btn-outline-success">
                <i class="fas fa-chart-line" style="margin-inline-end: 10px;"></i>{{ __('app.view_analytics') }}
            </a>
            <a href="#" class="btn btn-outline-info">
                <i class="fas fa-dollar-sign" style="margin-inline-end: 10px;"></i>{{ __('app.earnings_report') }}
            </a>
        </div>
    </div>
</div>
@endsection