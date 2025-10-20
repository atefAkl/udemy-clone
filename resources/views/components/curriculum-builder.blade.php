@props([
'courseId' => null,
'sections' => []
])

<!-- CSS -->
<link rel="stylesheet" href="{{ asset('css/curriculum-builder.css') }}">

<div class="curriculum-builder">
    <div class="curriculum-header d-flex justify-content-between align-items-center mb-4">
        <h4><i class="fas fa-book me-2"></i>{{ __('courses.build_curriculum') }}</h4>
        <button type="button" class="btn btn-primary" id="addSectionBtn">
            <i class="fas fa-plus"></i> {{ __('courses.add_section') }}
        </button>
    </div>

    <div class="curriculum-container" id="curriculumContainer" data-course-id="{{ $courseId }}">
        <div class="empty-state" id="emptyState" style="display: none;">
            <i class="fas fa-list-alt fa-3x mb-3"></i>
            <p class="h5">{{ __('courses.no_sections_yet') }}</p>
            <p><small class="text-muted">{{ __('courses.click_add_section_to_start') }}</small></p>
        </div>

        @if(count($sections) > 0)
        @foreach($sections as $section)
        <div class="section-item" data-section-id="{{ $section->id }}" data-status="saved">
            <div class="section-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center flex-grow-1">
                        <span class="drag-handle me-2" title="اسحب لإعادة الترتيب">≡</span>
                        <span class="section-number badge bg-primary me-2">{{ $loop->iteration }}</span>
                        <div class="flex-grow-1">
                            <input type="text" class="section-title-input form-control form-control-sm mb-1"
                                value="{{ $section->title }}" placeholder="{{ __('courses.section_title') }}" disabled>
                            <textarea class="section-description-input form-control form-control-sm" 
                                placeholder="وصف مختصر للقسم (اختياري)" 
                                maxlength="255" 
                                rows="1"
                                style="resize: none; font-size: 0.875rem;"
                                disabled>{{ $section->description ?? '' }}</textarea>
                            <small class="text-muted d-block">
                                <span class="char-counter">{{ strlen($section->description ?? '') }}</span>/255 حرف
                            </small>
                        </div>
                        <span class="badge bg-success ms-2 saved-badge">
                            <i class="fas fa-check"></i> {{ __('courses.saved') }}
                        </span>
                    </div>
                    <div class="section-actions">
                        <button type="button" class="btn btn-sm btn-success save-section-btn d-none">
                            <i class="fas fa-save"></i> {{ __('courses.save') }}
                        </button>
                        <button type="button" class="btn btn-sm btn-info edit-section-btn">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger delete-section-btn">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="section-content">
                <div class="lessons-container">
                    @foreach($section->lessons as $lesson)
                    <div class="lesson-item" data-lesson-id="{{ $lesson->id }}" data-status="saved" data-type="{{ $lesson->content_type }}">
                        <div class="lesson-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center flex-grow-1">
                                    <span class="drag-handle me-2">≡</span>
                                    <span class="lesson-number badge bg-secondary me-2">{{ $loop->iteration }}</span>
                                    <input type="text" class="lesson-title-input form-control form-control-sm"
                                        value="{{ $lesson->title }}" disabled style="max-width: 250px;">
                                    <select class="lesson-type-select form-select form-select-sm ms-2" disabled style="max-width: 120px;">
                                        <option value="video" {{ $lesson->content_type == 'video' ? 'selected' : '' }}>{{ __('courses.video') }}</option>
                                        <option value="article" {{ $lesson->content_type == 'article' ? 'selected' : '' }}>{{ __('courses.article') }}</option>
                                        <option value="file" {{ $lesson->content_type == 'download' ? 'selected' : '' }}>{{ __('courses.files') }}</option>
                                    </select>
                                    <span class="badge bg-success ms-2"><i class="fas fa-check"></i></span>
                                </div>
                                <div class="lesson-actions">
                                    <button type="button" class="btn btn-sm btn-success save-lesson-btn d-none">
                                        <i class="fas fa-save"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-info edit-lesson-btn">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-lesson-btn">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary add-lesson-btn mt-3">
                    <i class="fas fa-plus"></i> {{ __('courses.add_lesson') }}
                </button>
            </div>
        </div>
        @endforeach
        @endif
    </div>
</div>

<!-- Required Libraries -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<!-- TinyMCE: سيتم تحميله عند الحاجة فقط في نماذج المقالات -->
<!-- <script src="https://cdn.tiny.cloud/1/YOUR_API_KEY/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script> -->

<!-- Curriculum Builder Scripts -->
<script src="{{ asset('js/curriculum-builder.js') }}"></script>
<script src="{{ asset('js/curriculum-builder-lessons.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const courseId = document.getElementById('curriculumContainer').dataset.courseId;
        if (courseId) {
            window.curriculumBuilder = new CurriculumBuilder(courseId);
        }
    });
</script>