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
        <div id="poster-section" class="card shadow mb-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-1 fw-semibold">تحميل بانر</h5>
                <small class="text-muted">الحجم المقبول: 1920x1080 بكسل</small>
            </div>
            <div class="card-body">
                @if ($errors->has('banner'))
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">
                        @foreach ($errors->get('banner') as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

            <!-- تقسيم المحتوى: اليسار للمعاينة واليمين للأزرار -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- منطقة المعاينة -->
                <div class="order-2 lg:order-1">
                        <div id="poster-preview" class="border rounded p-4 text-center d-flex align-items-center justify-content-center poster-preview-box @if($course->banner_url) border-success bg-success bg-opacity-10 @else border-secondary border-2 border-dashed @endif" style="min-height: 300px;">
                            @if($course->banner_url)
                            <div class="text-center w-100">
                                <img src="{{ $course->banner_url }}" alt="Banner" class="img-fluid rounded shadow" style="max-height: 240px; object-fit: contain;">
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

                <!-- أزرار التحميل -->
                <div class="order-1 lg:order-2">
                    <div class="space-y-3">
                            <!-- زر تحميل من الجهاز -->
                            <button type="button" class="btn btn-outline-primary text-end d-flex align-items-center justify-content-start"
                                data-target="file" data-media="poster"
                                onclick="document.getElementById('poster-file-input').click()">
                                <svg class="me-2 text-primary" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <span class="fw-medium">تحميل من الجهاز</span>
                            </button>
                            <input type="file" id="poster-file-input" name="banner" accept=".png,.jpg,.jpeg,.webp" class="d-none">

                            <!-- زر اختر من المكتبة -->
                            <button type="button" class="btn btn-outline-primary text-end d-flex align-items-center justify-content-start"
                                data-target="library" data-media="poster"
                                onclick="selectFromLibrary('poster')">
                                <svg class="me-2 text-primary" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                </svg>
                                <span class="fw-medium">اختر من المكتبة</span>
                            </button>

                            <!-- زر من رابط -->
                            <button type="button" class="btn btn-outline-primary text-end d-flex align-items-center justify-content-start"
                                data-target="link" data-media="poster"
                                onclick="toggleLinkInput('poster')">
                                <svg class="me-2 text-primary" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                </svg>
                                <span class="fw-medium">من رابط</span>
                            </button>

                            <!-- حقل الرابط المخفي -->
                            <div id="poster-link-input-container" class="d-none">
                                <div class="input-group input-group-sm">
                                    <input type="url" id="poster-link-input" placeholder="https://example.com/image.jpg"
                                        class="form-control">
                                    <button type="button" onclick="applyLink('poster')" class="btn btn-primary">
                                        تطبيق
                                    </button>
                                </div>
                            </div>

                            <!-- زر لصق من الحافظة -->
                            <button type="button" class="btn btn-outline-primary text-end d-flex align-items-center justify-content-start"
                                data-target="clipboard" data-media="poster"
                                onclick="pasteFromClipboard('poster')">
                                <svg class="me-2 text-primary" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span class="fw-medium">لصق من الحافظة</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- قسم الفيديو الترويجي -->
        <div id="video-section" class="bg-white p-6 rounded-xl shadow-lg mb-8 border border-gray-100">
            <div class="mb-4 pb-3 border-b">
                <h2 class="text-lg font-semibold text-gray-800">تحميل فيديو ترويجي</h2>
                <p class="text-xs text-gray-500 mt-1">المدة: 3-10 دقائق فقومي بوا</p>
            </div>

            @if ($errors->has('promo_video'))
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->get('promo_video') as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- تقسيم المحتوى: اليسار للمعاينة واليمين للأزرار -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- منطقة المعاينة -->
                <div class="order-2 lg:order-1">
                    <div id="video-preview"
                        class="bg-gray-50 border-2 @if($course->video_url) border-solid border-green-400 bg-green-50 @else border-dashed border-gray-300 @endif rounded-lg p-8 text-center min-h-[300px] flex items-center justify-center transition duration-300">
                        @if($course->video_url)
                        <div class="text-center w-full">
                            <video src="{{ $course->video_url }}" controls class="w-full max-h-60 mx-auto rounded-lg shadow-lg">
                                المتصفح لا يدعم عرض هذا الفيديو
                            </video>
                            <p class="mt-3 text-sm text-green-600 font-medium">تم تحميل الفيديو الترويجي بنجاح</p>
                        </div>
                        @else
                        <div class="text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            <p id="video-status" class="text-gray-600 font-medium">تحميل فيديو ترويجي</p>
                        </div>
                        @endif
                    </div>
                    <div id="video-error" class="hidden mt-3 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm"></div>
                </div>

                <!-- أزرار التحميل -->
                <div class="order-1 lg:order-2">
                    <div class="space-y-3">
                        <!-- زر تحميل من الجهاز -->
                        <button type="button"
                            class="tab-button w-full text-right px-4 py-3 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200 flex items-center gap-3"
                            data-target="file" data-media="video"
                            onclick="document.getElementById('video-file-input').click()">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <span class="font-medium text-gray-700">تحميل من الجهاز</span>
                        </button>
                        <input type="file" id="video-file-input" name="promo_video" accept=".avi,.flv,.webm,.wmv,.mp4" class="hidden">

                        <!-- زر اختر من المكتبة -->
                        <button type="button"
                            class="tab-button w-full text-right px-4 py-3 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200 flex items-center gap-3"
                            data-target="library" data-media="video"
                            onclick="selectFromLibrary('video')">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-gray-700">اختر من المكتبة</span>
                        </button>

                        <!-- زر من رابط -->
                        <button type="button"
                            class="tab-button w-full text-right px-4 py-3 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200 flex items-center gap-3"
                            data-target="link" data-media="video"
                            onclick="toggleLinkInput('video')">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                            <span class="font-medium text-gray-700">من رابط</span>
                        </button>

                        <!-- حقل الرابط المخفي -->
                        <div id="video-link-input-container" class="hidden">
                            <div class="flex gap-2">
                                <input type="url" id="video-link-input" placeholder="https://youtube.com/watch?v=..."
                                    class="flex-grow px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <button type="button" onclick="applyLink('video')"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                                    تطبيق
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden fields for source types -->
        <input type="hidden" id="banner-source" name="banner_source" value="device">
        <input type="hidden" id="video-source" name="video_source" value="device">

        <!-- Submit Buttons -->
        <div class="flex gap-4 justify-between">
            <button type="button" onclick="window.history.back()"
                class="px-6 py-2.5 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition duration-200 font-medium">
                إعادة تعيين
            </button>
            <button type="submit" id="submit-button"
                class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                تحديث
            </button>
        </div>
    </form>

    {{-- Modals --}}
    @include('components.promotion-modals')

    {{-- CSS Styles --}}
    <style>
        .preview-container {
            position: relative;
            height: 300px;
            border: 2px dashed #ddd;
            border-radius: 8px;
            overflow: hidden;
            background-color: #f8f9fa;
        }

        .preview-image,
        .preview-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .preview-placeholder {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            pointer-events: none;
        }
    </style>


    <script>
        // دالة لإدارة التبديل بين طرق الإدخال (Tabs)
        function switchInputMethod(mediaType, targetInput) {
            // تحديد الحاويات الرئيسية
            const sectionId = mediaType + '-section';
            const inputsContainer = document.getElementById(mediaType + '-inputs');

            // إخفاء كل محتويات الإدخال
            inputsContainer.querySelectorAll('.input-content').forEach(el => {
                el.classList.add('hidden');
            });

            // إظهار المحتوى المستهدف
            const targetEl = document.getElementById(mediaType + '-' + targetInput);
            if (targetEl) {
                targetEl.classList.remove('hidden');
            }

            // تحديث حالة الأزرار (Active State)
            document.querySelectorAll(`#${sectionId} .tab-button`).forEach(btn => {
                if (btn.getAttribute('data-target') === targetInput) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });

            // تحديث الحقل المخفي للمصدر
            const sourceField = document.getElementById(mediaType === 'poster' ? 'banner-source' : 'video-source');
            if (sourceField) {
                const sourceValue = targetInput === 'file' ? 'device' :
                    targetInput === 'link' ? 'url' :
                    targetInput === 'library' ? 'library' :
                    targetInput === 'clipboard' ? 'clipboard' : 'device';
                sourceField.value = sourceValue;
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
                    <div class="text-center">
                        <img src="${url}" class="max-w-full max-h-60 mx-auto rounded-lg shadow-lg object-contain" alt="معاينة البوستر">
                        <p class="mt-2 text-sm text-green-600 font-medium">تم تحميل الصورة: ${file.name}</p>
                    </div>
                `;
                    } else if (mediaType === 'video') {
                        previewEl.innerHTML = `
                    <div class="text-center">
                        <video src="${url}" controls class="w-full max-h-60 mx-auto rounded-lg shadow-lg">
                            المتصفح لا يدعم عرض هذا الفيديو
                        </video>
                        <p class="mt-2 text-sm text-green-600 font-medium">تم تحميل الفيديو: ${file.name}</p>
                    </div>
                `;
                    }
                    // تحديث التنسيق لإظهار النجاح
                    previewEl.classList.remove('border-dashed', 'text-gray-500');
                    previewEl.classList.add('border-solid', 'border-green-400', 'bg-green-50');
                } else {
                    resetPreview(mediaType);
                }
            } else if (method === 'link' && value) {
                if (mediaType === 'poster') {
                    // للصور: نحاول عرض الصورة مباشرة
                    previewEl.innerHTML = `
                <div class="text-center">
                    <img src="${value}" 
                         class="max-w-full max-h-60 mx-auto rounded-lg shadow-lg object-contain" 
                         alt="صورة من رابط خارجي"
                         onload="this.parentElement.parentElement.classList.add('border-green-400', 'bg-green-50'); this.parentElement.parentElement.classList.remove('border-dashed', 'text-gray-500')"
                         onerror="this.parentElement.innerHTML='<div class=\\'p-4 bg-red-50 border border-red-200 rounded-lg text-red-700\\'>خطأ في تحميل الصورة من الرابط</div>'">
                    <p class="mt-2 text-sm text-green-600 font-medium">تم ربط الصورة من: ${value}</p>
                </div>
            `;
                } else if (mediaType === 'video') {
                    // للفيديوهات: نتعامل مع روابط يوتيوب وفيميو والروابط المباشرة
                    if (value.includes('youtube.com') || value.includes('youtu.be')) {
                        const videoId = extractYouTubeId(value);
                        if (videoId) {
                            previewEl.innerHTML = `
                        <div class="text-center">
                            <iframe width="100%" height="240" 
                                    src="https://www.youtube.com/embed/${videoId}" 
                                    class="rounded-lg shadow-lg"
                                    frameborder="0" 
                                    allowfullscreen>
                            </iframe>
                            <p class="mt-2 text-sm text-green-600 font-medium">تم ربط فيديو يوتيوب</p>
                        </div>
                    `;
                        }
                    } else if (value.includes('vimeo.com')) {
                        const videoId = extractVimeoId(value);
                        if (videoId) {
                            previewEl.innerHTML = `
                        <div class="text-center">
                            <iframe width="100%" height="240" 
                                    src="https://player.vimeo.com/video/${videoId}" 
                                    class="rounded-lg shadow-lg"
                                    frameborder="0" 
                                    allowfullscreen>
                            </iframe>
                            <p class="mt-2 text-sm text-green-600 font-medium">تم ربط فيديو فيميو</p>
                        </div>
                    `;
                        }
                    } else {
                        // رابط فيديو مباشر
                        previewEl.innerHTML = `
                    <div class="text-center">
                        <video src="${value}" controls class="w-full max-h-60 mx-auto rounded-lg shadow-lg"
                               onload="this.parentElement.parentElement.classList.add('border-green-400', 'bg-green-50')"
                               onerror="this.parentElement.innerHTML='<div class=\\'p-4 bg-red-50 border border-red-200 rounded-lg text-red-700\\'>خطأ في تحميل الفيديو من الرابط</div>'">
                            المتصفح لا يدعم عرض هذا الفيديو
                        </video>
                        <p class="mt-2 text-sm text-green-600 font-medium">تم ربط الفيديو من: ${value}</p>
                    </div>
                `;
                    }
                    previewEl.classList.remove('border-dashed', 'text-gray-500');
                    previewEl.classList.add('border-solid', 'border-green-400', 'bg-green-50');
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

        // دالة لاستخراج معرف فيديو فيميو
        function extractVimeoId(url) {
            const regExp = /(?:vimeo)\.com.*(?:videos|video|channels|)\/([\d]+)/i;
            const match = url.match(regExp);
            return match ? match[1] : null;
        }

        // دالة لإعادة تعيين المعاينة للحالة الافتراضية
        function resetPreview(mediaType) {
            const previewEl = document.getElementById(mediaType + '-preview');
            const defaultText = mediaType === 'poster' ? 'لم يتم اختيار بوستر بعد.' : 'لم يتم اختيار فيديو بعد.';

            previewEl.innerHTML = `<span id="${mediaType}-status">${defaultText}</span>`;
            previewEl.classList.add('border-dashed', 'text-gray-500');
            previewEl.classList.remove('border-solid', 'border-green-400', 'bg-green-50');
        }

        // معالج حدث تغيير الملف للجهاز (بوستر)
        document.getElementById('poster-file-input').addEventListener('change', function(e) {
            validateImageFile(e.target);
        });

        // معالج حدث تغيير الملف للجهاز (فيديو)
        document.getElementById('video-file-input').addEventListener('change', function(e) {
            validateVideoFile(e.target);
        });

        // دالة تبديل عرض حقل الرابط
        function toggleLinkInput(mediaType) {
            const container = document.getElementById(mediaType + '-link-input-container');
            if (container) {
                container.classList.toggle('hidden');
            }
        }

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
            // محاكاة اختيار صورة أو فيديو من المكتبة
            const sampleImages = [
                'https://picsum.photos/400/300?random=1',
                'https://picsum.photos/400/300?random=2',
                'https://picsum.photos/400/300?random=3'
            ];

            const sampleVideos = [
                'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_1mb.mp4',
                'https://www.w3schools.com/html/mov_bbb.mp4'
            ];

            if (mediaType === 'poster') {
                const randomImage = sampleImages[Math.floor(Math.random() * sampleImages.length)];
                const previewEl = document.getElementById('poster-preview');
                previewEl.innerHTML = `
            <div class="text-center">
                <img src="${randomImage}" 
                     class="max-w-full max-h-60 mx-auto rounded-lg shadow-lg object-contain" 
                     alt="صورة من المكتبة">
                <p class="mt-2 text-sm text-green-600 font-medium">تم اختيار صورة من المكتبة</p>
            </div>
        `;
                previewEl.classList.remove('border-dashed', 'text-gray-500');
                previewEl.classList.add('border-solid', 'border-green-400', 'bg-green-50');
            } else if (mediaType === 'video') {
                const randomVideo = sampleVideos[Math.floor(Math.random() * sampleVideos.length)];
                const previewEl = document.getElementById('video-preview');
                previewEl.innerHTML = `
            <div class="text-center">
                <video src="${randomVideo}" controls class="w-full max-h-60 mx-auto rounded-lg shadow-lg">
                    المتصفح لا يدعم عرض هذا الفيديو
                </video>
                <p class="mt-2 text-sm text-green-600 font-medium">تم اختيار فيديو من المكتبة</p>
            </div>
        `;
                previewEl.classList.remove('border-dashed', 'text-gray-500');
                previewEl.classList.add('border-solid', 'border-green-400', 'bg-green-50');
            }
        }

        // دالة لصق من الحافظة - للصور فقط
        async function pasteFromClipboard(mediaType) {
            if (mediaType === 'video') {
                // للفيديو: نحاول قراءة نص فقط (روابط)
                try {
                    const text = await navigator.clipboard.readText();
                    if (isValidUrl(text)) {
                        updatePreview('video', 'link', text);
                    } else {
                        alert('يرجى لصق رابط فيديو صحيح (يوتيوب، فيميو، أو رابط مباشر)');
                    }
                } catch (err) {
                    alert('لا يمكن قراءة محتوى الحافظة. يرجى المحاولة مرة أخرى أو استخدام الرابط المباشر.');
                }
                return;
            }

            // للصور: نحاول قراءة الصور والروابط
            try {
                // محاولة قراءة الحافظة
                const clipboardItems = await navigator.clipboard.read();

                for (const clipboardItem of clipboardItems) {
                    // البحث عن صور في الحافظة
                    for (const type of clipboardItem.types) {
                        if (type.startsWith('image/')) {
                            const blob = await clipboardItem.getType(type);
                            const url = URL.createObjectURL(blob);
                            const previewEl = document.getElementById('poster-preview');
                            previewEl.innerHTML = `
                        <div class="text-center">
                            <img src="${url}" 
                                 class="max-w-full max-h-60 mx-auto rounded-lg shadow-lg object-contain" 
                                 alt="صورة من الحافظة">
                            <p class="mt-2 text-sm text-green-600 font-medium">تم لصق صورة من الحافظة</p>
                        </div>
                    `;
                            previewEl.classList.remove('border-dashed', 'text-gray-500');
                            previewEl.classList.add('border-solid', 'border-green-400', 'bg-green-50');
                            return;
                        }
                    }

                    // إذا لم نجد صورة، نحاول قراءة نص (قد يكون رابط صورة)
                    if (clipboardItem.types.includes('text/plain')) {
                        const text = await clipboardItem.getType('text/plain');
                        const textContent = await text.text();

                        // التحقق من كون النص رابط صالح
                        if (isValidUrl(textContent)) {
                            updatePreview('poster', 'link', textContent);
                            return;
                        }
                    }
                }

                // إذا لم نجد محتوى مناسب
                alert('لا يوجد صورة أو رابط صورة في الحافظة');

            } catch (err) {
                // في حالة فشل قراءة الحافظة، نحاول قراءة النص فقط
                try {
                    const text = await navigator.clipboard.readText();
                    if (isValidUrl(text)) {
                        updatePreview('poster', 'link', text);
                    } else {
                        alert('لا يوجد رابط صورة صحيح في الحافظة');
                    }
                } catch (textErr) {
                    alert('لا يمكن قراءة محتوى الحافظة. يرجى المحاولة مرة أخرى أو استخدام الرابط المباشر.');
                }
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
            errorDiv.classList.add('hidden');
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
            const maxSize = 3 * 1024 * 1024; // 3MB
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
            errorDiv.classList.add('hidden');
            errorDiv.innerHTML = '';

            if (!file) {
                resetPreview('video');
                return;
            }

            const errors = [];

            // التحقق من النوع
            const allowedTypes = ['video/x-msvideo', 'video/x-flv', 'video/webm', 'video/x-ms-wmv'];
            const fileExtension = file.name.split('.').pop().toLowerCase();
            const allowedExtensions = ['avi', 'flv', 'webm', 'wmv', 'mp4'];

            if (!allowedExtensions.includes(fileExtension)) {
                errors.push('نوع الملف غير مسموح. الأنواع المسموحة: AVI, FLV, WEBM, WMV, MP4');
            }

            // التحقق من الحجم (10MB)
            const maxSize = 10 * 1024 * 1024; // 10MB
            if (file.size > maxSize) {
                errors.push(`حجم الملف (${(file.size / 1024 / 1024).toFixed(2)} ميجابايت) يتجاوز الحد الأقصى المسموح (10 ميجابايت)`);
            }

            // التحقق من المدة والأبعاد والجودة
            const video = document.createElement('video');
            video.preload = 'metadata';

            video.onloadedmetadata = function() {
                window.URL.revokeObjectURL(video.src);

                // التحقق من المدة (1-10 دقائق)
                const duration = video.duration;
                if (duration < 30 || duration > 600) {
                    errors.push(`مدة الفيديو (${Math.floor(duration / 60)}:${Math.floor(duration % 60).toString().padStart(2, '0')}) يجب أن تكون بين 1 و10 دقائق`);
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
            errorDiv.classList.remove('hidden');
            errorDiv.innerHTML = '<ul class="list-disc list-inside space-y-1">' +
                errors.map(err => `<li>${err}</li>`).join('') +
                '</ul>';
        }

        // تعيين الحالة الأولية
        // لا نحتاج لاستدعاء switchInputMethod لأن الأزرار تعمل مباشرة
        
        // إذا كان البانر أو الفيديو موجود، نحدث الحد والخلفية
        document.addEventListener('DOMContentLoaded', function() {
            const posterPreview = document.getElementById('poster-preview');
            const videoPreview = document.getElementById('video-preview');
            
            // تحديث الحالة الأولية للحقول المخفية
            @if($course->banner_url)
                document.getElementById('banner-source').value = '{{ $course->banner_source ?? "device" }}';
            @endif
            
            @if($course->video_url)
                document.getElementById('video-source').value = '{{ $course->video_source ?? "device" }}';
            @endif
        });
    </script>
</div>