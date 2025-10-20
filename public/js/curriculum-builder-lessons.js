/**
 * Curriculum Builder - Lessons Management
 * Part 2: Lesson Forms and Save/Edit Functions
 */

// Extend CurriculumBuilder prototype
CurriculumBuilder.prototype.showLessonForm = function (lessonEl, type) {
    const container = lessonEl.querySelector('.lesson-form-container');

    if (type === 'video') {
        this.createVideoForm(container);
    } else if (type === 'article') {
        this.createArticleForm(container);
    } else if (type === 'file') {
        this.createFileForm(container);
    }
};

// ==========================================
// Video Form
// ==========================================
CurriculumBuilder.prototype.createVideoForm = function (container) {
    container.innerHTML = `
        <div class="lesson-form p-3 border rounded bg-light mt-2">
            <div class="row">
                <div class="col-md-7">
                    <div class="mb-2">
                        <label class="form-label small fw-bold">الوصف</label>
                        <textarea class="form-control form-control-sm lesson-description" rows="3" 
                            maxlength="255" placeholder="وصف مختصر عن الدرس (اختياري)"></textarea>
                        <small class="text-muted"><span class="char-count">0</span>/255 حرف</small>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="mb-2">
                        <label class="form-label small fw-bold">ملف الفيديو <span class="text-danger">*</span></label>
                        <input type="file" class="form-control form-control-sm video-file-input" 
                            accept="video/mp4,video/avi,video/webm,video/mov">
                        <small class="text-muted">MP4, AVI, WebM - حتى 500MB</small>
                    </div>
                    <div class="video-preview-container mt-2" 
                        style="height: 120px; width: 213px; background: #000; border-radius: 4px; 
                        display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        <span class="text-white-50"><i class="fas fa-video fa-3x"></i></span>
                    </div>
                    <small class="text-muted d-block mt-1" id="video-duration"></small>
                </div>
            </div>
        </div>
    `;

    // Character counter
    const textarea = container.querySelector('.lesson-description');
    const charCount = container.querySelector('.char-count');
    textarea.addEventListener('input', () => {
        charCount.textContent = textarea.value.length;
    });

    // Video preview and duration
    const videoInput = container.querySelector('.video-file-input');
    const previewContainer = container.querySelector('.video-preview-container');
    const durationEl = container.querySelector('#video-duration');

    videoInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const video = document.createElement('video');
            video.preload = 'metadata';

            video.onloadedmetadata = function () {
                window.URL.revokeObjectURL(video.src);
                const duration = Math.floor(video.duration);
                const minutes = Math.floor(duration / 60);
                const seconds = duration % 60;
                durationEl.innerHTML = `<i class="fas fa-clock"></i> المدة: ${minutes}:${seconds.toString().padStart(2, '0')}`;
            };

            video.src = URL.createObjectURL(file);
            video.style.width = '100%';
            video.style.height = '100%';
            video.style.objectFit = 'cover';
            video.controls = true;
            previewContainer.innerHTML = '';
            previewContainer.appendChild(video);
        }
    });
};

// ==========================================
// Article Form
// ==========================================
CurriculumBuilder.prototype.createArticleForm = function (container) {
    const editorId = `editor-${Date.now()}`;
    container.innerHTML = `
        <div class="lesson-form p-3 border rounded bg-light mt-2">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-2">
                        <label class="form-label small fw-bold">الوصف المختصر</label>
                        <textarea class="form-control form-control-sm lesson-description" rows="2" 
                            maxlength="255" placeholder="وصف قصير يظهر في قائمة الدروس"></textarea>
                        <small class="text-muted"><span class="char-count">0</span>/255</small>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-bold">محتوى المقال <span class="text-danger">*</span></label>
                        <textarea id="${editorId}" class="article-content-editor"></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-2">
                        <label class="form-label small fw-bold">صورة البوستر <span class="text-danger">*</span></label>
                        <input type="file" class="form-control form-control-sm article-image-input" accept="image/*">
                        <small class="text-muted">1200x630px مُوصى به</small>
                    </div>
                    <div class="image-preview mt-2"></div>
                </div>
            </div>
        </div>
    `;

    // Character counter
    const textarea = container.querySelector('.lesson-description');
    const charCount = container.querySelector('.char-count');
    textarea.addEventListener('input', () => {
        charCount.textContent = textarea.value.length;
    });

    // Image preview
    const imageInput = container.querySelector('.article-image-input');
    const imagePreview = container.querySelector('.image-preview');
    imageInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (event) {
                imagePreview.innerHTML = `<img src="${event.target.result}" class="img-thumbnail" style="max-width: 100%; border-radius: 8px;">`;
            };
            reader.readAsDataURL(file);
        }
    });

    // استخدام textarea عادي بدلاً من TinyMCE
    // للحصول على محرر نصوص متقدم، احصل على API key مجاني من:
    // https://www.tiny.cloud/auth/signup/

    const editor = container.querySelector('.article-content-editor');
    if (editor) {
        editor.rows = 12;
        editor.className = 'form-control';
        editor.placeholder = 'اكتب محتوى المقال هنا... يمكنك استخدام HTML للتنسيق';
        editor.style.fontFamily = 'Arial, sans-serif';
        editor.style.fontSize = '14px';
    }
};

