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
<div class="d-flex justify-content-between align-items-center pt-5 mb-4">

    <div class="course-pane-left">
        <div class="container course-editor-wrapper pt-5">
            <form action="{{ route('instructor.courses.update', $course) }}" method="POST" enctype="multipart/form-data" id="courseForm">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col col-12 ">
                        <div>
                            <h1 class="h3 mb-0">{{ __('instructor.edit_course') }}</h1>
                            <p class="text-muted mb-0">{{ __('instructor.update_course_information') }}</p>
                        </div>
                        <div class="buttons mb-4">
                            <a href="{{ route('instructor.courses.show', $course) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-home"></i> {{ __('instructor.back_to_home') }}
                            </a>
                            <a href="{{ route('instructor.courses.show', $course) }}" target="_blank" class="btn btn-outline-secondary">
                                <i class="fas fa-display"></i> {{ __('instructor.back_to_course') }}
                            </a>
                            <span id="saveStatus" class="ms-3 small text-muted" style="display:inline-block; min-width: 120px;">&nbsp;</span>
                        </div>
                    </div>
                    <div class="col-lg-12">

                        <!-- Course Information -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title fs-5 mb-4">{{ __('instructor.course_information') }}</h5>
                                <div class="row">
                                    <!-- Title -->
                                    <div class="col-12">
                                        <div class="input-group mb-2">
                                            <label for="title" class="input-group-text">{{ __('instructor.course_title') }} <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" required
                                                value="{{ old('title', $course->title) }}">
                                            <label data-bs-toggle="tooltip" data-bs-title="{{ __('instructor.course_title_help') }}" class="input-group-text"><i class="fa fa-info"></i></label>
                                        </div>
                                        @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 mb-3">
                                        <!-- Subtitle -->
                                        <div class="input-group mb-2">
                                            <label for="subtitle" class="input-group-text">{{ __('instructor.course_subtitle') }}</label>
                                            <input type="text" class="form-control @error('subtitle') is-invalid @enderror" id="subtitle" name="subtitle"
                                                value="{{ old('subtitle', $course->subtitle) }}" maxlength="120">
                                            <label data-bs-toggle="tooltip" data-bs-title="{{ __('instructor.course_subtitle_help') }}" class="input-group-text"><i class="fa fa-info"></i></label>
                                        </div>
                                        @error('subtitle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                        <!-- Description -->
                                        <fieldset class="mt-4 pt-4 px-0 pb-0">
                                            <legend style="margin-inline-start: 1rem;"> {{ __('instructor.course_description') }} </legend>
                                            <textarea id="description"
                                                class="form-control ckeditor @error('description') is-invalid @enderror"
                                                name="description" style="background-color: transparent; border: 0"
                                                rows="5" required>{{ old('description', $course->description) }}</textarea>
                                            @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </fieldset>

                                        <!-- Short Description -->
                                        <fieldset class="mt-4 pt-4 px-0 pb-0">
                                            <legend style="margin-inline-start: 1rem;"> {{ __('instructor.short_description') }} </legend>
                                            <textarea id="short_description"
                                                class="form-control @error('short_description') is-invalid @enderror"
                                                name="short_description" required style="background-color: transparent; border: 0"
                                                minlength="16" maxlength="160"
                                                rows="3">{{ old('short_description', $course->short_description) }}</textarea>
                                            <div class="form-text">{{ __('instructor.characters_remaining') }}: <span id="short-desc-count">160</span></div>
                                            @error('short_description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </fieldset>
                                    </div>
                                    <div class="col col-12 mb-3">
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
                                        </div>
                                        @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col col-12 mb-3">
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
                                    <div class="col-12 mb-3">
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

                                        </div>
                                        <div class="input-group">
                                            <input type="text" name="" id="pre-requirements" class="form-control">
                                            <button type="button" class="input-group-text btn-outline-primary" id="add-requirement">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
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
            </form>
        </div>
    </div>

    <div class="course-pane-right">
        <div class="card course-preview-card mb-3">
            <div class="card-body">
                <h5 class="card-title mb-3">{{ __('instructor.course_preview') }}</h5>
                <div class="ratio ratio-16x9 mb-3">
                    <img src="{{ $course->image ? asset('storage/' . $course->image) : asset('images/course-placeholder.jpg') }}"
                        alt="{{ $course->title }}"
                        class="img-fluid rounded"
                        id="image-preview">
                </div>
                <h3 class="h4 mb-1" id="preview-title">{{ old('title', $course->title) }}</h3>
                <p class="text-muted mb-3" id="preview-subtitle">{{ old('subtitle', $course->subtitle) }}</p>
                <p class="mb-0" id="preview-short">{{ old('short_description', $course->short_description) }}</p>
            </div>
        </div>
        <!-- Space for future: badges, duration, language, etc. -->
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/translations/{{ app()->getLocale() }}.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', async function() {
        // Initialize file previews
        initFilePreviews();

        // Initialize CKEditor for requirements
        await initEditors();

        // Set up event listeners
        setupEventListeners();

        // Initialize character counters
        initCharacterCounters();

        // Initialize live preview and autosave (edit page)
        initLivePreview();
        initAutosave();

    });

    function initFilePreviews() {
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
                        const removeCheckbox = document.getElementById('remove_thumbnail');
                        if (removeCheckbox) removeCheckbox.checked = false;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Video preview
        const videoInput = document.getElementById('preview_video');
        const videoPreview = document.getElementById('video-preview');
        const videoSource = document.getElementById('video-source');

        if (videoInput && videoSource) {
            videoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    videoSource.src = URL.createObjectURL(file);
                    videoPreview.load();
                    videoPreview.style.display = 'block';
                    const removeCheckbox = document.getElementById('remove_preview_video');
                    if (removeCheckbox) removeCheckbox.checked = false;
                }
            });
        }
    }

    async function initEditors() {
        // Initialize CKEditor for existing requirement fields
        const editors = [];
        const editorConfig = {
            language: '{{ app()->getLocale() }}',
            toolbar: ['bold', 'italic', 'link', 'bulletedList', 'numberedList', 'undo', 'redo'],
            removePlugins: ['Heading']
        };

        for (const element of document.querySelectorAll('.requirement-editor')) {
            if (!element.id) {
                element.id = `requirement-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
            }

            if (element.id && !element.hasAttribute('data-cke')) {
                try {
                    const editor = await ClassicEditor.create(element, editorConfig);
                    editors.push(editor);
                } catch (error) {
                    console.error('Error initializing editor:', error);
                }
            }
        }
        return editors;
    }

    function setupEventListeners() {
        // Form submission
        const form = document.getElementById('courseForm');
        if (form) {
            form.addEventListener('submit', handleFormSubmit);
        }

        // Add requirement button
        const addRequirementBtn = document.getElementById('add-requirement');
        if (addRequirementBtn) {
            addRequirementBtn.addEventListener('click', addRequirementField);
        }

        // Remove requirement (delegated event)
        document.addEventListener('click', handleRequirementRemoval);

    }

    async function handleFormSubmit(e) {
        // Check requirements
        const requirementEditors = document.querySelectorAll('.requirement-editor');
        for (const editorElement of requirementEditors) {
            const editor = ClassicEditor.instances[editorElement.id];
            if (editor) {
                const content = editor.getData().trim();
                if (content === '' || content === '<p>&nbsp;</p>') {
                    e.preventDefault();
                    alert('{{ __("instructor.course.requirement_required") }}');
                    return false;
                }
            }
        }
        return true;
    }



    function handleRequirementRemoval(e) {
        if (!e.target.closest('.remove-requirement')) return;

        const item = e.target.closest('.requirement-item');
        if (!item) return;

        const allItems = document.querySelectorAll('.requirement-item');
        if (allItems.length <= 1) {
            // Clear the editor instead of removing it if it's the last one
            const editorElement = item.querySelector('.requirement-editor');
            if (editorElement) {
                if (editorElement.id && ClassicEditor.instances[editorElement.id]) {
                    ClassicEditor.instances[editorElement.id].setData('');
                } else {
                    editorElement.value = '';
                }
            }
            return;
        }

        // Remove the item and its editor
        const editorElement = item.querySelector('.requirement-editor');
        if (editorElement?.id && ClassicEditor.instances[editorElement.id]) {
            ClassicEditor.instances[editorElement.id].destroy()
                .then(() => item.remove())
                .catch(console.error);
        } else {
            item.remove();
        }
    }

    function initCharacterCounters() {
        const updateCounter = (element, counterId, maxLength) => {
            if (!element || !counterId) return;

            const update = () => {
                const counter = document.getElementById(counterId);
                if (counter) {
                    counter.textContent = maxLength - element.value.length;
                }
            };

            element.addEventListener('input', update);
            update(); // Initialize counter
        };

        updateCounter(
            document.getElementById('description'),
            'desc-count',
            1000
        );

        updateCounter(
            document.getElementById('short_description'),
            'short-desc-count',
            160
        );
    }

    // --- Live preview bindings ---
    function initLivePreview() {
        const titleInput = document.getElementById('title');
        const subtitleInput = document.getElementById('subtitle');
        const shortDescInput = document.getElementById('short_description');

        const previewTitle = document.getElementById('preview-title');
        const previewSubtitle = document.getElementById('preview-subtitle');
        const previewShort = document.getElementById('preview-short');

        if (titleInput && previewTitle) {
            titleInput.addEventListener('input', () => {
                previewTitle.textContent = titleInput.value;
            });
        }
        if (subtitleInput && previewSubtitle) {
            subtitleInput.addEventListener('input', () => {
                previewSubtitle.textContent = subtitleInput.value;
            });
        }
        if (shortDescInput && previewShort) {
            shortDescInput.addEventListener('input', () => {
                previewShort.textContent = shortDescInput.value;
            });
        }
    }

    // --- Autosave (Edit mode only) ---
    function initAutosave() {
        // Assume edit page; if you later use same template for create, gate by a server-provided flag
        const form = document.getElementById('courseForm');
        if (!form) return;

        const tokenInput = form.querySelector('input[name="_token"]');
        const saveStatus = document.getElementById('saveStatus');
        const updateUrl = form.getAttribute('action');

        // Debounce helper
        const debounce = (fn, delay = 300) => {
            let t;
            return (...args) => {
                clearTimeout(t);
                t = setTimeout(() => fn(...args), delay);
            };
        };

        const setStatus = (state, text) => {
            if (!saveStatus) return;
            if (state === 'saving') {
                saveStatus.classList.remove('text-success', 'text-danger');
                saveStatus.classList.add('text-muted');
                saveStatus.textContent = text || '{{ __("Saving...") }}';
            } else if (state === 'saved') {
                saveStatus.classList.remove('text-muted', 'text-danger');
                saveStatus.classList.add('text-success');
                saveStatus.textContent = text || '{{ __("Saved") }}';
                setTimeout(() => {
                    if (saveStatus.textContent === text || text == null) saveStatus.textContent = '';
                }, 2000);
            } else if (state === 'error') {
                saveStatus.classList.remove('text-muted', 'text-success');
                saveStatus.classList.add('text-danger');
                saveStatus.textContent = text || '{{ __("Error while saving") }}';
            } else {
                saveStatus.textContent = '';
            }
        };

        // Determine if an element should autosave
        const shouldAutosave = (el) => {
            if (!el || !el.name) return false;
            // Exclude files and explicit file-related controls from autosave
            if (el.type === 'file') return false;
            if (el.name === 'thumbnail' || el.name === 'preview_video') return false;
            if (el.name === 'remove_thumbnail' || el.name === 'remove_preview_video') return false;
            return true;
        };

        // Build payload for a single element change
        const buildPayload = (el) => {
            // Requirements array: send all values
            if (el.name === 'requirements[]') {
                const values = Array.from(document.querySelectorAll('.requirement-editor'))
                    .map(t => t.value?.trim?.() ?? '')
                    .filter(v => v !== '');
                return {
                    requirements: values
                };
            }

            // Normal fields
            let value;
            if (el.type === 'checkbox') {
                value = el.checked ? 1 : 0;
            } else {
                value = el.value;
            }
            const key = el.name;
            return {
                [key]: value
            };
        };

        const syncCkEditorTextarea = (el) => {
            // For CKEditor-backed textareas ensure value is current before sending
            // If ClassicEditor instance exists, pull data
            try {
                if (el && el.id && window.ClassicEditor && ClassicEditor.instances && ClassicEditor.instances[el.id]) {
                    el.value = ClassicEditor.instances[el.id].getData();
                }
            } catch (e) {
                /* noop */
            }
        };

        const sendPatch = async (payload) => {
            setStatus('saving');
            try {
                const res = await fetch(updateUrl, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': tokenInput ? tokenInput.value : ''
                    },
                    body: JSON.stringify(payload)
                });
                if (!res.ok) throw new Error('HTTP ' + res.status);
                setStatus('saved');
            } catch (err) {
                setStatus('error');
                // Optional: console.error(err);
            }
        };

        const debouncedSend = debounce(sendPatch, 350);

        // Attach listeners
        const onBlurOrChange = (e) => {
            const el = e.target;
            if (!shouldAutosave(el)) return;

            // Sync CKEditor-backed textareas
            if (el.classList.contains('ckeditor') || el.classList.contains('requirement-editor')) {
                syncCkEditorTextarea(el);
            }

            const payload = buildPayload(el);
            debouncedSend(payload);
        };

        // Inputs and selects
        form.querySelectorAll('input, select, textarea').forEach(el => {
            el.addEventListener('blur', onBlurOrChange);
            el.addEventListener('change', onBlurOrChange);
        });
    }
</script>
@endpush