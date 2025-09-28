@props([
'courseId' => null,
'sections' => []
])

<div class="curriculum-builder">
    <div class="curriculum-header d-flex justify-content-between align-items-center mb-4">
        <h4>{{ __('courses.build_curriculum') }}</h4>
        <button type="button" class="btn btn-primary" id="addSectionBtn">
            <i class="fas fa-plus"></i> {{ __('courses.add_section') }}
        </button>
    </div>

    <div class="curriculum-container" id="curriculumContainer">
        <div class="empty-state" id="emptyState" style="display: none;">
            <i class="fas fa-list-alt"></i>
            <p>{{ __('courses.no_sections_yet') }}</p>
            <p><small>{{ __('courses.click_add_section_to_start') }}</small></p>
        </div>

        @if(count($sections) > 0)
        @foreach($sections as $section)
        <div class="section-item" data-section-id="{{ $section['id'] ?? $loop->index }}">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <span class="drag-handle me-2">≡</span>
                    <span class="section-number">{{ $loop->index + 1 }}.</span>
                    <input type="text" class="section-title-input form-control form-control-sm d-inline-block w-auto ms-2"
                        value="{{ $section['title'] ?? '' }}" placeholder="{{ __('courses.section_title') }}">
                </div>
                <div class="section-actions">
                    <button type="button" class="btn btn-sm btn-outline-secondary add-lesson-btn">
                        <i class="fas fa-plus"></i> {{ __('courses.add_lesson') }}
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger delete-section-btn">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

            <div class="section-content">
                <div class="lessons-container" data-section-id="{{ $section['id'] ?? $loop->index }}">
                    @if(isset($section['lessons']) && count($section['lessons']) > 0)
                    @foreach($section['lessons'] as $lesson)
                    <div class="lesson-item" data-lesson-id="{{ $lesson['id'] ?? $loop->index }}">
                        <div class="d-flex align-items-center">
                            <span class="drag-handle me-2">≡</span>
                            <span class="lesson-number">{{ $loop->index + 1 }}.</span>
                            <input type="text" class="lesson-title-input form-control form-control-sm d-inline-block w-auto ms-2"
                                value="{{ $lesson['title'] ?? '' }}" placeholder="{{ __('courses.lesson_title') }}">
                            <select class="lesson-type-select form-select form-select-sm ms-2" style="width: auto;">
                                <option value="video" {{ ($lesson['type'] ?? '') == 'video' ? 'selected' : '' }}>Video</option>
                                <option value="article" {{ ($lesson['type'] ?? '') == 'article' ? 'selected' : '' }}>Article</option>
                                <option value="presentation" {{ ($lesson['type'] ?? '') == 'presentation' ? 'selected' : '' }}>Presentation</option>
                                <option value="mixed" {{ ($lesson['type'] ?? '') == 'mixed' ? 'selected' : '' }}>Mixed</option>
                            </select>
                        </div>
                        <div class="lesson-actions">
                            <button type="button" class="btn btn-sm btn-outline-info upload-files-btn">
                                <i class="fas fa-upload"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-lesson-btn">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>

    <!-- Hidden input to store curriculum data -->
    <input type="hidden" name="curriculum_data" id="curriculumData">
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const curriculumContainer = document.getElementById('curriculumContainer');
        const addSectionBtn = document.getElementById('addSectionBtn');
        const curriculumDataInput = document.getElementById('curriculumData');
        const emptyState = document.getElementById('emptyState');

        const toggleEmptyState = () => {
            const sectionCount = curriculumContainer.querySelectorAll('.section-item').length;
            emptyState.style.display = sectionCount === 0 ? 'block' : 'none';
        };

        const updateNumbers = () => {
            const sections = curriculumContainer.querySelectorAll('.section-item');
            sections.forEach((section, sectionIndex) => {
                section.querySelector('.section-number').textContent = `${sectionIndex + 1}.`;
                const lessons = section.querySelectorAll('.lesson-item');
                lessons.forEach((lesson, lessonIndex) => {
                    lesson.querySelector('.lesson-number').textContent = `${lessonIndex + 1}.`;
                });
            });
        };

        const serializeCurriculum = () => {
            const sections = [];
            curriculumContainer.querySelectorAll('.section-item').forEach(sectionEl => {
                const sectionId = sectionEl.dataset.sectionId;
                const sectionTitle = sectionEl.querySelector('.section-title-input').value;
                const lessons = [];
                sectionEl.querySelectorAll('.lesson-item').forEach(lessonEl => {
                    lessons.push({
                        id: lessonEl.dataset.lessonId,
                        title: lessonEl.querySelector('.lesson-title-input').value,
                        type: lessonEl.querySelector('.lesson-type-select').value
                    });
                });
                sections.push({
                    id: sectionId,
                    title: sectionTitle,
                    lessons: lessons
                });
            });
            curriculumDataInput.value = JSON.stringify(sections);
        };

        const initLessonSortable = (container) => {
            new Sortable(container, {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'sortable-ghost',
                onEnd: () => {
                    updateNumbers();
                    serializeCurriculum();
                }
            });
        };

        const createSectionElement = () => {
            const sectionId = `new-section-${Date.now()}`;
            const section = document.createElement('div');
            section.className = 'section-item';
            section.setAttribute('data-section-id', sectionId);
            section.innerHTML = `
            <div class="section-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <span class="drag-handle me-2">≡</span>
                    <span class="section-number"></span>
                    <input type="text" class="section-title-input form-control form-control-sm d-inline-block w-auto ms-2" placeholder="{{ __('courses.section_title') }}">
                </div>
                <div class="section-actions">
                    <button type="button" class="btn btn-sm btn-outline-secondary add-lesson-btn"><i class="fas fa-plus"></i> {{ __('courses.add_lesson') }}</button>
                    <button type="button" class="btn btn-sm btn-outline-danger delete-section-btn"><i class="fas fa-trash"></i></button>
                </div>
            </div>
            <div class="section-content">
                <div class="lessons-container" data-section-id="${sectionId}"></div>
            </div>
        `;
            const lessonsContainer = section.querySelector('.lessons-container');
            initLessonSortable(lessonsContainer);
            return section;
        };

        const createLessonElement = () => {
            const lessonId = `new-lesson-${Date.now()}`;
            const lesson = document.createElement('div');
            lesson.className = 'lesson-item';
            lesson.setAttribute('data-lesson-id', lessonId);
            lesson.innerHTML = `
            <div class="d-flex align-items-center">
                <span class="drag-handle me-2">≡</span>
                <span class="lesson-number"></span>
                <input type="text" class="lesson-title-input form-control form-control-sm d-inline-block w-auto ms-2" placeholder="{{ __('courses.lesson_title') }}">
                <select class="lesson-type-select form-select form-select-sm ms-2" style="width: auto;">
                    <option value="video" selected>Video</option>
                    <option value="article">Article</option>
                    <option value="presentation">Presentation</option>
                    <option value="mixed">Mixed</option>
                </select>
            </div>
            <div class="lesson-actions">
                <input type="file" class="lesson-file-input" style="display: none;" multiple>
                <button type="button" class="btn btn-sm btn-outline-info trigger-upload-btn"><i class="fas fa-upload"></i></button>
                <button type="button" class="btn btn-sm btn-outline-danger delete-lesson-btn"><i class="fas fa-trash"></i></button>
            </div>
        `;
            return lesson;
        };

        addSectionBtn.addEventListener('click', () => {
            const newSection = createSectionElement();
            curriculumContainer.appendChild(newSection);
            updateNumbers();
            toggleEmptyState();
            serializeCurriculum();
        });

        curriculumContainer.addEventListener('click', (e) => {
            if (e.target.closest('.delete-section-btn')) {
                e.target.closest('.section-item').remove();
                updateNumbers();
                toggleEmptyState();
                serializeCurriculum();
            }
            if (e.target.closest('.add-lesson-btn')) {
                const lessonsContainer = e.target.closest('.section-item').querySelector('.lessons-container');
                const newLesson = createLessonElement();
                lessonsContainer.appendChild(newLesson);
                updateNumbers();
                serializeCurriculum();
            }
            if (e.target.closest('.delete-lesson-btn')) {
                e.target.closest('.lesson-item').remove();
                updateNumbers();
                serializeCurriculum();
            }
            if (e.target.closest('.trigger-upload-btn')) {
                const lessonItem = e.target.closest('.lesson-item');
                const fileInput = lessonItem.querySelector('.lesson-file-input');
                fileInput.click();

                fileInput.onchange = (event) => {
                    const files = event.target.files;
                    if (files.length > 0) {
                        const lessonTitleInput = lessonItem.querySelector('.lesson-title-input');
                        const lessonTypeSelect = lessonItem.querySelector('.lesson-type-select');
                        const fileNames = Array.from(files).map(file => file.name).join(', ');
                        
                        if(lessonTitleInput.value === '') {
                           lessonTitleInput.value = fileNames;
                        }
                        
                        lessonTypeSelect.value = 'mixed';
                        serializeCurriculum();
                    }
                };
            }
        });

        // Initial setup
        new Sortable(curriculumContainer, {
            animation: 150,
            handle: '.section-header',
            ghostClass: 'sortable-ghost',
            onEnd: () => {
                updateNumbers();
                serializeCurriculum();
            }
        });

        document.querySelectorAll('.lessons-container').forEach(initLessonSortable);

        toggleEmptyState();
        updateNumbers();
        serializeCurriculum();
    });
</script>

<style>
    .curriculum-builder {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        background: #f8f9fa;
    }

    .curriculum-container {
        min-height: 200px;
    }

    .section-item {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }

    .section-item:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .section-header {
        background: #f1f3f4;
        padding: 15px;
        border-bottom: 1px solid #e0e0e0;
        cursor: move;
    }

    .section-content {
        padding: 15px;
    }

    .lesson-item {
        background: #fafafa;
        border: 1px solid #eee;
        border-radius: 4px;
        padding: 10px;
        margin-bottom: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .lesson-item:hover {
        background: #f0f0f0;
    }

    .drag-handle {
        cursor: move;
        color: #666;
        margin-right: 10px;
        font-size: 16px;
    }

    .sortable-ghost {
        opacity: 0.5;
    }

    .section-title-input,
    .lesson-title-input {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 4px 8px;
        font-size: 14px;
    }

    .section-title-input:focus,
    .lesson-title-input:focus,
    .lesson-type-select:focus {
        outline: none;
        border-color: #4e73df;
        box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.25);
    }

    .section-actions,
    .lesson-actions {
        display: flex;
        gap: 5px;
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: #666;
        background: white;
        border-radius: 6px;
        border: 2px dashed #ddd;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.5;
    }
</style>