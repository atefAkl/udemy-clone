@extends('layouts.instructor-wide')

@section('title', __('instructor.create_course'))

@section('content-header')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="m-0">{{ __('instructor.create_new_course') }}</h1>
        <p class="text-muted mb-0">{{ __('instructor.create_course_description') }}</p>
    </div>
    <a href="{{ route('instructor.courses.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        {{ __('instructor.back_to_courses') }}
    </a>
</div>

<!-- Progress Bar -->
<div class="progress mb-4" style="height: 6px;">
    <div class="progress-bar bg-primary" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
</div>

<!-- Progress Steps -->
<div class="d-flex justify-content-between mb-5 position-relative">
    <div class="text-center">
        <div class="mx-auto mb-2 d-flex align-items-center justify-content-center rounded-circle bg-primary text-white" style="width: 32px; height: 32px; font-size: 0.875rem;">1</div>
        <span class="small">{{ __('courses.basic_information') }}</span>
    </div>
    <div class="text-center">
        <div class="mx-auto mb-2 d-flex align-items-center justify-content-center rounded-circle bg-light border text-muted" style="width: 32px; height: 32px; font-size: 0.875rem;">2</div>
        <span class="small text-muted">{{ __('app.course_curriculum') }}</span>
    </div>
    <div class="text-center">
        <div class="mx-auto mb-2 d-flex align-items-center justify-content-center rounded-circle bg-light border text-muted" style="width: 32px; height: 32px; font-size: 0.875rem;">3</div>
        <span class="small text-muted">{{ __('app.course_landing_page') }}</span>
    </div>
    <div class="text-center">
        <div class="mx-auto mb-2 d-flex align-items-center justify-content-center rounded-circle bg-light border text-muted" style="width: 32px; height: 32px; font-size: 0.875rem;">4</div>
        <span class="small text-muted">{{ __('app.pricing') }}</span>
    </div>
    <div class="position-absolute w-100" style="top: 16px; z-index: -1; height: 2px; background-color: #e9ecef;">
        <div class="bg-primary" style="width: 25%; height: 100%;"></div>
    </div>
</div>
@endsection

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
    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Course Creation Form -->
    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('instructor.courses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Basic Information Card -->
                <div class="card dashboard-card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold d-flex align-items-center">
                            <span class="d-flex align-items-center justify-content-center rounded-circle bg-primary text-white me-2" style="width: 24px; height: 24px; font-size: 12px;">1</span>
                            {{ __('courses.basic_information') }}
                        </h5>
                        <span class="badge bg-light text-dark">Required</span>
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
                <div class="card dashboard-card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold d-flex align-items-center">
                            <span class="d-flex align-items-center justify-content-center rounded-circle bg-light border text-muted me-2" style="width: 24px; height: 24px; font-size: 12px;">2</span>
                            {{ __('app.launch_schedule') }}
                        </h5>
                        <span class="badge bg-light text-dark">Optional</span>
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
                                <input type="time" class="form-control @error('launch_time') is-invalid @enderror"
                                    id="launch_time" name="launch_time" value="{{ old('launch_time') }}">
                                @error('launch_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="has_certificate"
                                name="has_certificate" value="1" {{ old('has_certificate') ? 'checked' : '' }}>
                            <label class="form-check-label" for="has_certificate">
                                {{ __('app.course_includes_certificate') }}
                            </label>
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
        <div class="d-flex justify-content-between align-items-center mt-5 pt-4">
            <div>
                <a href="{{ route('instructor.courses.index') }}" class="btn btn-link text-muted px-0">
                    <i class="fas fa-times me-1"></i>
                    {{ __('app.cancel') }}
                </a>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary" disabled>
                    <i class="fas fa-arrow-left me-1"></i>
                    {{ __('app.previous') }}
                </button>
                <button type="submit" name="action" value="draft" class="btn btn-outline-secondary">
                    {{ __('app.save_as_draft') }}
                </button>
                <button type="button" class="btn btn-primary px-4" id="continueButton">
                    {{ __('app.continue') }}
                    <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </div>
        </div>
        </form>
    </div>

    <!-- Sidebar Tips -->
    <div class="col-lg-4">
        <div class="card dashboard-card">
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

<style>
    .form-section {
        margin-bottom: 2rem;
    }

    .form-section-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #eee;
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .required-field::after {
        content: '*';
        color: #dc3545;
        margin-inline-start: 4px;
    }

    .dashboard-card {
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        overflow: hidden;
        margin-bottom: 1.5rem;
        border: 1px solid #e9ecef;
    }

    .dashboard-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.15);
    }

    .card-header {
        background-color: #fff;
        border-bottom: 1px solid #e9ecef;
        padding: 1.25rem 1.5rem;
    }

    .card-body {
        padding: 1.5rem;
    }

    .btn-primary {
        font-weight: 600;
        padding: 0.5rem 1.5rem;
        border-radius: 4px;
    }

    .btn-outline-secondary {
        border-radius: 4px;
        padding: 0.5rem 1rem;
    }

    .preview-thumbnail {
        max-width: 200px;
        max-height: 150px;
        margin-top: 1rem;
        border: 1px solid #e3e6f0;
        border-radius: 5px;
        display: none;
    }
</style>

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
                            preview.className = 'img-thumbnail mt-2';
                            preview.style.maxWidth = '200px';
                            preview.style.maxHeight = '120px';
                            thumbnailInput.parentNode.appendChild(preview);
                        }
                        preview.src = e.target.result;
                        preview.style.display = 'block';
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
                        sizeInfo.className = 'small mt-1';
                        previewVideoInput.parentNode.appendChild(sizeInfo);
                    }
                    sizeInfo.innerHTML = `File size: ${fileSize} MB`;
                    sizeInfo.style.color = fileSize > 100 ? '#dc3545' : '#28a745';
                }
            });
        }

        // Form validation before submit
        const form = document.querySelector('form');
        const continueButton = document.getElementById('continueButton');

        function validateForm() {
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

            return isValid;
        }

        // Handle continue button click
        if (continueButton) {
            continueButton.addEventListener('click', function(e) {
                e.preventDefault();
                if (validateForm()) {
                    // Here you would typically submit the form or move to the next step
                    // For now, just show an alert
                    alert('Form is valid! Proceeding to next step...');
                    // form.submit();
                }
            });
        }

        // Handle form submission
        if (form) {
            form.addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
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