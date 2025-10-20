@props(['course'])

<div class="promotion-info-component">
    @if (session('success'))
    <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
        <svg class="me-2" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
        <svg class="me-2" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <form action="{{ route('instructor.courses.update.promotion-info', [$course->id]) }}" method="POST" enctype="multipart/form-data" id="promotionForm">
        @csrf
        @method('PUT')

        <!-- قسم البوستر -->
        <div id="poster-section" class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-1 fw-semibold">تحميل بانر</h5>
                <small class="text-muted">الحجم المقبول: 1920x1080 بكسل</small>
            </div>
            <div class="card-footer border-top-0">
                @if ($errors->has('banner'))
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">
                        @foreach ($errors->get('banner') as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="row g-3">

                    <!-- أزرار التحميل -->
                    <div class="col-12 col-md-4 col-lg-3">
                        <div class="d-grid gap-2">
                            <!-- زر تحميل من الجهاز -->
                            <button type="button" class="btn btn-outline-primary text-end d-flex align-items-center justify-content-start"
                                onclick="document.getElementById('poster-file-input').click()">
                                <svg class="me-2 text-primary" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <span class="fw-medium">تحميل من الجهاز</span>
                            </button>
                            <input type="file" id="poster-file-input" name="banner" accept=".png,.jpg,.jpeg,.webp" class="d-none">

                            <!-- زر اختر من المكتبة -->
                            <button type="button" class="btn btn-outline-primary text-end d-flex align-items-center justify-content-start"
                                onclick="selectFromLibrary('poster')">
                                <svg class="me-2 text-primary" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                </svg>
                                <span class="fw-medium">اختر من المكتبة</span>
                            </button>

                            <!-- زر من رابط -->
                            <button type="button" class="btn btn-outline-primary text-end d-flex align-items-center justify-content-start"
                                onclick="toggleLinkInput('poster')">
                                <svg class="me-2 text-primary" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                </svg>
                                <span class="fw-medium">من رابط</span>
                            </button>

                            <!-- حقل الرابط المخفي -->
                            <div id="poster-link-input-container" class="d-none">
                                <div class="input-group input-group-sm">
                                    <input type="url" id="poster-link-input" placeholder="https://example.com/image.jpg" class="form-control">
                                    <button type="button" onclick="applyLink('poster')" class="btn btn-primary">تطبيق</button>
                                </div>
                            </div>

                            <!-- زر لصق من الحافظة -->
                            <button type="button" class="btn btn-outline-primary text-end d-flex align-items-center justify-content-start"
                                onclick="pasteFromClipboard('poster')">
                                <svg class="me-2 text-primary" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span class="fw-medium">لصق من الحافظة</span>
                            </button>
                        </div>
                    </div>
                    <!-- منطقة المعاينة -->
                    <div class="col-12 col-md-8 col-lg-9">
                        <div id="poster-preview" class="border rounded p-4 text-center d-flex align-items-center justify-content-center @if($course->banner_url) border-success bg-success bg-opacity-10 @else border-secondary border-2 @endif" style="min-height: 300px; border-style: @if(!$course->banner_url) dashed @else solid @endif;">
                            @if($course->banner_url)
                            <div class="text-center w-100">
                                <img src="{{ $course->banner_url }}" alt="Banner" class="img-fluid rounded " style="max-height: 240px; object-fit: contain;">
                                <p class="mt-3 text-success fw-medium small">تم تحميل البانر بنجاح</p>
                            </div>
                            @else
                            <div class="text-center">
                                <svg class="mb-3 text-secondary" width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p id="poster-status" class="text-secondary fw-medium">تحميل بانر</p>
                            </div>
                            @endif
                        </div>
                        <div id="poster-error" class="alert alert-danger mt-3 d-none"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- قسم الفيديو الترويجي -->
        <div id="video-section" class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-1 fw-semibold">تحميل فيديو ترويجي</h5>
                <small class="text-muted">المدة: 3-10 دقائق فقومي بوا</small>
            </div>
            <div class="card-footer border-top-0">
                @if ($errors->has('promo_video'))
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">
                        @foreach ($errors->get('promo_video') as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="row g-3">
                    <!-- أزرار التحميل -->
                    <div class="col-12 col-md-4 col-lg-3">
                        <div class="d-grid gap-2">
                            <!-- زر تحميل من الجهاز -->
                            <button type="button" class="btn btn-outline-primary text-end d-flex align-items-center justify-content-start"
                                onclick="document.getElementById('video-file-input').click()">
                                <svg class="me-2 text-primary" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <span class="fw-medium">تحميل من الجهاز</span>
                            </button>
                            <input type="file" id="video-file-input" name="promo_video" accept=".avi,.flv,.webm,.wmv,.mp4" class="d-none">

                            <!-- زر اختر من المكتبة -->
                            <button type="button" class="btn btn-outline-primary text-end d-flex align-items-center justify-content-start"
                                onclick="selectFromLibrary('video')">
                                <svg class="me-2 text-primary" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                <span class="fw-medium">اختر من المكتبة</span>
                            </button>

                            <!-- زر من رابط -->
                            <button type="button" class="btn btn-outline-primary text-end d-flex align-items-center justify-content-start"
                                onclick="toggleLinkInput('video')">
                                <svg class="me-2 text-primary" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                </svg>
                                <span class="fw-medium">من رابط</span>
                            </button>

                            <!-- حقل الرابط المخفي -->
                            <div id="video-link-input-container" class="d-none">
                                <div class="input-group input-group-sm">
                                    <input type="url" id="video-link-input" placeholder="https://youtube.com/watch?v=..." class="form-control">
                                    <button type="button" onclick="applyLink('video')" class="btn btn-primary">تطبيق</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- منطقة المعاينة -->
                    <div class="col-12 col-md-8 col-lg-9">
                        <div id="video-preview" class="border rounded p-2 text-center d-flex align-items-center justify-content-center @if($course->promo_video_url) border-success bg-success bg-opacity-10 @else border-secondary border-2 @endif" style="min-height: 300px; border-style: @if(!$course->promo_video_url) dashed @else solid @endif;">
                            @if($course->promo_video_url)
                            <div class="text-center w-100 mx-auto">
                                <video src="{{ $course->promo_video_url }}" controls class="img-fluid rounded " style="max-height: 240px;">
                                    المتصفح لا يدعم عرض هذا الفيديو
                                </video>
                                <p class="mt-3 text-success fw-medium small">تم تحميل الفيديو الترويجي بنجاح</p>
                            </div>
                            @else
                            <div class="text-center">
                                <svg class="mb-3 text-secondary" width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                <p id="video-status" class="text-secondary fw-medium">تحميل فيديو ترويجي</p>
                            </div>
                            @endif
                        </div>
                        <div id="video-error" class="alert alert-danger mt-3 d-none"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden fields for source types -->
        <input type="hidden" data-source="{{$course->banner_source}}" id="banner-source" name="banner_source" value="device">
        <input type="hidden" data-source="{{$course->video_source}}" id="video-source" name="video_source" value="device">

        <!-- Submit Buttons -->
        <div class="d-flex justify-content-between gap-2">
            <button type="button" onclick="window.history.back()" class="btn btn-warning">
                إعادة تعيين
            </button>
            <button type="submit" id="submit-button" class="btn btn-primary">
                تحديث
            </button>
        </div>
    </form>
</div>

{{-- Include Modals --}}
@include('components.promotion-modals')

<style>
    .poster-preview-box,
    #video-preview {
        transition: all 0.3s ease;
    }
</style>

<script>
    // دالة تبديل عرض حقل الرابط
    function toggleLinkInput(mediaType) {
        const container = document.getElementById(mediaType + '-link-input-container');
        if (container) {
            container.classList.toggle('d-none');
        }
    }

    // دالة لتحديث منطقة العرض المسبق
    function updatePreview(mediaType, method, value) {
        const previewEl = document.getElementById(mediaType + '-preview');

        // إزالة أي محتوى سابق
        previewEl.innerHTML = '';

        if (method === 'file') {
            const file = value.files[0];
            if (file) {
                const url = URL.createObjectURL(file);
                if (mediaType === 'poster') {
                    previewEl.innerHTML = `
                        <div class="text-center w-100">
                            <img src="${url}" class="img-fluid rounded" style="max-height: 240px; object-fit: contain;" alt="معاينة البوستر">
                            <p class="mt-3 text-success fw-medium small">تم تحميل الصورة: ${file.name}</p>
                        </div>
                    `;
                } else if (mediaType === 'video') {
                    previewEl.innerHTML = `
                        <div class="text-center w-100">
                            <video src="${url}" controls class="img-fluid rounded" style="max-height: 240px;">
                                المتصفح لا يدعم عرض هذا الفيديو
                            </video>
                            <p class="mt-3 text-success fw-medium small">تم تحميل الفيديو: ${file.name}</p>
                        </div>
                    `;
                }
                // تحديث التنسيق لإظهار النجاح
                previewEl.classList.remove('border-secondary', 'border-dashed');
                previewEl.classList.add('border-success', 'bg-success', 'bg-opacity-10');
                previewEl.style.borderStyle = 'solid';
            } else {
                resetPreview(mediaType);
            }
        } else if (method === 'link' && value) {
            if (mediaType === 'poster') {
                previewEl.innerHTML = `
                    <div class="text-center w-100">
                        <img src="${value}" 
                             class="img-fluid rounded" 
                             style="max-height: 240px; object-fit: contain;" 
                             alt="صورة من رابط خارجي"
                             onload="this.parentElement.parentElement.classList.add('border-success', 'bg-success', 'bg-opacity-10'); this.parentElement.parentElement.classList.remove('border-secondary'); this.parentElement.parentElement.style.borderStyle='solid'"
                             onerror="this.parentElement.innerHTML='<div class=\\'alert alert-danger\\'>خطأ في تحميل الصورة من الرابط</div>'">
                        <p class="mt-3 text-success fw-medium small">تم ربط الصورة من: ${value}</p>
                    </div>
                `;
            } else if (mediaType === 'video') {
                // فيديو - يمكن أن يكون يوتيوب أو رابط مباشر
                if (value.includes('youtube.com') || value.includes('youtu.be')) {
                    const videoId = extractYouTubeId(value);
                    if (videoId) {
                        previewEl.innerHTML = `
                            <div class="text-center w-100">
                                <iframe width="100%" height="240" 
                                        src="https://www.youtube.com/embed/${videoId}" 
                                        class="rounded"
                                        frameborder="0" 
                                        allowfullscreen>
                                </iframe>
                                <p class="mt-3 text-success fw-medium small">تم ربط فيديو يوتيوب</p>
                            </div>
                        `;
                    }
                } else {
                    previewEl.innerHTML = `
                        <div class="text-center w-100">
                            <video src="${value}" controls class="img-fluid rounded" style="max-height: 240px;"
                                   onload="this.parentElement.parentElement.classList.add('border-success', 'bg-success', 'bg-opacity-10')"
                                   onerror="this.parentElement.innerHTML='<div class=\\'alert alert-danger\\'>خطأ في تحميل الفيديو من الرابط</div>'">
                                المتصفح لا يدعم عرض هذا الفيديو
                            </video>
                            <p class="mt-3 text-success fw-medium small">تم ربط الفيديو من: ${value}</p>
                        </div>
                    `;
                }
                previewEl.classList.remove('border-secondary', 'border-dashed');
                previewEl.classList.add('border-success', 'bg-success', 'bg-opacity-10');
                previewEl.style.borderStyle = 'solid';
            }
        } else {
            resetPreview(mediaType);
        }
    }

    // دالة لاستخراج معرف فيديو يوتيوب
    function extractYouTubeId(url) {
        const regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
        const match = url.match(regExp);
        return (match && match[7].length === 11) ? match[7] : null;
    }

    // دالة لإعادة تعيين المعاينة للحالة الافتراضية
    function resetPreview(mediaType) {
        const previewEl = document.getElementById(mediaType + '-preview');
        const defaultIcon = mediaType === 'poster' ?
            '<svg class="mb-3 text-secondary" width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>' :
            '<svg class="mb-3 text-secondary" width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>';
        const defaultText = mediaType === 'poster' ? 'تحميل بانر' : 'تحميل فيديو ترويجي';

        previewEl.innerHTML = `<div class="text-center">${defaultIcon}<p class="text-secondary fw-medium">${defaultText}</p></div>`;
        previewEl.classList.add('border-secondary', 'border-2');
        previewEl.classList.remove('border-success', 'bg-success', 'bg-opacity-10');
        previewEl.style.borderStyle = 'dashed';
    }

    // معالج حدث تغيير الملف للجهاز (بوستر)
    document.getElementById('poster-file-input').addEventListener('change', function(e) {
        validateImageFile(e.target);
    });

    // معالج حدث تغيير الملف للجهاز (فيديو)
    document.getElementById('video-file-input').addEventListener('change', function(e) {
        validateVideoFile(e.target);
    });

    // دالة تطبيق الرابط
    function applyLink(mediaType) {
        const input = document.getElementById(mediaType + '-link-input');
        if (input && input.value.trim()) {
            updatePreview(mediaType, 'link', input.value.trim());
            // إخفاء حقل الإدخال بعد التطبيق
            toggleLinkInput(mediaType);
        } else {
            alert('يرجى إدخال رابط صحيح');
        }
    }

    // دالة محاكاة الاختيار من المكتبة
    function selectFromLibrary(mediaType) {
        const sampleImages = [
            'https://picsum.photos/seed/' + Math.random() + '/1920/1080',
        ];

        const sampleVideos = [
            'https://www.w3schools.com/html/mov_bbb.mp4'
        ];

        if (mediaType === 'poster') {
            const randomImage = sampleImages[0];
            const previewEl = document.getElementById('poster-preview');
            previewEl.innerHTML = `
                <div class="text-center w-100">
                    <img src="${randomImage}" 
                         class="img-fluid rounded" 
                         style="max-height: 240px; object-fit: contain;" 
                         alt="صورة من المكتبة">
                    <p class="mt-3 text-success fw-medium small">تم اختيار صورة من المكتبة</p>
                </div>
            `;
            previewEl.classList.remove('border-secondary', 'border-dashed');
            previewEl.classList.add('border-success', 'bg-success', 'bg-opacity-10');
            previewEl.style.borderStyle = 'solid';
        } else if (mediaType === 'video') {
            const randomVideo = sampleVideos[0];
            const previewEl = document.getElementById('video-preview');
            previewEl.innerHTML = `
                <div class="text-center w-100">
                    <video src="${randomVideo}" controls class="img-fluid rounded" style="max-height: 240px;">
                        المتصفح لا يدعم عرض هذا الفيديو
                    </video>
                    <p class="mt-3 text-success fw-medium small">تم اختيار فيديو من المكتبة</p>
                </div>
            `;
            previewEl.classList.remove('border-secondary', 'border-dashed');
            previewEl.classList.add('border-success', 'bg-success', 'bg-opacity-10');
            previewEl.style.borderStyle = 'solid';
        }
    }

    // دالة لصق من الحافظة
    async function pasteFromClipboard(mediaType) {
        try {
            const text = await navigator.clipboard.readText();
            if (isValidUrl(text)) {
                updatePreview(mediaType, 'link', text);
            } else {
                alert('يرجى لصق رابط صحيح');
            }
        } catch (err) {
            alert('لا يمكن قراءة محتوى الحافظة. يرجى المحاولة مرة أخرى');
        }
    }

    // دالة للتحقق من صحة الرابط
    function isValidUrl(string) {
        try {
            new URL(string);
            return true;
        } catch (_) {
            return false;
        }
    }

    // دالة التحقق من ملف الصورة
    function validateImageFile(input) {
        const file = input.files[0];
        const errorDiv = document.getElementById('poster-error');
        errorDiv.classList.add('d-none');
        errorDiv.innerHTML = '';

        if (!file) {
            resetPreview('poster');
            return;
        }

        const errors = [];

        // التحقق من النوع
        const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            errors.push('نوع الملف غير مسموح. الأنواع المسموحة: PNG, JPG, WEBP');
        }

        // التحقق من الحجم (3MB)
        const maxSize = 3 * 1024 * 1024;
        if (file.size > maxSize) {
            errors.push(`حجم الملف (${(file.size / 1024 / 1024).toFixed(2)} ميجابايت) يتجاوز الحد الأقصى المسموح (3 ميجابايت)`);
        }

        // التحقق من الأبعاد
        const img = new Image();
        img.onload = function() {
            const width = img.width;
            const height = img.height;
            const aspectRatio = width / height;
            const targetRatio = 16 / 9;
            const tolerance = 0.1;
            const minRatio = targetRatio * (1 - tolerance);
            const maxRatio = targetRatio * (1 + tolerance);

            if (aspectRatio < minRatio || aspectRatio > maxRatio) {
                errors.push(`أبعاد الصورة (${width}x${height}) غير متوافقة مع نسبة 16:9. النسبة الحالية: ${aspectRatio.toFixed(2)}`);
            }

            if (errors.length > 0) {
                showErrors('poster', errors);
                input.value = '';
                resetPreview('poster');
            } else {
                updatePreview('poster', 'file', input);
            }
        };
        img.src = URL.createObjectURL(file);
    }

    // دالة التحقق من ملف الفيديو
    function validateVideoFile(input) {
        const file = input.files[0];
        const errorDiv = document.getElementById('video-error');
        errorDiv.classList.add('d-none');
        errorDiv.innerHTML = '';

        if (!file) {
            resetPreview('video');
            return;
        }

        const errors = [];

        // التحقق من النوع
        const fileExtension = file.name.split('.').pop().toLowerCase();
        const allowedExtensions = ['avi', 'flv', 'webm', 'wmv', 'mp4'];

        if (!allowedExtensions.includes(fileExtension)) {
            errors.push('نوع الملف غير مسموح. الأنواع المسموحة: AVI, FLV, WEBM, WMV, MP4');
        }

        // التحقق من الحجم (10MB)
        const maxSize = 10 * 1024 * 1024;
        if (file.size > maxSize) {
            errors.push(`حجم الملف (${(file.size / 1024 / 1024).toFixed(2)} ميجابايت) يتجاوز الحد الأقصى المسموح (10 ميجابايت)`);
        }

        // التحقق من المدة والأبعاد والجودة
        const video = document.createElement('video');
        video.preload = 'metadata';

        video.onloadedmetadata = function() {
            window.URL.revokeObjectURL(video.src);

            // التحقق من المدة (30 ثانية - 10 دقائق)
            const duration = video.duration;
            if (duration < 30 || duration > 600) {
                errors.push(`مدة الفيديو (${Math.floor(duration / 60)}:${Math.floor(duration % 60).toString().padStart(2, '0')}) يجب أن تكون بين 30 ثانية و10 دقائق`);
            }

            // التحقق من الأبعاد (16:9)
            const width = video.videoWidth;
            const height = video.videoHeight;
            const aspectRatio = width / height;
            const targetRatio = 16 / 9;
            const tolerance = 0.1;
            const minRatio = targetRatio * (1 - tolerance);
            const maxRatio = targetRatio * (1 + tolerance);

            if (aspectRatio < minRatio || aspectRatio > maxRatio) {
                errors.push(`أبعاد الفيديو (${width}x${height}) غير متوافقة مع نسبة 16:9. النسبة الحالية: ${aspectRatio.toFixed(2)}`);
            }

            // التحقق من الجودة (HD = 720p+)
            if (height < 720) {
                errors.push(`جودة الفيديو (${height}p) أقل من الحد الأدنى المطلوب (720p HD)`);
            }

            if (errors.length > 0) {
                showErrors('video', errors);
                input.value = '';
                resetPreview('video');
            } else {
                updatePreview('video', 'file', input);
            }
        };

        video.onerror = function() {
            errors.push('فشل تحميل الفيديو. تأكد من أن الملف صالح.');
            showErrors('video', errors);
            input.value = '';
            resetPreview('video');
        };

        video.src = URL.createObjectURL(file);
    }

    // دالة عرض الأخطاء
    function showErrors(type, errors) {
        const errorDiv = document.getElementById(type + '-error');
        errorDiv.classList.remove('d-none');
        errorDiv.innerHTML = '<ul class="mb-0">' +
            errors.map(err => `<li>${err}</li>`).join('') +
            '</ul>';
    }

    // تعيين الحالة الأولية
    document.addEventListener('DOMContentLoaded', function() {
        // تحديث الحالة الأولية للحقول المخفية
        const bannerSource = document.getElementById('banner-source');
        const videoSource = document.getElementById('video-source');
        
        if (bannerSource.dataset.source) {
            bannerSource.value = bannerSource.dataset.source;
        }
        
        if (videoSource.dataset.source) {
            videoSource.value = videoSource.dataset.source;
        }
    });
</script>