// ==========================================
// Files Form
// ==========================================
CurriculumBuilder.prototype.createFileForm = function (container) {
    container.innerHTML = `
        <div class="lesson-form p-3 border rounded bg-light mt-2">
            <div class="mb-2">
                <label class="form-label small fw-bold">الوصف</label>
                <textarea class="form-control form-control-sm lesson-description" rows="2" 
                    maxlength="255" placeholder="وصف للملفات المرفقة"></textarea>
                <small class="text-muted"><span class="char-count">0</span>/255</small>
            </div>
            <div class="mb-2">
                <label class="form-label small fw-bold">رفع الملفات <span class="text-danger">*</span></label>
                <input type="file" class="form-control form-control-sm files-input" multiple 
                    accept=".pdf,.ppt,.pptx,.zip,.jpg,.jpeg,.png,.gif">
                <small class="text-muted">PDF, PPT, ZIP, صور - حتى 50MB لكل ملف</small>
            </div>
            <div class="files-list mb-2"></div>
            <div class="form-check">
                <input class="form-check-input downloadable-checkbox" type="checkbox" id="downloadable-${Date.now()}" checked>
                <label class="form-check-label small" for="downloadable-${Date.now()}">
                    <i class="fas fa-download text-success"></i> السماح للطلاب بتحميل الملفات
                </label>
            </div>
        </div>
    `;

    // Character counter
    const textarea = container.querySelector('.lesson-description');
    const charCount = container.querySelector('.char-count');
    textarea.addEventListener('input', () => {
        charCount.textContent = textarea.value.length;
    });

    // Files list
    const filesInput = container.querySelector('.files-input');
    const filesList = container.querySelector('.files-list');
    filesInput.addEventListener('change', (e) => {
        const files = Array.from(e.target.files);
        if (files.length > 0) {
            let totalSize = 0;
            let html = '<div class="border rounded p-2 bg-white"><small class="fw-bold d-block mb-2"><i class="fas fa-paperclip"></i> الملفات المحددة:</small><ul class="list-unstyled mb-0">';
            files.forEach(file => {
                const size = (file.size / 1024 / 1024).toFixed(2);
                totalSize += parseFloat(size);
                const icon = this.getFileIcon(file.name);
                html += `<li class="small mb-1"><i class="${icon} me-1"></i>${file.name} <span class="text-muted">(${size} MB)</span></li>`;
            });
            html += `</ul><small class="text-muted d-block mt-2">الحجم الإجمالي: ${totalSize.toFixed(2)} MB</small></div>`;
            filesList.innerHTML = html;
        }
    });
};

