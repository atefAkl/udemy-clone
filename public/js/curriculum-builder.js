/**
 * Curriculum Builder v2.0
 * Progressive Save with Inline Editing
 */

class CurriculumBuilder {
    constructor(courseId) {
        this.courseId = courseId;
        this.container = document.getElementById('curriculumContainer');
        this.addSectionBtn = document.getElementById('addSectionBtn');
        this.emptyState = document.getElementById('emptyState');

        this.STATUS = {
            CREATING: 'creating',
            EDITING: 'editing',
            SAVED: 'saved'
        };

        this.init();
    }

    init() {
        // منع التكرار: إزالة listeners القديمة أولاً
        if (this.addSectionBtn._listener) {
            this.addSectionBtn.removeEventListener('click', this.addSectionBtn._listener);
        }

        this.addSectionBtn._listener = () => this.addNewSection();
        this.addSectionBtn.addEventListener('click', this.addSectionBtn._listener);

        // استخدام event delegation لتجنب التكرار
        if (!this.container._initialized) {
            this.container.addEventListener('click', (e) => this.handleClick(e));
            this.container._initialized = true;
        }

        this.initSectionsSortable();
        this.toggleEmptyState();
        this.updateNumbers();
    }

    // ==========================================
    // Helper Functions
    // ==========================================

    toggleEmptyState() {
        const count = this.container.querySelectorAll('.section-item').length;
        this.emptyState.style.display = count === 0 ? 'block' : 'none';
    }

    updateNumbers() {
        this.container.querySelectorAll('.section-item').forEach((section, sIndex) => {
            section.querySelector('.section-number').textContent = sIndex + 1;
            section.querySelectorAll('.lesson-item').forEach((lesson, lIndex) => {
                lesson.querySelector('.lesson-number').textContent = lIndex + 1;
            });
        });
    }

    showToast(message, type = 'success') {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
        const toast = document.createElement('div');
        toast.className = `alert ${alertClass} position-fixed alert-dismissible fade show`;
        toast.style = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            <i class="fas fa-${icon} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    }

    getFileIcon(filename) {
        const ext = filename.split('.').pop().toLowerCase();
        const icons = {
            pdf: 'fas fa-file-pdf text-danger',
            ppt: 'fas fa-file-powerpoint text-warning',
            pptx: 'fas fa-file-powerpoint text-warning',
            zip: 'fas fa-file-archive text-secondary',
            jpg: 'fas fa-file-image text-info',
            jpeg: 'fas fa-file-image text-info',
            png: 'fas fa-file-image text-info'
        };
        return icons[ext] || 'fas fa-file';
    }

    // ==========================================
    // Event Handlers
    // ==========================================

    handleClick(e) {
        const target = e.target.closest('button');
        if (!target) return;

        if (target.classList.contains('save-section-btn')) {
            const section = target.closest('.section-item');
            this.saveSection(section);
        }
        else if (target.classList.contains('edit-section-btn')) {
            const section = target.closest('.section-item');
            this.enableSectionEdit(section);
        }
        else if (target.classList.contains('delete-section-btn')) {
            const section = target.closest('.section-item');
            this.deleteSection(section);
        }
        else if (target.classList.contains('add-lesson-btn')) {
            const section = target.closest('.section-item');
            this.addNewLesson(section);
        }
        else if (target.classList.contains('save-lesson-btn')) {
            const lesson = target.closest('.lesson-item');
            this.saveLesson(lesson);
        }
        else if (target.classList.contains('edit-lesson-btn')) {
            const lesson = target.closest('.lesson-item');
            this.enableLessonEdit(lesson);
        }
        else if (target.classList.contains('delete-lesson-btn')) {
            const lesson = target.closest('.lesson-item');
            this.deleteLesson(lesson);
        }
    }

    // ==========================================
    // Section Management
    // ==========================================

    addNewSection() {
        const tempId = `temp-${Date.now()}`;
        const section = document.createElement('div');
        section.className = 'section-item';
        section.dataset.sectionId = tempId;
        section.dataset.status = this.STATUS.CREATING;

        section.innerHTML = `
            <div class="section-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center flex-grow-1">
                        <span class="drag-handle me-2" title="اسحب لإعادة الترتيب">≡</span>
                        <span class="section-number badge bg-secondary me-2"></span>
                        <div class="flex-grow-1">
                            <input type="text" class="section-title-input form-control form-control-sm mb-1" 
                                placeholder="اسم القسم" autofocus>
                            <textarea class="section-description-input form-control form-control-sm" 
                                placeholder="وصف مختصر للقسم (اختياري)" 
                                maxlength="255" 
                                rows="1"
                                style="resize: none; font-size: 0.875rem;"></textarea>
                            <small class="text-muted d-block">
                                <span class="char-counter">0</span>/255 حرف
                            </small>
                        </div>
                        <span class="badge bg-warning ms-2">
                            <i class="fas fa-exclamation-triangle"></i> غير محفوظ
                        </span>
                    </div>
                    <div class="section-actions">
                        <button type="button" class="btn btn-sm btn-success save-section-btn">
                            <i class="fas fa-save"></i> حفظ القسم
                        </button>
                        <button type="button" class="btn btn-sm btn-danger delete-section-btn">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="section-content">
                <div class="lessons-container"></div>
                <button type="button" class="btn btn-sm btn-outline-secondary add-lesson-btn mt-3" disabled>
                    <i class="fas fa-plus"></i> إضافة درس
                </button>
                <small class="text-muted d-block mt-2">
                    <i class="fas fa-info-circle"></i> احفظ القسم أولاً لتتمكن من إضافة الدروس
                </small>
            </div>
        `;

        this.container.appendChild(section);
        this.initLessonsSortable(section.querySelector('.lessons-container'));

        // Character counter للـ description
        const descriptionInput = section.querySelector('.section-description-input');
        const charCounter = section.querySelector('.char-counter');
        descriptionInput.addEventListener('input', function () {
            charCounter.textContent = this.value.length;
        });

        this.updateNumbers();
        this.toggleEmptyState();
    }

    async saveSection(sectionEl) {
        const titleInput = sectionEl.querySelector('.section-title-input');
        const descriptionInput = sectionEl.querySelector('.section-description-input');
        const title = titleInput.value.trim();
        const description = descriptionInput ? descriptionInput.value.trim() : '';

        console.log(title, description);

        if (!title) {
            this.showToast('الرجاء إدخال اسم القسم', 'error');
            titleInput.focus();
            return;
        }

        const sectionId = sectionEl.dataset.sectionId;
        const isNew = sectionId.startsWith('temp-');
        const saveBtn = sectionEl.querySelector('.save-section-btn');

        // Loading state
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
        saveBtn.disabled = true;

        try {
            const url = isNew
                ? `/instructor/courses/${this.courseId}/sections`
                : `/instructor/sections/${sectionId}`;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                throw new Error('CSRF token not found');
            }

            const response = await fetch(url, {
                method: isNew ? 'POST' : 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    title,
                    description
                })
            });

