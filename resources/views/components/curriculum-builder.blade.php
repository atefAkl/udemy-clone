@props([
'courseId' => null,
'sections' => []
])


<div class="curriculum-builder">

    <!-- Render Validation errors Here -->
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="curriculum-header d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fas fa-book me-2"></i>{{ __('courses.build_curriculum') }}</h4>
        <button type="button" class="btn btn-primary" id="addSectionBtn"
            data-bs-toggle="modal" data-bs-target="#createNewSection">
            <i class="fas fa-plus"></i> {{ __('courses.add_section') }}
        </button>
    </div>

    <div class="curriculum-container" id="curriculumContainer" data-course-id="{{ $courseId }}">
        <div class="accordion" id="curriculumAccordion">
            @forelse($sections as $section)
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button py-2 {{ $loop->first ? '' : 'collapsed' }} flex-grow-1" type="button" data-bs-toggle="collapse" data-bs-target="#section-{{ $section->id }}"
                        @if($section->description) title="{{ $section->description }}" @endif>
                        {{$section->sort_order}} - {{ $section->title }}

                    </button>
                </h2>
                <div id="section-{{ $section->id }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#curriculumAccordion">
                    <div class="accordion-body">
                        <div class="d-flex justify-content-end gap-2 align-items-center" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal" data-bs-target="#editSectionModal"
                                data-section-id="{{ $section->id }}"
                                data-section-title="{{ $section->title }}"
                                data-sort_order="{{ $section->sort_order }}"
                                data-section-description="{{ $section->description }}"
                                title="{{ __('courses.edit_section') }}">
                                <span title="{{ __('courses.edit_section') }}"><i class="fas fa-edit"></i> {{ __('courses.edit_section') }}</span>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal" data-bs-target="#deleteSectionModal"
                                data-section-id="{{ $section->id }}"
                                data-section-title="{{ $section->title }}"
                                data-section-lessons-count="{{ $section->lessons->count() }}"
                                title="{{ __('courses.delete_section') }}">
                                <span title="{{ __('courses.delete_section') }}"><i class="fas fa-trash"></i> {{ __('courses.delete_section') }}</span>
                            </button>
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-outline-success dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-expanded="false"
                                    title="{{ __('courses.add_lesson') }}">
                                    <i class="fas fa-plus me-1"></i>{{ __('courses.add_lesson') }}
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#videoLessonModal" data-section-id="{{ $section->id }}">
                                            <i class="fas fa-video me-2 text-danger"></i>{{ __('courses.video_lesson') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#lectureLessonModal" data-section-id="{{ $section->id }}">
                                            <i class="fas fa-book-reader me-2 text-primary"></i>{{ __('courses.lecture_lesson') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#presentationLessonModal" data-section-id="{{ $section->id }}">
                                            <i class="fas fa-file-powerpoint me-2 text-warning"></i>{{ __('courses.presentation_lesson') }}
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#quizLessonModal" data-section-id="{{ $section->id }}">
                                            <i class="fas fa-clipboard-question me-2 text-info"></i>{{ __('courses.quiz') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="lessons-grid row g-3 mt-2">
                            @forelse($section->lessons as $lesson)
                            <div class="col-md-6 col-lg-4">
                                <div class="card lesson-card shadow-sm h-100">
                                    <div class="card-header bg-white border-bottom">
                                        <h6 class="mb-0 text-truncate" title="{{ $lesson->title }}">
                                            <i class="fas fa-{{ $lesson->lesson_type === 'video' ? 'video' : 'book' }} me-2 text-primary"></i>
                                            {{ $lesson->title }}
                                        </h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row g-0">
                                            <!-- Video/Content Preview Section -->
                                            <div class="col-12">
                                                <div class="lesson-preview position-relative" style="padding-top: 56.25%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">

                                                    <div class="position-absolute top-50 start-50 translate-middle text-white">
                                                        <i class="fas fa-video fa-3x opacity-75"></i>
                                                        <p class="mt-2 small">{{ __('courses.'.$lesson->lesson_type) }}</p>
                                                    </div>

                                                </div>
                                            </div>

                                            <!-- Lesson Details Section -->
                                            <div class="col-12 p-3">
                                                <div class="lesson-meta">
                                                    <p class="text-muted small mb-2 lesson-description">
                                                        {{ $lesson->description ?? __('courses.no_description') }}
                                                    </p>
                                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                                        <span class="badge bg-light text-dark border">
                                                            <i class="fas fa-sort-numeric-up me-1"></i>
                                                            {{ __('courses.order') }}: {{ $lesson->sort_order }}
                                                        </span>
                                                        @if($lesson->lesson_type === 'video' && $lesson->duration)
                                                        <span class="badge bg-primary">
                                                            <i class="far fa-clock me-1"></i>
                                                            {{ $lesson->duration }} {{ __('courses.min') }}
                                                        </span>
                                                        @endif
                                                        <span class="badge bg-info text-white">
                                                            <i class="fas fa-{{ $lesson->video_source === 'upload' ? 'upload' : 'link' }} me-1"></i>
                                                            {{ ucfirst($lesson->video_source ?? 'N/A') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white border-top">
                                        <div class="d-flex justify-content-between align-items-center gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary flex-fill"
                                                title="{{ __('courses.view_lesson') }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary flex-fill"
                                                title="{{ __('courses.edit_lesson') }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger flex-fill"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteLessonModal"
                                                data-lesson-id="{{ $lesson->id }}"
                                                data-lesson-title="{{ $lesson->title }}"
                                                title="{{ __('courses.delete_lesson') }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12">
                                <div class="alert alert-info text-center mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    {{ __('courses.no_lessons_yet') }}
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>


            </div>
            @empty
            <p>No sections found</p>
            @endforelse
        </div>
    </div>

    <!-- Modals -->
    <!-- Modal -->
    <div class="modal fade" id="createNewSection" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="createNewSectionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('instructor.courses.sections.store', ['course' => $courseId]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $courseId }}">
                    <div class="modal-header py-2">
                        <h1 class="modal-title fs-5" id="createNewSectionLabel">
                            <i class="fas fa-plus-circle me-2"></i>{{ __('courses.add_new_section') }}
                        </h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-1">
                            <label for="title" class="input-group-text">{{__('courses.title')}}</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{old('title')}}">
                        </div>
                        <div class="form-floating mb-1">
                            <textarea name="description" placeholder="{{__('courses.enter_short_description')}}" id="short_description"
                                class="form-control">{{old('description')}}</textarea>
                            <label for="short_description">{{__('courses.short_description')}}</label>
                        </div>
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>{{ __('courses.cancel') }}
                        </button>
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i>{{ __('courses.add_section') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Section Modal -->
    <div class="modal fade" id="editSectionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editSectionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" data-action="{{route('instructor.courses.sections.update', ['section' => 'section_id'])}}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header py-2">
                        <h1 class="modal-title fs-5" id="editSectionModalLabel">
                            <i class="fas fa-edit me-2"></i>{{ __('courses.edit_section') }}
                        </h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-2">
                            <label for="sort_order" class="input-group-text">{{__('courses.sort_order')}}</label>
                            <input type="number" class="form-control" name="sort_order" id="sort_order" min="1" step="1" value="" required>
                        </div>
                        <div class="input-group mb-2">
                            <label for="edit-title" class="input-group-text">{{__('courses.title')}}</label>
                            <input type="text" class="form-control" id="edit-title" name="title" value="{{ $section->title }}" required>
                        </div>
                        <div class="form-floating mb-2">
                            <textarea name="description" placeholder="{{__('courses.enter_short_description')}}" id="edit-description"
                                class="form-control" style="height: 100px">{{ $section->description }}</textarea>
                            <label for="edit-description}">{{__('courses.short_description')}}</label>
                        </div>
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-sm" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>{{ __('courses.cancel') }}
                        </button>
                        <button type="submit" class="btn btn-sm">
                            <i class="fas fa-save me-1"></i>{{ __('courses.update') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Section Modal -->
    <div class="modal fade" id="deleteSectionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteSectionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="deleteSectionForm" method="POST" data-action="{{route('instructor.courses.sections.destroy', ['section' => 'section_id'])}}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header py-2 bg-danger text-white">
                        <h1 class="modal-title fs-5" id="deleteSectionModalLabel">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ __('courses.delete_section') }}
                        </h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                            <div>
                                <h5 class="alert-heading mb-2">{{ __('courses.warning') }}</h5>
                                <p class="mb-0" id="deleteSectionText"></p>
                                <p class="mb-0 mt-2 fw-bold text-danger" id="deleteSectionLessonsCount" style="display:none;">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <span id="lessonsCountText"></span>
                                </p>
                            </div>
                        </div>
                        <p class="text-muted mb-0">{{ __('courses.delete_action_irreversible') }}</p>
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>{{ __('courses.cancel') }}
                        </button>
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash me-1"></i>{{ __('courses.confirm_delete') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Lecture/Article Lesson Modal (Placeholder) -->
    <div class="modal fade" id="lectureLessonModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-book-reader me-2"></i>{{ __('courses.lecture_lesson') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-center text-muted">{{ __('courses.coming_soon') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Presentation Lesson Modal (Placeholder) -->
    <div class="modal fade" id="presentationLessonModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-file-powerpoint me-2"></i>{{ __('courses.presentation_lesson') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-center text-muted">{{ __('courses.coming_soon') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Quiz Modal (Placeholder) -->
    <div class="modal fade" id="quizLessonModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-clipboard-question me-2"></i>{{ __('courses.quiz') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-center text-muted">{{ __('courses.coming_soon') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Lesson Modal -->
    <div class="modal fade" id="deleteLessonModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="deleteLessonForm" method="POST" data-action="{{route('instructor.courses.lessons.destroy', ['lesson' => 'lesson_id'])}}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header py-2 bg-danger text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ __('courses.delete_lesson') }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                            <div>
                                <h6 class="alert-heading mb-2">{{ __('courses.warning') }}</h6>
                                <p class="mb-0" id="deleteLessonText"></p>
                            </div>
                        </div>
                        <p class="text-muted mb-0 small">{{ __('courses.delete_action_irreversible') }}</p>
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>{{ __('courses.cancel') }}
                        </button>
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash me-1"></i>{{ __('courses.confirm_delete') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Video Lesson Modal -->
    <div class="modal fade" id="videoLessonModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="videoLessonModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="videoLessonForm" method="POST" enctype="multipart/form-data" action="{{ route('instructor.courses.lessons.store.video', ['section'=> 'section_id']) }}">
                    @csrf
                    <input type="hidden" name="lesson_type" value="video">
                    <input type="hidden" name="section_id" id="video_section_id">
                    <div class="modal-header py-2">
                        <h1 class="modal-title fs-5" id="videoLessonModalLabel">
                            <i class="fas fa-video me-2"></i>{{ __('courses.add_video_lesson') }}
                        </h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-2">
                            <label for="video_lesson_title" class="input-group-text">{{ __('courses.title') }}</label>
                            <input type="text" class="form-control" id="video_lesson_title" name="title" required>
                        </div>
                        <div class="form-floating mb-2">
                            <textarea name="description" placeholder="{{ __('courses.enter_short_description') }}"
                                id="video_lesson_description" class="form-control" style="height: 100px"></textarea>
                            <label for="video_lesson_description">{{ __('courses.description') }}</label>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">{{ __('courses.video_source') }}</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="video_source" id="video_upload" value="upload" checked>
                                <label class="btn btn-outline-primary" for="video_upload">
                                    <i class="fas fa-upload me-1"></i>{{ __('courses.upload_video') }}
                                </label>

                                <input type="radio" class="btn-check" name="video_source" id="video_url" value="url">
                                <label class="btn btn-outline-primary" for="video_url">
                                    <i class="fas fa-link me-1"></i>{{ __('courses.video_url') }}
                                </label>
                            </div>
                        </div>
                        <div id="video_upload_section" class="mb-2">
                            <label for="video_file" class="form-label">{{ __('courses.select_video_file') }}</label>
                            <input type="file" class="form-control" id="video_file" name="video_file" accept="video/*">
                            <small class="text-muted">{{ __('courses.max_video_size') }}</small>
                        </div>
                        <div id="video_url_section" class="mb-2" style="display: none;">
                            <label for="video_url_input" class="form-label">{{ __('courses.video_url') }}</label>
                            <input type="url" class="form-control" id="video_url_input" name="video_url"
                                placeholder="https://youtube.com/watch?v=... or https://vimeo.com/...">
                            <small class="text-muted">{{ __('courses.supported_platforms') }}: YouTube, Vimeo</small>
                        </div>

                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-sm" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>{{ __('courses.cancel') }}
                        </button>
                        <button type="submit" class="btn btn-sm">
                            <i class="fas fa-save me-1"></i>{{ __('courses.add_lesson') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Edit Section Modal
            const editSectionModal = document.getElementById('editSectionModal');
            if (editSectionModal) {
                editSectionModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const sort_order = button.getAttribute('data-sort_order');
                    const sectionId = button.getAttribute('data-section-id');
                    const sectionTitle = button.getAttribute('data-section-title');
                    const sectionDescription = button.getAttribute('data-section-description');

                    // Update form action
                    const form = editSectionModal.querySelector('form');
                    const action = form.getAttribute('data-action').replace('section_id', sectionId);
                    form.action = action;

                    // Fill form fields
                    form.querySelector('#edit-title').value = sectionTitle || '';
                    form.querySelector('#edit-description').value = sectionDescription || '';
                    form.querySelector('#sort_order').value = sort_order || '';
                });
            }

            // Delete Section Modal
            const deleteSectionModal = document.getElementById('deleteSectionModal');
            if (deleteSectionModal) {
                deleteSectionModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const sectionId = button.getAttribute('data-section-id');
                    const sectionTitle = button.getAttribute('data-section-title');
                    const lessonsCount = parseInt(button.getAttribute('data-section-lessons-count')) || 0;

                    // Update form action
                    const form = deleteSectionModal.querySelector('#deleteSectionForm');
                    const action = form.getAttribute('data-action').replace('section_id', sectionId);
                    form.action = action;

                    // Update modal content
                    document.getElementById('deleteSectionText').textContent =
                        `{{ __('courses.delete_section_warning', ['section' => '']) }}`.replace(':section', sectionTitle);

                    // Show/hide lessons count
                    const lessonsCountDiv = document.getElementById('deleteSectionLessonsCount');
                    if (lessonsCount > 0) {
                        lessonsCountDiv.style.display = 'block';
                        document.getElementById('lessonsCountText').textContent =
                            `{{ __('courses.section_has_lessons', ['count' => '']) }}`.replace(':count', lessonsCount);
                    } else {
                        lessonsCountDiv.style.display = 'none';
                    }
                });
            }

            // Video Lesson Modal
            const videoLessonModal = document.getElementById('videoLessonModal');
            if (videoLessonModal) {
                videoLessonModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const sectionId = button.getAttribute('data-section-id');
                    const form = videoLessonModal.querySelector('#videoLessonForm');
                    const action = form.getAttribute('action').replace('section_id', sectionId);
                    form.action = action;

                    // Set section ID
                    document.getElementById('video_section_id').value = sectionId;
                });

                // Toggle between upload and URL
                const uploadRadio = document.getElementById('video_upload');
                const urlRadio = document.getElementById('video_url');
                const uploadSection = document.getElementById('video_upload_section');
                const urlSection = document.getElementById('video_url_section');

                uploadRadio.addEventListener('change', function() {
                    if (this.checked) {
                        uploadSection.style.display = 'block';
                        urlSection.style.display = 'none';
                        document.getElementById('video_file').required = true;
                        document.getElementById('video_url_input').required = false;
                    }
                });

                urlRadio.addEventListener('change', function() {
                    if (this.checked) {
                        uploadSection.style.display = 'none';
                        urlSection.style.display = 'block';
                        document.getElementById('video_file').required = false;
                        document.getElementById('video_url_input').required = true;
                    }
                });
            }

            // Delete Lesson Modal
            const deleteLessonModal = document.getElementById('deleteLessonModal');
            if (deleteLessonModal) {
                deleteLessonModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const lessonId = button.getAttribute('data-lesson-id');
                    const lessonTitle = button.getAttribute('data-lesson-title');

                    // Update form action
                    const form = deleteLessonModal.querySelector('#deleteLessonForm');
                    const action = form.getAttribute('data-action').replace('lesson_id', lessonId);
                    form.action = action;

                    // Update modal content
                    document.getElementById('deleteLessonText').textContent =
                        `{{ __('courses.delete_lesson_warning') }}`.replace(':lesson', lessonTitle);
                });
            }
        });
    </script>
</div>