// ==========================================
// Save Lesson
// ==========================================
CurriculumBuilder.prototype.saveLesson = async function (lessonEl) {
    const sectionEl = lessonEl.closest('.section-item');
    const sectionId = sectionEl.dataset.sectionId;

    const title = lessonEl.querySelector('.lesson-title-input').value.trim();
    const type = lessonEl.querySelector('.lesson-type-select').value;
    const description = lessonEl.querySelector('.lesson-description')?.value || '';

    if (!title) {
        this.showToast('الرجاء إدخال اسم الدرس', 'error');
        return;
    }

    // Prepare FormData
    const formData = new FormData();
    formData.append('title', title);
    formData.append('type', type);
    formData.append('description', description);

    // Type-specific validation
    if (type === 'video') {
        const videoFile = lessonEl.querySelector('.video-file-input')?.files[0];
        if (!videoFile) {
            this.showToast('الرجاء اختيار ملف فيديو', 'error');
            return;
        }
        formData.append('video', videoFile);  // ✅ تم التصحيح: 'video' بدلاً من 'video_url'
    }
    else if (type === 'article') {
        const image = lessonEl.querySelector('.article-image-input')?.files[0];
        if (!image) {
            this.showToast('الرجاء اختيار صورة البوستر', 'error');
            return;
        }
        formData.append('image', image);

        // الحصول على محتوى المقال من textarea
        const editor = lessonEl.querySelector('.article-content-editor');
        if (!editor) {
            this.showToast('خطأ: لم يتم إيجاد محرر النصوص', 'error');
            return;
        }

        const content = editor.value.trim();
        if (!content) {
            this.showToast('الرجاء كتابة محتوى المقال', 'error');
            return;
        }

        formData.append('content', content);
    }
    else if (type === 'file') {
        const files = lessonEl.querySelector('.files-input')?.files;
        if (!files || files.length === 0) {
            this.showToast('الرجاء اختيار ملف واحد على الأقل', 'error');
            return;
        }
        Array.from(files).forEach((file, index) => {
            formData.append(`files[${index}]`, file);
        });
        const downloadable = lessonEl.querySelector('.downloadable-checkbox')?.checked;
        formData.append('downloadable', downloadable ? '1' : '0');
    }

    const lessonId = lessonEl.dataset.lessonId;
    const isNew = lessonId.startsWith('temp-');
    const saveBtn = lessonEl.querySelector('.save-lesson-btn');

    // Loading state
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
    saveBtn.disabled = true;

    try {
        const url = isNew
            ? `/instructor/sections/${sectionId}/lessons`
            : `/instructor/lessons/${lessonId}`;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            lessonEl.dataset.lessonId = data.lesson.id;
            lessonEl.dataset.status = this.STATUS.SAVED;

            // Disable inputs
            lessonEl.querySelector('.lesson-title-input').disabled = true;
            lessonEl.querySelector('.lesson-type-select').disabled = true;

            // Update badge
            const warningBadge = lessonEl.querySelector('.badge.bg-warning');
            if (warningBadge) {
                warningBadge.outerHTML = '<span class="badge bg-success ms-2"><i class="fas fa-check"></i> محفوظ</span>';
            }

            // Hide save, show edit
            saveBtn.classList.add('d-none');
            let editBtn = lessonEl.querySelector('.edit-lesson-btn');
            if (!editBtn) {
                editBtn = document.createElement('button');
                editBtn.type = 'button';
                editBtn.className = 'btn btn-sm btn-info edit-lesson-btn';
                editBtn.innerHTML = '<i class="fas fa-edit"></i> تعديل';
                saveBtn.after(editBtn);
            }

            // Hide form
            lessonEl.querySelector('.lesson-form-container').style.display = 'none';

            this.showToast('تم حفظ الدرس بنجاح');
        } else {
            throw new Error(data.message || 'فشل الحفظ');
        }
    } catch (error) {
        console.error('Error:', error);
        this.showToast('حدث خطأ أثناء حفظ الدرس', 'error');
    } finally {
        saveBtn.innerHTML = '<i class="fas fa-save"></i> حفظ';
        saveBtn.disabled = false;
    }
};

// ==========================================
// Edit Lesson
// ==========================================
CurriculumBuilder.prototype.enableLessonEdit = function (lessonEl) {
    lessonEl.querySelector('.lesson-title-input').disabled = false;
    lessonEl.querySelector('.lesson-type-select').disabled = false;

    const formContainer = lessonEl.querySelector('.lesson-form-container');
    formContainer.style.display = 'block';

    const saveBtn = lessonEl.querySelector('.save-lesson-btn');
    saveBtn.classList.remove('d-none');

    lessonEl.dataset.status = this.STATUS.EDITING;

    const savedBadge = lessonEl.querySelector('.badge.bg-success');
    if (savedBadge) {
        savedBadge.className = 'badge bg-warning ms-2';
        savedBadge.innerHTML = '<i class="fas fa-edit"></i> يتم التعديل';
    }
};

// ==========================================
// Delete Lesson
// ==========================================
CurriculumBuilder.prototype.deleteLesson = async function (lessonEl) {
    const lessonId = lessonEl.dataset.lessonId;
    const isNew = lessonId.startsWith('temp-');

    if (isNew) {
        lessonEl.remove();
        this.updateNumbers();
        return;
    }

    if (!confirm('هل أنت متأكد من حذف هذا الدرس؟')) {
        return;
    }

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }

        const response = await fetch(`/instructor/lessons/${lessonId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });

        const data = await response.json();

        if (data.success) {
            lessonEl.remove();
            this.updateNumbers();
            this.showToast('تم حذف الدرس بنجاح');
        }
    } catch (error) {
        console.error('Error:', error);
        this.showToast('حدث خطأ أثناء حذف الدرس', 'error');
    }
};
