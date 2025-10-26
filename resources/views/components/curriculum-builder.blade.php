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
                                @php
                                $isVideo = $lesson->lesson_type === 'video';
                                $headerIcon = $isVideo ? 'video' : 'book';
                                $lessonIcon = $isVideo ? 'video' : 'book-open';
                                $gradientColor = $isVideo
                                ? '#667eea 0%, #764ba2 100%'
                                : '#f093fb 0%, #f5576c 100%';
                                $sourceIcon = $lesson->video_source === 'upload' ? 'upload' : 'link';
                                @endphp
                                <div class="card lesson-card shadow-sm h-100">
                                    <div class="card-header bg-white border-bottom">
                                        <h6 class="mb-0 text-truncate" title="{{ $lesson->title }}">
                                            <i class="fas fa-{{ $headerIcon }} me-2 text-primary"></i>
                                            {{ $lesson->title }}
                                        </h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row g-0">
                                            <!-- Video/Content Preview Section -->
                                            <div class="col-12">
                                                <div class="lesson-preview position-relative" style="padding-top: 56.25%; background: linear-gradient(135deg, {{ $gradientColor }});">
                                                    <div class="position-absolute top-50 start-50 translate-middle text-center text-white">
                                                        <i class="fas fa-{{ $lessonIcon }} fa-4x opacity-75 mb-2"></i>
                                                        <p class="mb-0 fw-bold">{{ ucfirst($lesson->lesson_type) }}</p>
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
                                                            <i class="fas fa-{{ $sourceIcon }} me-1"></i>
                                                            {{ ucfirst($lesson->video_source ?? 'N/A') }}
                                                        </span>
                                                    </div>
                                                    <!-- Assignments and Files Badges -->
                                                    @if($lesson->assignments && $lesson->assignments->count() > 0)
                                                    <div class="mt-2">
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="fas fa-tasks me-1"></i>
                                                            {{ $lesson->assignments->count() }} {{ __('courses.assignments') }}
                                                        </span>
                                                    </div>
                                                    @endif
                                                    @if($lesson->files && $lesson->files->count() > 0)
                                                    <div class="mt-2">
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-download me-1"></i>
                                                            {{ $lesson->files->count() }} {{ __('courses.files') }}
                                                        </span>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white border-top">
                                        <div class="d-flex justify-content-between align-items-center gap-2">
                                            <form action="{{ route('instructor.courses.lessons.destroy', $lesson->id) }}" method="post"
                                                onsubmit="return confirm('Are you sure you want to delete this lesson?')">
                                                @csrf
                                                @method('delete')
                                                <a href="{{ route('instructor.courses.lessons.show', ['course' => $lesson->course_id, 'section' => $lesson->section_id, 'lesson' => $lesson->id]) }}"
                                                    class="btn btn-sm btn-outline-primary flex-fill"
                                                    title="{{ __('courses.view_lesson') }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-secondary flex-fill"
                                                    data-lesson_id="{{ $lesson->id }}"
                                                    onclick="this.previousElementSibling.click()"
                                                    title="{{ __('courses.edit_lesson') }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="submit" class="btn btn-sm btn-outline-danger flex-fill"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteLessonModal"
                                                    data-lesson-id="{{ $lesson->id }}"
                                                    data-lesson-title="{{ $lesson->title }}"
                                                    title="{{ __('courses.delete_lesson') }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
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

                            <!-- Quizzes Section -->
                            @forelse($section->quizzes as $quiz)
                            <div class="col-md-6 col-lg-4">
                                <div class="card quiz-card shadow-sm h-100 border-success">
                                    <div class="card-header bg-success bg-opacity-10 border-bottom border-success">
                                        <h6 class="mb-0 text-truncate text-success" title="{{ $quiz->title }}">
                                            <i class="fas fa-clipboard-question me-2"></i>
                                            {{ $quiz->title }}
                                        </h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row g-0">
                                            <!-- Quiz Preview Section -->
                                            <div class="col-12">
                                                <div class="quiz-preview position-relative" style="padding-top: 56.25%; background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);">
                                                    <div class="position-absolute top-50 start-50 translate-middle text-white text-center">
                                                        <i class="fas fa-clipboard-list fa-3x opacity-75"></i>
                                                        <p class="mt-2 small fw-bold">{{ __('courses.quiz') }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Quiz Details Section -->
                                            <div class="col-12 p-3">
                                                <div class="quiz-meta">
                                                    <p class="text-muted small mb-2">
                                                        {{ $quiz->description ?? __('courses.no_description') }}
                                                    </p>

                                                    <!-- Quiz Stats -->
                                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-question-circle me-1"></i>
                                                            {{ $quiz->questions->count() }} {{ __('courses.questions') }}
                                                        </span>

                                                        <span class="badge bg-primary">
                                                            <i class="fas fa-star me-1"></i>
                                                            {{ $quiz->totalPoints }} {{ __('courses.points') }}
                                                        </span>

                                                        @if($quiz->duration_minutes)
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="far fa-clock me-1"></i>
                                                            {{ $quiz->duration_minutes }} {{ __('courses.min') }}
                                                        </span>
                                                        @endif
                                                    </div>

                                                    <!-- Quiz Type & Pass Percentage -->
                                                    <div class="d-flex flex-wrap gap-2">
                                                        <span class="badge bg-info text-white">
                                                            <i class="fas fa-graduation-cap me-1"></i>
                                                            {{ __('courses.' . $quiz->quiz_type) }}
                                                        </span>

                                                        <span class="badge bg-secondary">
                                                            <i class="fas fa-percent me-1"></i>
                                                            {{ $quiz->pass_percentage }}% {{ __('courses.pass_percentage') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white border-top border-success">
                                        <div class="d-flex justify-content-between align-items-center gap-2">
                                            <form action="{{ route('instructor.courses.quizzes.destroy', $quiz->id) }}" method="post"
                                                onsubmit="return confirm('Are you sure you want to delete this quiz?')">
                                                @csrf
                                                @method('delete')
                                                <a href="{{ route('instructor.courses.quizzes.show', $quiz->id) }}"
                                                    class="btn btn-sm btn-outline-success flex-fill"
                                                    title="{{ __('courses.view_quiz') }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-primary flex-fill"
                                                    data-quiz_id="{{ $quiz->id }}"
                                                    onclick="this.previousElementSibling.click()"
                                                    title="{{ __('courses.edit_quiz') }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="submit" class="btn btn-sm btn-outline-danger flex-fill"

                                                    data-quiz-id="{{ $quiz->id }}"
                                                    data-quiz-title="{{ $quiz->title }}"
                                                    title="{{ __('courses.delete_quiz') }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
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

    <!-- Add Lecture/Article Lesson Modal -->
    <div class="modal fade" id="lectureLessonModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <form id="articleLessonForm" method="POST" enctype="multipart/form-data" action="{{ route('instructor.courses.lessons.store.article', ['section' => 'section_id']) }}">
                    @csrf
                    <input type="hidden" name="lesson_type" value="article">
                    <input type="hidden" name="section_id" id="article_section_id">
                    <div class="modal-header py-2">
                        <h5 class="modal-title">
                            <i class="fas fa-book-reader me-2"></i>{{ __('courses.add_article_lesson') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-3">
                            <label for="article_lesson_title" class="input-group-text">{{ __('courses.title') }}</label>
                            <input type="text" class="form-control" id="article_lesson_title" name="title" required>
                        </div>
                        <div class="mb-3">

                            <label for="article_body" class="form-label">
                                {{ __('courses.article_body') }}
                                <small class="text-muted">({{ __('courses.auto_description_hint') }})</small>
                            </label>
                            <textarea name="article_body" id="article_body" class="form-control"
                                style="min-height: 400px; width: 100%; border: 1px solid #dee2e6; padding: 10px; border-radius: 4px;"></textarea>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">{{ __('courses.poster_source') }}</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="poster_source" id="poster_upload" value="upload" checked>
                                <label class="btn btn-outline-primary" for="poster_upload">
                                    <i class="fas fa-upload me-1"></i>{{ __('courses.upload_poster') }}
                                </label>

                                <input type="radio" class="btn-check" name="poster_source" id="poster_url" value="url">
                                <label class="btn btn-outline-primary" for="poster_url">
                                    <i class="fas fa-link me-1"></i>{{ __('courses.poster_url') }}
                                </label>
                            </div>
                        </div>
                        <div id="poster_upload_section" class="mb-2">
                            <label for="poster_file" class="form-label">{{ __('courses.select_poster_file') }}</label>
                            <input type="file" class="form-control" id="poster_file" name="poster_file" accept="image/*">
                            <small class="text-muted">{{ __('courses.max_poster_size') }}</small>
                        </div>
                        <div id="poster_url_section" class="mb-2" style="display: none;">
                            <label for="poster_url_input" class="form-label">{{ __('courses.poster_url') }}</label>
                            <input type="url" class="form-control" id="poster_url_input" name="poster_url"
                                placeholder="https://example.com/image.jpg">
                            <small class="text-muted">{{ __('courses.poster_url_hint') }}</small>
                        </div>

                        <hr class="my-3">

                        <!-- Downloadable Files Section -->
                        <div class="mb-3">
                            <h6 class="mb-2">
                                <i class="fas fa-download me-2 text-info"></i>{{ __('courses.downloadable_files') }}
                                <small class="text-muted">({{ __('courses.optional') }})</small>
                            </h6>
                            <input type="file" class="form-control" id="article_lesson_files" name="lesson_files[]" multiple
                                accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar,.txt,.csv">
                            <small class="text-muted d-block mt-1">
                                <i class="fas fa-info-circle me-1"></i>{{ __('courses.supported_file_types') }}:
                                PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, ZIP, RAR, TXT, CSV
                            </small>
                            <small class="text-muted d-block">
                                <i class="fas fa-exclamation-triangle me-1"></i>{{ __('courses.max_file_size_per_file') }}: 10MB
                            </small>
                            <!-- Files Preview -->
                            <div id="article_files_preview" class="mt-2" style="display: none;">
                                <div class="list-group" id="article_files_list"></div>
                            </div>
                        </div>

                        <hr class="my-3">

                        <!-- Assignments Section -->
                        <div class="mb-3">
                            <h6 class="mb-2">
                                <i class="fas fa-tasks me-2 text-warning"></i>{{ __('courses.assignments') }}
                                <small class="text-muted">({{ __('courses.optional') }})</small>
                            </h6>
                            <div id="article_assignments_container">
                                <!-- Assignment items will be added here dynamically -->
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="article_add_assignment_btn">
                                <i class="fas fa-plus me-1"></i>{{ __('courses.add_assignment') }}
                            </button>
                        </div>

                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>{{ __('courses.cancel') }}
                        </button>
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-save me-1"></i>{{ __('courses.add_lesson') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Quiz Modal -->
<div class="modal fade" id="quizLessonModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <form id="quizForm" method="POST" action="{{ route('instructor.courses.quizzes.store', ['section' => 'section_id']) }}">
                @csrf
                <input type="hidden" name="section_id" id="quiz_section_id">

                <div class="modal-header bg-gradient-primary text-white py-2">
                    <h5 class="modal-title">
                        <i class="fas fa-clipboard-question me-2"></i>{{ __('courses.create_quiz') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <!-- Quiz Basic Information -->
                    <div class="card mb-3 border-primary">
                        <div class="card-header bg-primary bg-opacity-10">
                            <h6 class="mb-0 text-primary">
                                <i class="fas fa-info-circle me-2"></i>{{ __('courses.quiz_information') }}
                            </h6>
                        </div>
                        <div class="card-body">
                            <!-- Quiz Title -->
                            <div class="mb-3">
                                <label for="quiz_title" class="form-label">{{ __('courses.quiz_title') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="quiz_title" name="title" required
                                    placeholder="{{ __('courses.quiz_title_placeholder') }}">
                            </div>

                            <!-- Quiz Description -->
                            <div class="mb-3">
                                <label for="quiz_description" class="form-label">{{ __('courses.description') }}</label>
                                <textarea class="form-control" id="quiz_description" name="description" rows="3"
                                    placeholder="{{ __('courses.quiz_description_placeholder') }}"></textarea>
                            </div>

                            <div class="row">
                                <!-- Quiz Type -->
                                <div class="col-md-4 mb-3">
                                    <label for="quiz_type" class="form-label">{{ __('courses.quiz_type') }} <span class="text-danger">*</span></label>
                                    <select class="form-select" id="quiz_type" name="quiz_type" required>
                                        <option value="practice">{{ __('courses.practice_quiz') }}</option>
                                        <option value="graded">{{ __('courses.graded_quiz') }}</option>
                                        <option value="final">{{ __('courses.final_exam') }}</option>
                                    </select>
                                </div>

                                <!-- Duration -->
                                <div class="col-md-4 mb-3">
                                    <label for="duration_minutes" class="form-label">{{ __('courses.duration_minutes') }}</label>
                                    <input type="number" class="form-control" id="duration_minutes" name="duration_minutes"
                                        min="5" max="180" placeholder="30">
                                    <small class="text-muted">{{ __('courses.leave_empty_no_limit') }}</small>
                                </div>

                                <!-- Pass Percentage -->
                                <div class="col-md-4 mb-3">
                                    <label for="pass_percentage" class="form-label">{{ __('courses.pass_percentage') }}</label>
                                    <input type="number" class="form-control" id="pass_percentage" name="pass_percentage"
                                        min="0" max="100" value="70" placeholder="70">
                                    <small class="text-muted">%</small>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Max Attempts -->
                                <div class="col-md-4 mb-3">
                                    <label for="max_attempts" class="form-label">{{ __('courses.max_attempts') }}</label>
                                    <input type="number" class="form-control" id="max_attempts" name="max_attempts"
                                        min="1" max="10" value="3">
                                    <small class="text-muted">{{ __('courses.attempts_allowed') }}</small>
                                </div>

                                <!-- Randomize Questions -->
                                <div class="col-md-4 mb-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" id="randomize_questions"
                                            name="randomize_questions" value="1">
                                        <label class="form-check-label" for="randomize_questions">
                                            {{ __('courses.randomize_questions') }}
                                        </label>
                                    </div>
                                </div>

                                <!-- Show Results -->
                                <div class="col-md-4 mb-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" id="show_results"
                                            name="show_results" value="1" checked>
                                        <label class="form-check-label" for="show_results">
                                            {{ __('courses.show_results_after_completion') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Questions Section -->
                    <div class="card border-success">
                        <div class="card-header bg-success bg-opacity-10">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 text-success">
                                    <i class="fas fa-question-circle me-2"></i>{{ __('courses.quiz_questions') }}
                                </h6>
                                <span class="badge bg-success" id="questions_count">0 {{ __('courses.questions') }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="questions_container">
                                <!-- Questions will be added here dynamically -->
                                <div class="text-center text-muted py-4" id="no_questions_message">
                                    <i class="fas fa-question-circle fa-3x mb-3 opacity-25"></i>
                                    <p>{{ __('courses.no_questions_yet') }}</p>
                                </div>
                            </div>

                            <!-- Add Question Buttons -->
                            <div class="d-flex gap-2 flex-wrap mt-3">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="add_multiple_choice_btn">
                                    <i class="fas fa-list-ul me-1"></i>{{ __('courses.multiple_choice') }}
                                </button>
                                <button type="button" class="btn btn-outline-info btn-sm" id="add_true_false_btn">
                                    <i class="fas fa-check-double me-1"></i>{{ __('courses.true_false') }}
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" id="add_fill_blank_btn">
                                    <i class="fas fa-edit me-1"></i>{{ __('courses.fill_in_blank') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>{{ __('courses.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="fas fa-save me-1"></i>{{ __('courses.create_quiz') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Upload Progress Modal -->
<div class="modal fade" id="uploadProgressModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title">
                    <i class="fas fa-cloud-upload-alt me-2"></i>Uploading Files...
                </h5>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <div class="progress mb-3" style="height: 25px;">
                    <div id="uploadProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                        role="progressbar" style="width: 0%">
                        <span id="uploadProgressText">0%</span>
                    </div>
                </div>

                <p class="text-center mb-2">
                    <span id="uploadStatusText">Preparing upload...</span>
                </p>

                <p class="text-center text-muted mb-0">
                    <small id="uploadDetailsText">Please wait while we process your files.</small>
                </p>
            </div>
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

                    <hr class="my-3">

                    <!-- Downloadable Files Section -->
                    <div class="mb-3">
                        <h6 class="mb-2">
                            <i class="fas fa-download me-2 text-info"></i>{{ __('courses.downloadable_files') }}
                            <small class="text-muted">({{ __('courses.optional') }})</small>
                        </h6>
                        <input type="file" class="form-control" id="video_lesson_files" name="lesson_files[]" multiple
                            accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar,.txt,.csv">
                        <small class="text-muted d-block mt-1">
                            <i class="fas fa-info-circle me-1"></i>{{ __('courses.supported_file_types') }}:
                            PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, ZIP, RAR, TXT, CSV
                        </small>
                        <small class="text-muted d-block">
                            <i class="fas fa-exclamation-triangle me-1"></i>{{ __('courses.max_file_size_per_file') }}: 10MB
                        </small>
                        <!-- Files Preview -->
                        <div id="video_files_preview" class="mt-2" style="display: none;">
                            <div class="list-group" id="video_files_list"></div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- Assignments Section -->
                    <div class="mb-3">
                        <h6 class="mb-2">
                            <i class="fas fa-tasks me-2 text-warning"></i>{{ __('courses.assignments') }}
                            <small class="text-muted">({{ __('courses.optional') }})</small>
                        </h6>
                        <div id="video_assignments_container">
                            <!-- Assignment items will be added here dynamically -->
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="video_add_assignment_btn">
                            <i class="fas fa-plus me-1"></i>{{ __('courses.add_assignment') }}
                        </button>
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

        // CRITICAL: Fix Bootstrap Modal focus trap for TinyMCE
        document.addEventListener('focusin', function(e) {
            if (e.target.closest('.tox-tinymce-aux, .moxman-window, .tam-assetmanager-root') !== null) {
                e.stopImmediatePropagation();
            }
        });

        // Article Lesson Modal
        const articleLessonModal = document.getElementById('lectureLessonModal');
        if (articleLessonModal) {
            articleLessonModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const sectionId = button.getAttribute('data-section-id');
                const form = articleLessonModal.querySelector('#articleLessonForm');
                const action = form.getAttribute('action').replace('section_id', sectionId);
                form.action = action;

                // Set section ID
                document.getElementById('article_section_id').value = sectionId;

                // Initialize TinyMCE - FIX for Bootstrap Modal
                if (typeof tinymce !== 'undefined') {
                    // Remove existing instance if any
                    tinymce.remove('#article_body');

                    // Small delay to ensure modal is fully visible
                    setTimeout(function() {
                        tinymce.init({
                            selector: '#article_body',
                            height: 400,
                            menubar: false,
                            promotion: false,
                            branding: false,

                            // CRITICAL: Fix for Bootstrap 5 Modal
                            target: document.getElementById('article_body'),

                            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
                            toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | image link | code | help',
                            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px; padding: 10px; }',

                            // Fix focus issue in Bootstrap modal
                            init_instance_callback: function(editor) {
                                editor.on('focus', function() {
                                    document.getElementById('lectureLessonModal').setAttribute('data-mce-focused', 'true');
                                });
                            },

                            images_upload_handler: function(blobInfo, progress) {
                                return new Promise(function(resolve, reject) {
                                    var reader = new FileReader();
                                    reader.onload = function(e) {
                                        resolve(e.target.result);
                                    };
                                    reader.onerror = function() {
                                        reject('Error reading image file');
                                    };
                                    reader.readAsDataURL(blobInfo.blob());
                                });
                            },

                            setup: function(editor) {
                                editor.on('init', function() {
                                    console.log('✅ TinyMCE initialized successfully!');
                                    // Force focus to work in modal
                                    editor.focus();
                                });

                                editor.on('OpenWindow', function(e) {
                                    // Allow TinyMCE dialogs in Bootstrap modal
                                    document.getElementById('lectureLessonModal').removeAttribute('tabindex');
                                });
                            }
                        });
                    }, 300);
                } else {
                    console.error('❌ TinyMCE library not loaded! Check the CDN link.');
                    alert('Rich text editor failed to load. Please refresh the page.');
                }
            });

            // Clean up TinyMCE when modal closes
            articleLessonModal.addEventListener('hidden.bs.modal', function() {
                if (typeof tinymce !== 'undefined') {
                    tinymce.remove('#article_body');
                }
            });

            // Toggle between poster upload and URL
            const posterUploadRadio = document.getElementById('poster_upload');
            const posterUrlRadio = document.getElementById('poster_url');
            const posterUploadSection = document.getElementById('poster_upload_section');
            const posterUrlSection = document.getElementById('poster_url_section');

            posterUploadRadio.addEventListener('change', function() {
                if (this.checked) {
                    posterUploadSection.style.display = 'block';
                    posterUrlSection.style.display = 'none';
                    document.getElementById('poster_file').required = true;
                    document.getElementById('poster_url_input').required = false;
                }
            });

            posterUrlRadio.addEventListener('change', function() {
                if (this.checked) {
                    posterUploadSection.style.display = 'none';
                    posterUrlSection.style.display = 'block';
                    document.getElementById('poster_file').required = false;
                    document.getElementById('poster_url_input').required = true;
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

                // Update form action - use correct route
                const form = deleteLessonModal.querySelector('#deleteLessonForm');
                form.action = `/instructor/courses/lessons/${lessonId}`;

                // Update modal content
                document.getElementById('deleteLessonText').textContent =
                    `{{ __('courses.delete_lesson_warning') }}`.replace(':lesson', lessonTitle);
            });
        }

        // Delete Quiz Modal
        const deleteQuizModal = document.getElementById('deleteQuizModal');
        if (deleteQuizModal) {
            deleteQuizModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const quizId = button.getAttribute('data-quiz-id');
                const quizTitle = button.getAttribute('data-quiz-title');

                // Update form action
                const form = deleteQuizModal.querySelector('#deleteQuizForm');
                const action = form.getAttribute('data-action').replace('quiz_id', quizId);
                form.action = action;

                // Update modal content
                document.getElementById('deleteQuizText').textContent =
                    `{{ __('courses.delete_quiz_warning') }}`.replace(':quiz', quizTitle);
            });
        }

        // Helper function to get file icon
        function getFileIcon(filename) {
            const ext = filename.split('.').pop().toLowerCase();
            const iconMap = {
                'pdf': 'fas fa-file-pdf text-danger',
                'doc': 'fas fa-file-word text-primary',
                'docx': 'fas fa-file-word text-primary',
                'ppt': 'fas fa-file-powerpoint text-warning',
                'pptx': 'fas fa-file-powerpoint text-warning',
                'xls': 'fas fa-file-excel text-success',
                'xlsx': 'fas fa-file-excel text-success',
                'zip': 'fas fa-file-archive text-secondary',
                'rar': 'fas fa-file-archive text-secondary',
                'txt': 'fas fa-file-alt text-muted',
                'csv': 'fas fa-file-csv text-success'
            };
            return iconMap[ext] || 'fas fa-file text-muted';
        }

        // Files preview for Video Lesson
        const videoFilesInput = document.getElementById('video_lesson_files');
        if (videoFilesInput) {
            videoFilesInput.addEventListener('change', function(e) {
                const files = e.target.files;
                const preview = document.getElementById('video_files_preview');
                const filesList = document.getElementById('video_files_list');

                if (files.length > 0) {
                    preview.style.display = 'block';
                    filesList.innerHTML = '';

                    Array.from(files).forEach((file, index) => {
                        const fileSize = (file.size / (1024 * 1024)).toFixed(2);
                        const fileIcon = getFileIcon(file.name);

                        const fileItem = document.createElement('div');
                        fileItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                        fileItem.innerHTML = `
                            <div>
                                <i class="${fileIcon} me-2"></i>
                                <strong>${file.name}</strong>
                                <small class="text-muted ms-2">(${fileSize} MB)</small>
                            </div>
                            <span class="badge bg-info rounded-pill">${index + 1}</span>
                        `;
                        filesList.appendChild(fileItem);
                    });
                } else {
                    preview.style.display = 'none';
                }
            });
        }

        // Files preview for Article Lesson
        const articleFilesInput = document.getElementById('article_lesson_files');
        if (articleFilesInput) {
            articleFilesInput.addEventListener('change', function(e) {
                const files = e.target.files;
                const preview = document.getElementById('article_files_preview');
                const filesList = document.getElementById('article_files_list');

                if (files.length > 0) {
                    preview.style.display = 'block';
                    filesList.innerHTML = '';

                    Array.from(files).forEach((file, index) => {
                        const fileSize = (file.size / (1024 * 1024)).toFixed(2);
                        const fileIcon = getFileIcon(file.name);

                        const fileItem = document.createElement('div');
                        fileItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                        fileItem.innerHTML = `
                            <div>
                                <i class="${fileIcon} me-2"></i>
                                <strong>${file.name}</strong>
                                <small class="text-muted ms-2">(${fileSize} MB)</small>
                            </div>
                            <span class="badge bg-info rounded-pill">${index + 1}</span>
                        `;
                        filesList.appendChild(fileItem);
                    });
                } else {
                    preview.style.display = 'none';
                }
            });
        }

        // Assignment counter for unique IDs
        let videoAssignmentCounter = 0;
        let articleAssignmentCounter = 0;

        // Add assignment function for Video Lesson
        const videoAddAssignmentBtn = document.getElementById('video_add_assignment_btn');
        if (videoAddAssignmentBtn) {
            videoAddAssignmentBtn.addEventListener('click', function() {
                videoAssignmentCounter++;
                const container = document.getElementById('video_assignments_container');
                const assignmentHtml = `
                    <div class="card mb-2 assignment-item" id="video_assignment_${videoAssignmentCounter}">
                        <div class="card-body p-2">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong class="text-primary">{{ __('courses.assignment') }} ${videoAssignmentCounter}</strong>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="document.getElementById('video_assignment_${videoAssignmentCounter}').remove()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <input type="text" class="form-control form-control-sm mb-1" name="assignments[${videoAssignmentCounter}][title]" placeholder="{{ __('courses.assignment_title') }}" required>
                            <textarea class="form-control form-control-sm mb-1" name="assignments[${videoAssignmentCounter}][description]" rows="2" placeholder="{{ __('courses.assignment_description') }}"></textarea>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="number" class="form-control form-control-sm" name="assignments[${videoAssignmentCounter}][due_days]" placeholder="{{ __('courses.due_days') }}" min="1">
                                </div>
                                <div class="col-md-6">
                                    <input type="number" class="form-control form-control-sm" name="assignments[${videoAssignmentCounter}][max_score]" placeholder="{{ __('courses.max_score') }}" value="100" min="1">
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', assignmentHtml);
            });
        }

        // Add assignment function for Article Lesson
        const articleAddAssignmentBtn = document.getElementById('article_add_assignment_btn');
        if (articleAddAssignmentBtn) {
            articleAddAssignmentBtn.addEventListener('click', function() {
                articleAssignmentCounter++;
                const container = document.getElementById('article_assignments_container');
                const assignmentHtml = `
                    <div class="card mb-2 assignment-item" id="article_assignment_${articleAssignmentCounter}">
                        <div class="card-body p-2">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong class="text-primary">{{ __('courses.assignment') }} ${articleAssignmentCounter}</strong>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="document.getElementById('article_assignment_${articleAssignmentCounter}').remove()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <input type="text" class="form-control form-control-sm mb-1" name="assignments[${articleAssignmentCounter}][title]" placeholder="{{ __('courses.assignment_title') }}" required>
                            <textarea class="form-control form-control-sm mb-1" name="assignments[${articleAssignmentCounter}][description]" rows="2" placeholder="{{ __('courses.assignment_description') }}"></textarea>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="number" class="form-control form-control-sm" name="assignments[${articleAssignmentCounter}][due_days]" placeholder="{{ __('courses.due_days') }}" min="1">
                                </div>
                                <div class="col-md-6">
                                    <input type="number" class="form-control form-control-sm" name="assignments[${articleAssignmentCounter}][max_score]" placeholder="{{ __('courses.max_score') }}" value="100" min="1">
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', assignmentHtml);
            });
        }

        // ==========================================
        // Quiz Modal JavaScript
        // ==========================================

        let questionCounter = 0;

        // Initialize Quiz Modal
        const quizModal = document.getElementById('quizLessonModal');
        if (quizModal) {
            quizModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const sectionId = button.getAttribute('data-section-id');
                const form = quizModal.querySelector('#quizForm');
                const action = form.getAttribute('action').replace('section_id', sectionId);
                form.action = action;

                document.getElementById('quiz_section_id').value = sectionId;
            });

            // Reset form when modal closes
            quizModal.addEventListener('hidden.bs.modal', function() {
                document.getElementById('quizForm').reset();
                document.getElementById('questions_container').innerHTML = '<div class="text-center text-muted py-4" id="no_questions_message"><i class="fas fa-question-circle fa-3x mb-3 opacity-25"></i><p>{{ __('
                courses.no_questions_yet ') }}</p></div>';
                questionCounter = 0;
                updateQuestionsCount();
            });
        }

        // Add Multiple Choice Question
        document.getElementById('add_multiple_choice_btn')?.addEventListener('click', function() {
            addQuestion('multiple_choice');
        });

        // Add True/False Question
        document.getElementById('add_true_false_btn')?.addEventListener('click', function() {
            addQuestion('true_false');
        });

        // Add Fill in Blank Question
        document.getElementById('add_fill_blank_btn')?.addEventListener('click', function() {
            addQuestion('fill_blank');
        });

        function addQuestion(type) {
            questionCounter++;
            const container = document.getElementById('questions_container');
            const noQuestionsMsg = document.getElementById('no_questions_message');
            if (noQuestionsMsg) noQuestionsMsg.remove();

            let questionHtml = '';

            if (type === 'multiple_choice') {
                questionHtml = createMultipleChoiceQuestion(questionCounter);
            } else if (type === 'true_false') {
                questionHtml = createTrueFalseQuestion(questionCounter);
            } else if (type === 'fill_blank') {
                questionHtml = createFillBlankQuestion(questionCounter);
            }

            container.insertAdjacentHTML('beforeend', questionHtml);
            updateQuestionsCount();
        }

        function createMultipleChoiceQuestion(id) {
            return `
                <div class="card mb-3 question-card" id="question_${id}">
                    <div class="card-header bg-primary bg-opacity-10">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong class="text-primary">
                                <i class="fas fa-list-ul me-2"></i>{{ __('courses.question') }} ${id} - {{ __('courses.multiple_choice') }}
                            </strong>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeQuestion(${id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="questions[${id}][type]" value="multiple_choice">
                        
                        <!-- Question Text -->
                        <div class="mb-3">
                            <label class="form-label">{{ __('courses.question_text') }} <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="questions[${id}][question_text]" rows="2" required></textarea>
                        </div>

                        <!-- Points -->
                        <div class="mb-3">
                            <label class="form-label">{{ __('courses.points') }}</label>
                            <input type="number" class="form-control" name="questions[${id}][points]" value="1" min="1" style="width: 100px;">
                        </div>

                        <!-- Answers -->
                        <label class="form-label">{{ __('courses.answer_options') }}</label>
                        <div id="answers_${id}">
                            ${createAnswerOption(id, 1, true)}
                            ${createAnswerOption(id, 2, false)}
                            ${createAnswerOption(id, 3, false)}
                            ${createAnswerOption(id, 4, false)}
                        </div>
                        
                        <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="addAnswer(${id})">
                            <i class="fas fa-plus me-1"></i>{{ __('courses.add_answer') }}
                        </button>
                    </div>
                </div>
            `;
        }

        function createTrueFalseQuestion(id) {
            return `
                <div class="card mb-3 question-card" id="question_${id}">
                    <div class="card-header bg-info bg-opacity-10">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong class="text-info">
                                <i class="fas fa-check-double me-2"></i>{{ __('courses.question') }} ${id} - {{ __('courses.true_false') }}
                            </strong>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeQuestion(${id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="questions[${id}][type]" value="true_false">
                        
                        <!-- Question Text -->
                        <div class="mb-3">
                            <label class="form-label">{{ __('courses.question_text') }} <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="questions[${id}][question_text]" rows="2" required></textarea>
                        </div>

                        <!-- Points -->
                        <div class="mb-3">
                            <label class="form-label">{{ __('courses.points') }}</label>
                            <input type="number" class="form-control" name="questions[${id}][points]" value="1" min="1" style="width: 100px;">
                        </div>

                        <!-- Correct Answer -->
                        <div class="mb-3">
                            <label class="form-label">{{ __('courses.correct_answer') }} <span class="text-danger">*</span></label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="questions[${id}][correct_answer]" value="true" id="q${id}_true" required>
                                <label class="form-check-label" for="q${id}_true">
                                    {{ __('courses.true') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="questions[${id}][correct_answer]" value="false" id="q${id}_false">
                                <label class="form-check-label" for="q${id}_false">
                                    {{ __('courses.false') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function createFillBlankQuestion(id) {
            return `
                <div class="card mb-3 question-card" id="question_${id}">
                    <div class="card-header bg-warning bg-opacity-10">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong class="text-warning">
                                <i class="fas fa-edit me-2"></i>{{ __('courses.question') }} ${id} - {{ __('courses.fill_in_blank') }}
                            </strong>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeQuestion(${id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="questions[${id}][type]" value="fill_blank">
                        
                        <!-- Question Text -->
                        <div class="mb-3">
                            <label class="form-label">{{ __('courses.question_text') }} <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="questions[${id}][question_text]" rows="2" required></textarea>
                            <small class="text-muted">{{ __('courses.use_blank_placeholder') }}: _____</small>
                        </div>

                        <!-- Points -->
                        <div class="mb-3">
                            <label class="form-label">{{ __('courses.points') }}</label>
                            <input type="number" class="form-control" name="questions[${id}][points]" value="1" min="1" style="width: 100px;">
                        </div>

                        <!-- Correct Answer -->
                        <div class="mb-3">
                            <label class="form-label">{{ __('courses.correct_answer') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="questions[${id}][correct_answer]" required 
                                placeholder="{{ __('courses.enter_correct_answer') }}">
                            <small class="text-muted">{{ __('courses.case_insensitive') }}</small>
                        </div>
                    </div>
                </div>
            `;
        }

        function createAnswerOption(questionId, answerId, isCorrect = false) {
            return `
                <div class="input-group mb-2 answer-option" id="q${questionId}_answer_${answerId}">
                    <div class="input-group-text">
                        <input class="form-check-input mt-0" type="radio" 
                            name="questions[${questionId}][correct_answer]" 
                            value="${answerId}" ${isCorrect ? 'checked' : ''} required>
                    </div>
                    <input type="text" class="form-control" 
                        name="questions[${questionId}][answers][${answerId}]" 
                        placeholder="{{ __('courses.answer_option') }} ${answerId}" required>
                    <button type="button" class="btn btn-outline-danger btn-sm" 
                        onclick="removeAnswer(${questionId}, ${answerId})" ${answerId <= 2 ? 'disabled' : ''}>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
        }

        function addAnswer(questionId) {
            const answersContainer = document.getElementById(`answers_${questionId}`);
            const answerCount = answersContainer.querySelectorAll('.answer-option').length + 1;
            answersContainer.insertAdjacentHTML('beforeend', createAnswerOption(questionId, answerCount, false));
        }

        function removeAnswer(questionId, answerId) {
            const answerElement = document.getElementById(`q${questionId}_answer_${answerId}`);
            if (answerElement) answerElement.remove();
        }

        function removeQuestion(id) {
            if (confirm('{{ __('
                    courses.confirm_delete_question ') }}')) {
                const questionElement = document.getElementById(`question_${id}`);
                if (questionElement) questionElement.remove();
                updateQuestionsCount();

                // Show "no questions" message if no questions left
                const container = document.getElementById('questions_container');
                if (!container.querySelector('.question-card')) {
                    container.innerHTML = '<div class="text-center text-muted py-4" id="no_questions_message"><i class="fas fa-question-circle fa-3x mb-3 opacity-25"></i><p>{{ __('
                    courses.no_questions_yet ') }}</p></div>';
                }
            }
        }

        function updateQuestionsCount() {
            const count = document.querySelectorAll('.question-card').length;
            document.getElementById('questions_count').textContent = `${count} {{ __('courses.questions') }}`;
        }

        // Edit Lesson Function
        function editLesson(lessonId) {
            // TODO: Implement edit lesson modal or redirect to edit page
            alert('Edit Lesson feature coming soon! Lesson ID: ' + lessonId);
            // For now, you can redirect to edit page:
            // window.location.href = `/instructor/courses/lessons/${lessonId}/edit`;
        }

        // Edit Quiz Function  
        function editQuiz(quizId) {
            // TODO: Implement edit quiz modal or redirect to edit page
            alert('Edit Quiz feature coming soon! Quiz ID: ' + quizId);
            // For now, you can redirect to edit page:
            // window.location.href = `/instructor/courses/quizzes/${quizId}/edit`;
        }

        // Note: Progress tracking removed - using direct upload now
    });
</script>
</div>