            const data = await response.json();

            if (data.success) {
                // Update section
                sectionEl.dataset.sectionId = data.section.id;
                sectionEl.dataset.status = this.STATUS.SAVED;
                titleInput.disabled = true;
                if (descriptionInput) descriptionInput.disabled = true;

                // Update UI
                const warningBadge = sectionEl.querySelector('.badge.bg-warning');
                if (warningBadge) {
                    warningBadge.outerHTML = '<span class="badge bg-success ms-2"><i class="fas fa-check"></i> محفوظ</span>';
                }

                // Hide save, show edit
                saveBtn.classList.add('d-none');
                let editBtn = sectionEl.querySelector('.edit-section-btn');
                if (!editBtn) {
                    editBtn = document.createElement('button');
                    editBtn.type = 'button';
                    editBtn.className = 'btn btn-sm btn-info edit-section-btn';
                    editBtn.innerHTML = '<i class="fas fa-edit"></i> تعديل';
                    saveBtn.after(editBtn);
                }

                // Enable add lesson button
                const addLessonBtn = sectionEl.querySelector('.add-lesson-btn');
                addLessonBtn.disabled = false;
                addLessonBtn.classList.remove('btn-outline-secondary');
                addLessonBtn.classList.add('btn-outline-primary');

                const infoText = sectionEl.querySelector('.section-content small.text-muted');
                if (infoText) infoText.remove();

                this.showToast('تم حفظ القسم بنجاح');
            } else {
                throw new Error(data.message || 'فشل الحفظ');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showToast('حدث خطأ أثناء حفظ القسم', 'error');
        } finally {
            saveBtn.innerHTML = '<i class="fas fa-save"></i> حفظ القسم';
            saveBtn.disabled = false;
        }
    }

    enableSectionEdit(sectionEl) {
        const titleInput = sectionEl.querySelector('.section-title-input');
        const descriptionInput = sectionEl.querySelector('.section-description-input');

        titleInput.disabled = false;
        titleInput.focus();
        titleInput.select();

        if (descriptionInput) {
            descriptionInput.disabled = false;

            // إضافة character counter إذا لم يكن موجوداً
            const charCounter = sectionEl.querySelector('.char-counter');
            if (charCounter && !descriptionInput._hasListener) {
                descriptionInput.addEventListener('input', function () {
                    charCounter.textContent = this.value.length;
                });
                descriptionInput._hasListener = true;
            }
        }

        sectionEl.querySelector('.save-section-btn').classList.remove('d-none');
        sectionEl.dataset.status = this.STATUS.EDITING;

        const savedBadge = sectionEl.querySelector('.badge.bg-success');
        if (savedBadge) {
            savedBadge.className = 'badge bg-warning ms-2';
            savedBadge.innerHTML = '<i class="fas fa-edit"></i> يتم التعديل';
        }
    }

    async deleteSection(sectionEl) {
        const sectionId = sectionEl.dataset.sectionId;
        const isNew = sectionId.startsWith('temp-');

        if (isNew) {
            sectionEl.remove();
            this.updateNumbers();
            this.toggleEmptyState();
            return;
        }

        if (!confirm('هل أنت متأكد من حذف هذا القسم؟\nسيتم حذف جميع الدروس التابعة له أيضاً!')) {
            return;
        }

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                throw new Error('CSRF token not found');
            }

            const response = await fetch(`/instructor/sections/${sectionId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const data = await response.json();

            if (data.success) {
                sectionEl.remove();
                this.updateNumbers();
                this.toggleEmptyState();
                this.showToast('تم حذف القسم بنجاح');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showToast('حدث خطأ أثناء حذف القسم', 'error');
        }
    }

    // ==========================================
    // Lesson Management - Part 1
    // ==========================================

    addNewLesson(sectionEl) {
        const sectionId = sectionEl.dataset.sectionId;

        // Check for unsaved lessons
        const unsavedLessons = sectionEl.querySelectorAll('.lesson-item[data-status="creating"]');
        if (unsavedLessons.length > 0) {
            const confirmMsg = 'هناك دروس غير محفوظة!\n\nالخيارات:\n- إلغاء: للعودة وحفظ الدروس\n- موافق: حذف الدروس غير المحفوظة والمتابعة';
            if (!confirm(confirmMsg)) {
                return;
            }
            unsavedLessons.forEach(lesson => lesson.remove());
        }

        const tempId = `temp-${Date.now()}`;
        const lesson = document.createElement('div');
        lesson.className = 'lesson-item';
        lesson.dataset.lessonId = tempId;
        lesson.dataset.status = this.STATUS.CREATING;
        lesson.dataset.type = 'video';

        lesson.innerHTML = `
            <div class="lesson-header">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="d-flex align-items-center flex-grow-1">
                        <span class="drag-handle me-2">≡</span>
                        <span class="lesson-number badge bg-secondary me-2"></span>
                        <input type="text" class="lesson-title-input form-control form-control-sm" 
                            placeholder="اسم الدرس" style="max-width: 250px;">
                        <select class="lesson-type-select form-select form-select-sm ms-2" style="max-width: 130px;">
                            <option value="video">🎬 فيديو</option>
                            <option value="article">📝 مقال</option>
                            <option value="file">📁 ملفات</option>
                        </select>
                        <span class="badge bg-warning ms-2">
                            <i class="fas fa-exclamation-triangle"></i> غير محفوظ
                        </span>
                    </div>
                    <div class="lesson-actions">
                        <button type="button" class="btn btn-sm btn-success save-lesson-btn">
                            <i class="fas fa-save"></i> حفظ
                        </button>
                        <button type="button" class="btn btn-sm btn-danger delete-lesson-btn">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="lesson-form-container"></div>
        `;

        const lessonsContainer = sectionEl.querySelector('.lessons-container');
        lessonsContainer.appendChild(lesson);

        // Show initial form
        const typeSelect = lesson.querySelector('.lesson-type-select');
        this.showLessonForm(lesson, typeSelect.value);

        // Listen to type changes
        typeSelect.addEventListener('change', () => {
            lesson.dataset.type = typeSelect.value;
            this.showLessonForm(lesson, typeSelect.value);
        });

        this.updateNumbers();
    }

    // المتابعة في التعليق التالي...
    // (باقي دوال الـ lessons في ملف منفصل)

    // ==========================================
    // Sortable Initialization
    // ==========================================

    initSectionsSortable() {
        if (typeof Sortable !== 'undefined') {
            new Sortable(this.container, {
                animation: 150,
                handle: '.section-header .drag-handle',
                ghostClass: 'sortable-ghost',
                onEnd: () => this.updateNumbers()
            });
        }
    }

    initLessonsSortable(lessonsContainer) {
        if (typeof Sortable !== 'undefined') {
            new Sortable(lessonsContainer, {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'sortable-ghost',
                onEnd: () => this.updateNumbers()
            });
        }
    }
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function () {
    const courseIdEl = document.getElementById('curriculumContainer');
    if (courseIdEl) {
        const courseId = courseIdEl.dataset.courseId;
        window.curriculumBuilder = new CurriculumBuilder(courseId);
    }
});
