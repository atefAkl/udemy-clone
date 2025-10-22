@extends('layouts.instructor-wide')

@section('title', __('instructor.create_course'))

@section('content')
<div class="container-fluid p-0">
    <x-page-title
        title="{{ __('instructor.create_new_course') }}"
        description="{{ __('instructor.create_course_description') }}"
        icon="fa fa-home"
        btn_url="{{ route('instructor.courses.index') }}"
        btn_text="{{ __('courses.back_to_courses') }}" />
    <!-- Parse Form Validation Errors -->
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
        <div class="col-lg-8 mb">

            <!-- Basic Information Card -->
            <div class="card dashboard-card mb-4 border-0 shadow-sm">
                <form action="{{ route('instructor.courses.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="card-header bg-primary text-white border-0 py-2 d-flex justify-content-between align-items-center">
                        <h5 class="fs-5 mb-0 d-flex align-items-center">
                            <span class="d-flex align-items-center justify-content-center rounded-circle bg-light border text-muted me-2" style="width: 24px; height: 24px; font-size: 12px;">1</span>
                            {{ __('courses.basic_information') }}
                        </h5>
                        <span class="badge bg-light text-dark"> (<b class="text-danger mx-2">*</b>) Equals Required Field</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="input-group">
                                    <label for="title" class="input-group-text">{{ __('courses.course_title') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" value="{{ old('title') }}" maxlength="100"
                                        placeholder="{{ __('courses.enter_course_title') }}" required>
                                </div>
                                <div class="form-text">{{ __('courses.title_max_100_chars') }}</div>
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="input-group">
                                    <label for="short_description" class="input-group-text">{{ __('courses.short_description') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('short_description') is-invalid @enderror"
                                        id="short_description" name="short_description" value="{{ old('short_description') }}" maxlength="160"
                                        placeholder="{{ __('courses.enter_short_description') }}" required>
                                </div>
                                <div class="form-text">{{ __('courses.short_description_max_160_chars') }}</div>
                                @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-floating">
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                        id="description" name="description" rows="4" maxlength="500"
                                        placeholder="{{ __('app.enter_course_description') }}" required>{{ old('description') }}</textarea>
                                    <label for="description">{{ __('courses.course_description') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="form-text">{{ __('app.description_max_500_chars') }}</div>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="input-group">
                                    <label for="category_id" class="input-group-text">{{ __('courses.category') }} <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category_id') is-invalid @enderror"
                                        id="category_id" name="category_id" required>
                                        <option value="">{{ __('courses.select_category') }}</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="input-group">
                                    <label for="language" class="input-group-text">{{__('courses.language')}} <span class="text-danger px-2 fw-bold"> *</span></label>
                                    <select name="language" id="language" class="form-select" required>
                                        <option value="">{{__('courses.select_language')}}</option>
                                        @foreach (__('courses.languages') as $key => $language)
                                        <option value="{{$key}}" @if ($key==old('language')) selected @endif>{{$language}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('language')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="input-group">
                                    <label for="target_level" class="input-group-text">{{ __('courses.target_level') }} <span class="text-danger">*</span></label>
                                    <select class="form-select @error('target_level') is-invalid @enderror"
                                        id="target_level" name="target_level" required>
                                        <option value="">{{ __('courses.select_target_level') }}</option>
                                        <option value="beginner" {{ old('target_level') == 'beginner' ? 'selected' : '' }}>{{ __('courses.beginner') }}</option>
                                        <option value="intermediate" {{ old('target_level') == 'intermediate' ? 'selected' : '' }}>{{ __('courses.intermediate') }}</option>
                                        <option value="advanced" {{ old('target_level') == 'advanced' ? 'selected' : '' }}>{{ __('courses.advanced') }}</option>
                                        <option value="professional" {{ old('target_level') == 'professional' ? 'selected' : '' }}>{{ __('courses.professional') }}</option>
                                    </select>
                                </div>
                                @error('target_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="input-group">
                                    <label for="price" class="input-group-text">{{ __('courses.price') }} <span class="text-danger">*</span></label>
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                        id="price" name="price" value="{{ old('price', $course->price) }}" min="0" step="0.01" required>
                                </div>
                                <div class="form-text">{{ __('courses.set_zero_for_free_course') }}</div>
                                @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Intended Learners Card -->

                    <div class="card-header bg-primary text-white border-0 py-2 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fs-5 d-flex align-items-center">
                            <span class="d-flex align-items-center justify-content-center rounded-circle bg-light border text-muted me-2" style="width: 24px; height: 24px; font-size: 12px;">2</span>
                            {{ __('courses.intended_learners') }}
                        </h5>
                        <span class="badge bg-light text-dark"> (<b class="text-danger mx-2">*</b>) {{__('courses.required_field')}}</span>
                    </div>
                    <div class="card-body">
                        <!-- What students will learn -->
                        <div class="mb-4">
                            <label for="learning_outcomes_input" class="form-label text-dark fs-6"><b>{{ __('courses.what_will_students_learn') }}</b></label>
                            <p class="small text-muted">{{ __('courses.learning_outcomes_description') }}</p>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" id="learning_outcomes_input" placeholder="{{ __('courses.learning_outcomes_placeholder') }}">
                                <button class="input-group-text btn btn-outline-secondary" type="button" id="add_learning_outcome_btn">{{ __('courses.add_to_list') }}</button>
                            </div>
                            <ul id="learning_outcomes_list" class="list-group list-group-flush ps-0">
                                <!-- Learning outcomes will be added here -->
                            </ul>
                        </div>

                        <!-- Course requirements -->
                        <div class="mb-4">
                            <label for="requirements_input" class="form-label fw-bold">{{ __('courses.course_requirements') }}</label>
                            <p class="small text-muted">{{ __('courses.requirements_description') }}</p>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" id="requirements_input" placeholder="{{ __('courses.requirements_placeholder') }}">
                                <button class="input-group-text btn btn-outline-secondary" type="button" id="add_requirement_btn">{{ __('courses.add_to_list') }}</button>
                            </div>
                            <ul id="requirements_list" class="list-group list-group-flush ps-0">
                                <!-- Requirements will be added here -->
                            </ul>
                        </div>

                        <!-- Target audience -->
                        <div>
                            <label for="target_audience_input" class="form-label fw-bold">{{ __('courses.target_audience') }}</label>
                            <p class="small text-muted">{{ __('courses.target_audience_description') }}</p>
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" id="target_audience_input" placeholder="{{ __('courses.target_audience_placeholder') }}">
                                <button class="input-group-text btn btn-outline-secondary" type="button" id="add_target_audience_btn">{{ __('courses.add_to_list') }}</button>
                            </div>
                            <ul id="target_audience_list" class="list-group list-group-flush ps-0">
                                <!-- Target audience will be added here -->
                            </ul>
                        </div>

                    </div>
                    <div class="card-footer">
                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end bg-light gap-1">

                            <a href="{{ route('instructor.courses.index') }}" data-confirm-message="{{ __('courses.confirm_cancel_course_create') }}" onclick="return confirm(this.dataset.confirmMessage);" class="form-control btn-sm btn btn-outline-warning">
                                <i class="fas fa-times me-1"></i>
                                {{ __('courses.cancel') }}
                            </a>
                            <button type="submit" name="action" value="draft" class="form-control btn-sm btn btn-outline-success">
                                <i class="fas fa-paper-plane me-1"></i>
                                {{ __('courses.save_as_draft') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Sidebar Tips -->

        </div>
        <div class="col col-4">
            <div class="card dashboard-card border-0 shadow-sm">
                <div class="card-header py-2 bg-primary text-white">
                    <h6 class="mb-0 fs-5">
                        <i class="fas fa-lightbulb" style="margin-inline-end: 10px;"></i>
                        {{ __('courses.course_creation_tips') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">{{ __('courses.compelling_title') }}</h6>
                        <p class="small text-muted">{{ __('courses.title_tip') }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-success">{{ __('courses.clear_description') }}</h6>
                        <p class="small text-muted">{{ __('courses.description_tip') }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-info">{{ __('courses.attractive_thumbnail') }}</h6>
                        <p class="small text-muted">{{ __('courses.thumbnail_tip') }}</p>
                    </div>
                    <div class="mb-0">
                        <h6 class="text-warning">{{ __('courses.competitive_pricing') }}</h6>
                        <p class="small text-muted">{{ __('courses.pricing_tip') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    ['target_audience_input', 'learning_outcomes_input', 'requirements_input'].forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('keypress', function(e) {
                // prevent enter key from submitting the form
                if (e.key === 'Enter') {
                    e.preventDefault();
                    element.nextElementSibling.click();
                }
            });
        }
    });
</script>

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
        // Function to handle dynamic list creation
        function setupDynamicList(inputId, addButtonId, listId, inputName) {
            const inputField = document.getElementById(inputId);
            const addButton = document.getElementById(addButtonId);
            const list = document.getElementById(listId);
            const form = list.closest('form');

            addButton.addEventListener('click', function() {
                const value = inputField.value.trim();
                if (value) {
                    // Create list item
                    const listItem = document.createElement('li');
                    listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                    listItem.textContent = value;

                    // Create hidden input
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = `${inputName}[]`;
                    hiddenInput.value = value;

                    // Create delete button
                    const deleteButton = document.createElement('button');
                    deleteButton.type = 'button';
                    deleteButton.className = 'btn-close';
                    deleteButton.setAttribute('aria-label', 'Close');

                    deleteButton.addEventListener('click', function() {
                        listItem.remove();
                        hiddenInput.remove();
                    });

                    listItem.appendChild(deleteButton);
                    list.appendChild(listItem);
                    form.appendChild(hiddenInput);

                    // Clear input field
                    inputField.value = '';
                }
            });
        }

        // Setup for Learning Outcomes
        setupDynamicList('learning_outcomes_input', 'add_learning_outcome_btn', 'learning_outcomes_list', 'learning_outcomes');

        // Setup for Requirements
        setupDynamicList('requirements_input', 'add_requirement_btn', 'requirements_list', 'requirements');

        // Setup for Target Audience
        setupDynamicList('target_audience_input', 'add_target_audience_btn', 'target_audience_list', 'target_audience');

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