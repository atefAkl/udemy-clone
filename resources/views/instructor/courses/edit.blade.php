@extends('layouts.instructor-wide')

@push('styles')
<style>
    .ck-editor__editable {
        min-height: 200px;
    }

    .ck.ck-editor {
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('title', __('instructor.edit_course'))

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}">{{ __('instructor.instructor_dashboard') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('instructor.courses.index') }}">{{ __('instructor.my_courses') }}</a></li>
        <li class="breadcrumb-item active">{{ __('instructor.edit_course') }}</li>
    </ol>
</nav>
@endsection

@section('content')
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">{{ __('instructor.edit_course') }}</h1>
        <p class="text-muted mb-0">{{ __('instructor.update_course_information') }}</p>
    </div>
    <div class="buttons">
        <a href="{{ route('instructor.courses.show', $course) }}" class="btn btn-outline-secondary">
            <i class="fas fa-home"></i> {{ __('instructor.back_to_home') }}
        </a>
        <a href="{{ route('instructor.courses.show', $course) }}" target="_blank" class="btn btn-outline-secondary">
            <i class="fas fa-display"></i> {{ __('instructor.back_to_course') }}
        </a>
    </div>
</div>

<form action="{{ route('instructor.courses.update', $course) }}" method="POST" enctype="multipart/form-data" id="courseForm">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-lg-8">

            <!-- Course Information -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title fs-5 mb-4">{{ __('instructor.course_information') }}</h5>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-group mb-2">
                                <label for="title" class="input-group-text">{{ __('instructor.course_title') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" required
                                    value="{{ old('title', $course->title) }}">
                            </div>
                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ __('instructor.course_title_help') }}</div>
                        </div>

                        <div class="col-12">
                            <div class="input-group mb-2">
                                <label for="subtitle" class="input-group-text">{{ __('instructor.course_subtitle') }}</label>
                                <input type="text" class="form-control @error('subtitle') is-invalid @enderror" id="subtitle" name="subtitle"
                                    value="{{ old('subtitle', $course->subtitle) }}" maxlength="120">
                            </div>
                            @error('subtitle')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ __('instructor.course_subtitle_help') }}</div>
                        </div>
                    </div>


                    <!-- Title -->


                    <!-- Subtitle -->
                    <div class="mb-3">

                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="form-label">{{ __('instructor.course_description') }} <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description', $course->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Short Description -->
                    <div class="mb-3">
                        <label for="short_description" class="form-label">{{ __('instructor.short_description') }} <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('short_description') is-invalid @enderror"
                            id="short_description" name="short_description" required
                            minlength="16" maxlength="160"
                            rows="3">{{ old('short_description', $course->short_description) }}</textarea>
                        <div class="form-text">{{ __('instructor.characters_remaining') }}: <span id="short-desc-count">160</span></div>
                        @error('short_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="mb-3">
                        <div class="input-group">
                            <label for="category_id" class="input-group-text">{{ __('instructor.category') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                <option value="">{{ __('instructor.select_category') }}</option>
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
                    </div>

                    <!-- Duration -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="input-group">
                                <label for="duration" class="input-group-text">{{ __('instructor.duration') }} <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('duration') is-invalid @enderror"
                                    id="duration" name="duration" required min="1" step="0.5"
                                    value="{{ old('duration', $course->duration) }}">
                                <span class="input-group-text">{{ __('instructor.hours') }}</span>
                                @error('duration')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Access Duration (simplified) -->
                        <div class="col-md-6 mb-3">
                            <div class="input-group">
                                <label for="access_duration_value" class="input-group-text">{{ __('instructor.access_duration') }} <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('access_duration_value') is-invalid @enderror"
                                    id="access_duration_value" name="access_duration_value" min="1" required
                                    value="{{ old('access_duration_value', $course->access_duration_value ?? 365) }}">
                                <select class="form-select" name="access_duration_unit" style="max-width: 120px;" required>
                                    <option value="days" {{ old('access_duration_unit', $course->access_duration_unit ?? 'days') == 'days' ? 'selected' : '' }}>{{ __('instructor.days') }}</option>
                                    <option value="weeks" {{ old('access_duration_unit', $course->access_duration_unit) == 'weeks' ? 'selected' : '' }}>{{ __('instructor.weeks') }}</option>
                                    <option value="months" {{ old('access_duration_unit', $course->access_duration_unit) == 'months' ? 'selected' : '' }}>{{ __('instructor.months') }}</option>
                                    <option value="years" {{ old('access_duration_unit', $course->access_duration_unit) == 'years' ? 'selected' : '' }}>{{ __('instructor.years') }}</option>
                                </select>
                                @error('access_duration_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">{{ __('instructor.access_duration_help') }}</div>
                        </div>
                    </div>

                    <!-- Certificate Toggle -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="has_certificate"
                                    name="has_certificate" value="1" {{ old('has_certificate', $course->has_certificate) ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_certificate">{{ __('instructor.include_certificate') }}</label>
                                @error('has_certificate')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Launch Date -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="input-group">
                                <label for="launch_date" class="input-group-text">{{ __('instructor.launch_date') }}</label>
                                <input type="date" class="form-control @error('launch_date') is-invalid @enderror"
                                    id="launch_date" name="launch_date"
                                    min="{{ date('Y-m-d') }}"
                                    value="{{ old('launch_date', $course->launch_date ? $course->launch_date : '') }}">
                                @error('launch_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col col-md-6 mb-2">
                            <div class="input-group">
                                <span class="input-group-text">{{ __('instructor.course_language') }} <span class="text-danger">*</span></span>
                                <select class="form-select @error('language') is-invalid @enderror" id="language" name="language" required>
                                    <option value="">{{ __('instructor.select_language') }}</option>
                                    <option value="ar" {{ old('language', $course->language) == 'ar' ? 'selected' : '' }}>{{ __('Arabic') }}</option>
                                    <option value="en" {{ old('language', $course->language) == 'en' ? 'selected' : '' }}>{{ __('English') }}</option>
                                </select>
                                @error('language')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col col-md-6 mb-2">
                            <label class="form-label">{{ __('instructor.course.requirements') }}</label>
                            <div id="requirements-container">
                                @php
                                $requirements = old('requirements', $course->requirements ?? []);
                                if (is_string($requirements)) {
                                $requirements = [$requirements];
                                }
                                @endphp
                                @if(count($requirements) > 0)
                                @foreach($requirements as $index => $requirement)
                                <div class="mb-3 requirement-item">
                                    <textarea class="form-control requirement-editor" name="requirements[]" id="requirement-{{ $index }}" rows="3">{{ $requirement }}</textarea>
                                    <button type="button" class="btn btn-sm btn-outline-danger mt-2 remove-requirement">
                                        <i class="fas fa-trash"></i> {{ __('instructor.course.remove_requirement') }}
                                    </button>
                                </div>
                                @endforeach
                                @else
                                <div class="mb-3 requirement-item">
                                    <textarea class="form-control requirement-editor" name="requirements[]" id="requirement-0" rows="3"></textarea>
                                    <button type="button" class="btn btn-sm btn-outline-danger mt-2 remove-requirement">
                                        <i class="fas fa-trash"></i> {{ __('instructor.course.remove_requirement') }}
                                    </button>
                                </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-requirement">
                                <i class="fas fa-plus"></i> {{ __('instructor.course.add_requirement') }}
                            </button>
                        </div>

                        <div class="col col-md-6 mb-2">

                            @if($course->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $course->image) }}" alt="Course Image" class="img-thumbnail mt-2" style="max-height: 150px;">
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Course Media -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title fs-5 mb-4">{{ __('instructor.media') }}</h5>

                <!-- Course Thumbnail -->
                <div class="form-group mb-4">
                    <label for="thumbnail" class="form-label">{{ __('instructor.course_thumbnail') }} <span class="text-muted">({{ __('instructor.max_size') }}: 5MB)</span></label>
                    <input type="file" class="form-control @error('thumbnail') is-invalid @enderror"
                        id="thumbnail" name="thumbnail" accept="image/jpeg,image/png,image/jpg">
                    @error('thumbnail')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="mt-3">
                        @if($course->thumbnail)
                        <img src="{{ Storage::url($course->thumbnail) }}"
                            id="thumbnail-preview"
                            alt="{{ __('instructor.current_thumbnail') }}"
                            class="img-thumbnail d-block"
                            style="max-height: 200px;">
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox"
                                id="remove_thumbnail" name="remove_thumbnail" value="1">
                            <label class="form-check-label" for="remove_thumbnail">
                                {{ __('instructor.remove_thumbnail') }}
                            </label>
                        </div>
                        @else
                        <img src="{{ asset('images/course-placeholder.jpg') }}"
                            id="thumbnail-preview"
                            alt="{{ __('instructor.thumbnail_preview') }}"
                            class="img-thumbnail d-block"
                            style="max-height: 200px; display: none;">
                        @endif
                    </div>
                </div>

                <!-- Preview Video -->
                <div class="form-group mb-4">
                    <label for="preview_video" class="form-label">{{ __('instructor.preview_video') }} ({{ __('instructor.optional') }})</label>
                    <input type="file" class="form-control @error('preview_video') is-invalid @enderror"
                        id="preview_video" name="preview_video" accept="video/mp4,video/quicktime,video/x-msvideo">
                    @error('preview_video')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">{{ __('instructor.video_help') }} ({{ __('instructor.max_size') }}: 100MB, {{ __('instructor.supported_formats') }}: MP4, MOV, AVI)</div>

                    <div class="mt-3">
                        @if($course->preview_video)
                        <video controls class="w-100" id="video-preview" style="max-height: 200px;">
                            <source id="video-source" src="{{ Storage::url($course->preview_video) }}" type="video/mp4">
                            {{ __('instructor.video_not_supported') }}
                        </video>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox"
                                id="remove_preview_video" name="remove_preview_video" value="1">
                            <label class="form-check-label" for="remove_preview_video">
                                {{ __('instructor.remove_preview_video') }}
                            </label>
                        </div>
                        @else
                        <video controls class="w-100" id="video-preview" style="max-height: 200px; display: none;">
                            <source id="video-source" src="" type="video/mp4">
                            {{ __('instructor.video_not_supported') }}
                        </video>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Right Sidebar -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title fs-5">{{ __('instructor.course_status') }}</h5>
                    <div class="mb-3">
                        <select class="form-select @error('status') is-invalid @enderror" name="status" id="status">
                            <option value="draft" {{ old('status', $course->status) == 'draft' ? 'selected' : '' }}>{{ __('instructor.draft') }}</option>
                            <option value="pending" {{ old('status', $course->status) == 'pending' ? 'selected' : '' }}>{{ __('instructor.pending_review') }}</option>
                            @if($course->status == 'published')
                            <option value="published" selected>{{ __('instructor.published') }}</option>
                            @endif
                        </select>
                        @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> {{ __('instructor.save_changes') }}
                        </button>

                        @if($course->status == 'published')
                        <a href="{{ route('courses.show', $course) }}" target="_blank" class="btn btn-outline-secondary">
                            <i class="fas fa-eye me-1"></i> {{ __('instructor.view_course') }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title fs-5">{{ __('instructor.course_preview') }}</h5>
                    <div class="ratio ratio-16x9 mb-3">
                        <img src="{{ $course->image ? asset('storage/' . $course->image) : asset('images/course-placeholder.jpg') }}"
                            alt="{{ $course->title }}"
                            class="img-fluid rounded"
                            id="image-preview">
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="is_free" name="is_free" value="1" {{ old('is_free', $course->is_free) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_free">{{ __('instructor.free_course') }}</label>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="featured" name="featured" value="1" {{ old('featured', $course->featured) ? 'checked' : '' }}>
                        <label class="form-check-label" for="featured">{{ __('instructor.featured_course') }}</label>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fs-5">{{ __('instructor.course_actions') }}</h5>
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteCourseModal">
                            <i class="fas fa-trash me-1"></i> {{ __('instructor.delete_course') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>


        <!-- Delete Course Modal -->
        <div class="modal fade" id="deleteCourseModal" tabindex="-1" aria-labelledby="deleteCourseModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteCourseModalLabel">{{ __('instructor.delete_course') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>{{ __('instructor.confirm_delete_course') }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('instructor.cancel') }}</button>
                        <form action="{{ route('instructor.courses.delete', $course) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">{{ __('instructor.delete') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/translations/{{ app()->getLocale() }}.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', async function() {
        // Thumbnail preview
        const thumbnailInput = document.getElementById('thumbnail');
        const thumbnailPreview = document.getElementById('thumbnail-preview');

        if (thumbnailInput) {
            thumbnailInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        thumbnailPreview.src = e.target.result;
                        thumbnailPreview.style.display = 'block';
                        // Hide remove checkbox when new file is selected
                        const removeCheckbox = document.getElementById('remove_thumbnail');
                        if (removeCheckbox) {
                            removeCheckbox.checked = false;
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Preview video file selection
        const videoInput = document.getElementById('preview_video');
        const videoPreview = document.getElementById('video-preview');
        const videoSource = document.getElementById('video-source');

        if (videoInput) {
            videoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const videoUrl = URL.createObjectURL(file);
                    videoSource.src = videoUrl;
                    videoPreview.load();
                    videoPreview.style.display = 'block';
                    // Hide remove checkbox when new file is selected
                    const removeCheckbox = document.getElementById('remove_preview_video');
                    if (removeCheckbox) {
                        removeCheckbox.checked = false;
                    }
                }
            });
        }
        // Initialize CKEditor for description
        let descriptionEditor;
        try {
            descriptionEditor = await ClassicEditor
                .create(document.querySelector('#description'), {
                    language: '{{ app()->getLocale() }}',
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo'],
                    heading: {
                        options: [{
                                model: 'paragraph',
                                title: 'Paragraph',
                                class: 'ck-heading_paragraph'
                            },
                            {
                                model: 'heading2',
                                view: 'h2',
                                title: 'Heading 2',
                                class: 'ck-heading_heading2'
                            },
                            {
                                model: 'heading3',
                                view: 'h3',
                                title: 'Heading 3',
                                class: 'ck-heading_heading3'
                            },
                            {
                                model: 'heading4',
                                view: 'h4',
                                title: 'Heading 4',
                                class: 'ck-heading_heading4'
                            }
                        ]
                    }
                });
        } catch (error) {
            console.error('Error initializing CKEditor:', error);
        }

        // Initialize CKEditor for existing requirement fields
        const initEditors = async () => {
            const editors = [];
            document.querySelectorAll('.requirement-editor').forEach((element, index) => {
                if (!element.id) {
                    element.id = `requirement-${Date.now()}-${index}`;
                }
                if (element.id && !element.hasAttribute('data-cke')) {
                    ClassicEditor
                        .create(element, {
                            language: '{{ app()->getLocale() }}',
                            toolbar: ['bold', 'italic', 'link', 'bulletedList', 'numberedList', 'undo', 'redo'],
                            removePlugins: ['Heading']
                        })
                        .then(editor => {
                            editors.push(editor);
                        })
                        .catch(error => {
                            console.error('Error initializing requirement editor:', error);
                        });
                }
            });
            return editors;
        };

        // Initialize existing editors
        await initEditors();

        // Update form submission to include CKEditor content
        document.getElementById('courseForm').addEventListener('submit', async function(e) {
            // Check description
            if (descriptionEditor) {
                const descriptionContent = descriptionEditor.getData().trim();
                if (descriptionContent === '' || descriptionContent === '<p>&nbsp;</p>') {
                    e.preventDefault();
                    alert('{{ __("instructor.course.description_required") }}');
                    return false;
                }
            }

            // Check requirements
            const requirementEditors = document.querySelectorAll('.requirement-editor');
            let hasEmptyRequirement = false;

            for (const editorElement of requirementEditors) {
                const editor = await ClassicEditor.instances[editorElement.id];
                if (editor) {
                    const content = editor.getData().trim();
                    if (content === '' || content === '<p>&nbsp;</p>') {
                        hasEmptyRequirement = true;
                        break;
                    }
                }
            }

            if (hasEmptyRequirement) {
                e.preventDefault();
                alert('{{ __("instructor.course.requirement_required") }}');
                return false;
            }
        });

        // Add requirement field
        document.getElementById('add-requirement').addEventListener('click', async function() {
            const container = document.getElementById('requirements-container');
            const newItem = document.createElement('div');
            newItem.className = 'mb-3 requirement-item';
            const newId = `requirement-${Date.now()}`;
            newItem.innerHTML = `
                <textarea class="form-control requirement-editor" name="requirements[]" id="${newId}" rows="3"></textarea>
                <button type="button" class="btn btn-sm btn-outline-danger mt-2 remove-requirement">
                    <i class="fas fa-trash"></i> {{ __('instructor.course.remove_requirement') }}
                </button>
            `;
            container.appendChild(newItem);

            // Initialize CKEditor for the new requirement
            try {
                await ClassicEditor
                    .create(document.getElementById(newId), {
                        language: '{{ app()->getLocale() }}',
                        toolbar: ['bold', 'italic', 'link', 'bulletedList', 'numberedList', 'undo', 'redo'],
                        removePlugins: ['Heading']
                    });
            } catch (error) {
                console.error('Error initializing new requirement editor:', error);
            }
        });

        // Remove requirement field
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-requirement')) {
                const item = e.target.closest('.requirement-item');
                if (item) {
                    // Don't remove the last item
                    if (document.querySelectorAll('.requirement-item').length > 1) {
                        // Destroy CKEditor instance if it exists
                        const editorElement = item.querySelector('.requirement-editor');
                        if (editorElement && editorElement.id && ClassicEditor.instances[editorElement.id]) {
                            ClassicEditor.instances[editorElement.id].destroy()
                                .then(() => {
                                    item.remove();
                                })
                                .catch(console.error);
                        } else {
                            item.remove();
                        }
                    } else {
                        // Clear the editor instead of removing it
                        const editorElement = item.querySelector('.requirement-editor');
                        if (editorElement && editorElement.id && ClassicEditor.instances[editorElement.id]) {
                            ClassicEditor.instances[editorElement.id].setData('');
                        } else if (editorElement) {
                            editorElement.value = '';
                        }
                    }
                }
            }
        });
    });

    // Toggle access duration value field based on radio selection
    document.querySelectorAll('input[name="access_duration_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const container = document.getElementById('access_duration_value_container');
            container.style.display = this.value === 'limited' ? 'block' : 'none';

            // Make the field required/not required based on selection
            const input = document.getElementById('access_duration_value');
            if (this.value === 'limited') {
                input.setAttribute('required', 'required');
            } else {
                input.removeAttribute('required');
            }
        });
    });

    // Character counters for textareas
    const description = document.getElementById('description');
    const shortDescription = document.getElementById('short_description');
    const descCount = document.getElementById('desc-count');
    const shortDescCount = document.getElementById('short-desc-count');

    if (description && descCount) {
        description.addEventListener('input', function() {
            const remaining = 1000 - this.value.length;
            descCount.textContent = remaining;
        });
        // Trigger once on load
        const event = new Event('input');
        description.dispatchEvent(event);
    }

    if (shortDescription && shortDescCount) {
        shortDescription.addEventListener('input', function() {
            const remaining = 160 - this.value.length;
            shortDescCount.textContent = remaining;
        });
        // Trigger once on load
        const event = new Event('input');
        shortDescription.dispatchEvent(event);
    }
</script>
@endpush