// الانتظار حتى يتم تحميل محتوى الصفحة بالكامل قبل تشغيل السكربت
window.addEventListener('DOMContentLoaded', () => {

    // ---------------------------------------------
    // 1. تعريف العناصر (DOM Elements)
    // ---------------------------------------------

    // عناصر البانر (الصورة المصغرة)
    const bannerFileInput = document.getElementById('bannerFileInput');
    const bannerPreview = document.getElementById('bannerPreview');
    const bannerPlaceholder = document.getElementById('bannerPlaceholder');
    const bannerSourceInput = document.getElementById('bannerSource');
    const bannerUrlInputHidden = document.getElementById('bannerUrl');
    const uploadBannerBtn = document.getElementById('uploadBannerBtn');
    const libraryBannerBtn = document.getElementById('libraryBannerBtn');
    const urlBannerBtn = document.getElementById('urlBannerBtn');
    const pasteBannerBtn = document.getElementById('pasteBannerBtn');

    // عناصر نموذج URL البانر
    const urlBannerModalEl = document.getElementById('urlBannerModal');
    const bannerUrlInputText = document.getElementById('bannerUrlInput');
    const submitBannerUrlBtn = document.getElementById('submitBannerUrl');
    // تهيئة نموذج URL البانر باستخدام Bootstrap
    const urlBannerModal = new bootstrap.Modal(urlBannerModalEl);

    // عناصر نموذج مكتبة البانر
    const libraryBannerModalEl = document.getElementById('libraryBannerModal');
    // تهيئة نموذج مكتبة البانر باستخدام Bootstrap
    const libraryBannerModal = new bootstrap.Modal(libraryBannerModalEl);

    // عناصر الفيديو (البرومو)
    const videoFileInput = document.getElementById('videoFileInput');
    const videoPreview = document.getElementById('videoPreview');
    const videoPlaceholder = document.getElementById('videoPlaceholder');
    const videoSourceInput = document.getElementById('videoSource');
    const videoUrlInputHidden = document.getElementById('videoUrl');
    const uploadVideoBtn = document.getElementById('uploadVideoBtn');
    const libraryVideoBtn = document.getElementById('libraryVideoBtn');
    const urlVideoBtn = document.getElementById('urlVideoBtn');

    // عناصر نموذج URL الفيديو
    const urlVideoModalEl = document.getElementById('urlVideoModal');
    const videoUrlInputText = document.getElementById('videoUrlInput');
    const submitVideoUrlBtn = document.getElementById('submitVideoUrl');
    // تهيئة نموذج URL الفيديو باستخدام Bootstrap
    const urlVideoModal = new bootstrap.Modal(urlVideoModalEl);

    // عناصر نموذج مكتبة الفيديو
    const libraryVideoModalEl = document.getElementById('libraryVideoModal');
    // تهيئة نموذج مكتبة الفيديو باستخدام Bootstrap
    const libraryVideoModal = new bootstrap.Modal(libraryVideoModalEl);

    // ---------------------------------------------
    // 2. دوال مساعدة (Helper Functions)
    // ---------------------------------------------

    /**
     * تحديث معاينة الميديا (صورة أو فيديو) وإظهار/إخفاء الحامل النصي
     * @param {HTMLElement} mediaElement - عنصر الـ <img> أو الـ <video> للمعاينة
     * @param {HTMLElement} placeholderElement - عنصر الحامل النصي (Placeholder)
     * @param {string|null} url - الرابط الجديد للميديا
     * @param {string} type - نوع الميديا ('image' أو 'video')
    */
    const updateMediaPreview = (mediaElement, placeholderElement, url, type) => {
        console.log([url])
        if (url) {
            mediaElement.src = url;
            mediaElement.style.display = 'block';
            placeholderElement.style.display = 'none';
            // إذا كان فيديو، نتأكد من إعادة تحميله
            if (type === 'video') {
                mediaElement.load();
            }
        } else {
            // إذا كان الرابط فارغاً، نعرض الحامل النصي
            mediaElement.src = '';
            mediaElement.style.display = 'none';
            placeholderElement.style.display = 'block';
        }
    };

    /**
     * مسح قيم حقول الإدخال الخاصة بالبانر
     */
    const resetBannerInputs = () => {
        bannerFileInput.value = ''; // مسح الملف المختار
        bannerUrlInputHidden.value = ''; // مسح رابط الـ URL المخفي
    };

    /**
     * مسح قيم حقول الإدخال الخاصة بالفيديو
     */
    const resetVideoInputs = () => {
        videoFileInput.value = ''; // مسح الملف المختار
        videoUrlInputHidden.value = ''; // مسح رابط الـ URL المخفي
    };

    // ---------------------------------------------
    // 3. لوجيك البانر (Banner Logic)
    // ---------------------------------------------

    // 3.1. الرفع من الجهاز
    uploadBannerBtn.addEventListener('click', () => {
        console.log('uploadBannerBtn clicked')
        bannerFileInput.click();
    });

    // معالجة اختيار الملف من الجهاز
    bannerFileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const fileUrl = URL.createObjectURL(file);
            updateMediaPreview(bannerPreview, bannerPlaceholder, fileUrl, 'image');
            bannerSourceInput.value = 'file'; // تحديد المصدر كملف
            bannerUrlInputHidden.value = ''; // مسح حقل URL إذا تم اختيار ملف
        } else if (!bannerPreview.src || bannerPreview.src === window.location.href) {
            // إذا لم يتم اختيار ملف وكانت المعاينة فارغة، نعرض الحامل النصي
            updateMediaPreview(bannerPreview, bannerPlaceholder, null, 'image');
        }
    });

    // 3.2. الإدخال من رابط URL
    urlBannerBtn.addEventListener('click', () => {
        urlBannerModal.show();
    });

    submitBannerUrlBtn.addEventListener('click', () => {
        const url = bannerUrlInputText.value.trim();
        if (url) {
            resetBannerInputs(); // مسح أي ملفات تم اختيارها مسبقاً
            updateMediaPreview(bannerPreview, bannerPlaceholder, url, 'image');
            bannerSourceInput.value = 'url'; // تحديد المصدر كرابط
            bannerUrlInputHidden.value = url; // حفظ الرابط في الحقل المخفي
            urlBannerModal.hide();
        }
    });
    // مسح حقل الإدخال النصي عند إغلاق المودال
    urlBannerModalEl.addEventListener('hidden.bs.modal', () => {
        bannerUrlInputText.value = '';
    });

    // 3.3. الاختيار من المكتبة (Mock/Placeholder Logic)
    libraryBannerBtn.addEventListener('click', () => {
        // افتح مودال المكتبة
        libraryBannerModal.show();
        // ** ملاحظة: هنا يتم افتراض أنك ستقوم بتحميل محتوى المكتبة ديناميكياً **
        // ** (Mock Logic) - يتم هنا وضع منطق وهمي لاختيار صورة بعد فتح المودال **
        // ** يجب استبدال هذا بمنطق حقيقي للتعامل مع صور المكتبة **

        const libraryContent = document.getElementById('bannerLibraryContent');
        // مثال بسيط: عند فتح المكتبة، نقوم بتحميل بعض الصور الوهمية
        if (!libraryContent.hasAttribute('data-loaded')) {
            libraryContent.innerHTML = `
                <div class="col-md-4 mb-3">
                    <img src="https://placehold.co/600x400/007bff/white?text=Library+Image+1" class="img-fluid rounded border p-1 cursor-pointer library-image" data-url="https://placehold.co/600x400/007bff/white?text=Library+Image+1" alt="Library Image 1">
                </div>
                <div class="col-md-4 mb-3">
                    <img src="https://placehold.co/600x400/28a745/white?text=Library+Image+2" class="img-fluid rounded border p-1 cursor-pointer library-image" data-url="https://placehold.co/600x400/28a745/white?text=Library+Image+2" alt="Library Image 2">
                </div>
                <div class="col-md-4 mb-3">
                    <img src="https://placehold.co/600x400/dc3545/white?text=Library+Image+3" class="img-fluid rounded border p-1 cursor-pointer library-image" data-url="https://placehold.co/600x400/dc3545/white?text=Library+Image+3" alt="Library Image 3">
                </div>
            `;
            libraryContent.setAttribute('data-loaded', 'true');
        }

        // إضافة مستمعي الأحداث لصور المكتبة
        libraryContent.querySelectorAll('.library-image').forEach(img => {
            img.onclick = () => {
                const url = img.getAttribute('data-url');
                resetBannerInputs(); // مسح أي ملفات تم اختيارها مسبقاً
                updateMediaPreview(bannerPreview, bannerPlaceholder, url, 'image');
                bannerSourceInput.value = 'library'; // تحديد المصدر كمكتبة
                bannerUrlInputHidden.value = url; // حفظ الرابط في الحقل المخفي
                libraryBannerModal.hide(); // إغلاق المودال
            };
        });
    });

    // 3.4. اللصق من الحافظة (Paste from Clipboard)
    pasteBannerBtn.addEventListener('click', async () => {
        try {
            // استخدام واجهة برمجة تطبيقات الحافظة الحديثة
            const items = await navigator.clipboard.read();
            for (const item of items) {
                if (item.types.includes('image/png')) {
                    const blob = await item.getType('image/png');
                    const fileUrl = URL.createObjectURL(blob);
                    resetBannerInputs(); // مسح أي ملفات تم اختيارها مسبقاً
                    updateMediaPreview(bannerPreview, bannerPlaceholder, fileUrl, 'image');
                    bannerSourceInput.value = 'file'; // التعامل معها كملف مؤقت تم رفعه (لأغراض المعاينة)
                    bannerUrlInputHidden.value = '';
                    // ** ملاحظة: عند الإرسال، ستحتاج إلى إرسال البيانات المُلصقة كملف إلى الخادم **
                    // ** هذا يتطلب منطقاً إضافياً في معالجة النموذج (Form Submission) **
                    console.log('Image pasted from clipboard successfully.');
                    return;
                }
            }
            // إذا لم يتم العثور على صورة في الحافظة
            alert('لم يتم العثور على صورة في الحافظة. يرجى التأكد من نسخ صورة.');

        } catch (err) {
            // التعامل مع الأخطاء (مثل عدم وجود إذن للوصول للحافظة)
            console.error('Failed to read clipboard contents: ', err);
            // توجيه المستخدم للصق مباشرة في حقل
            alert('فشل الوصول إلى الحافظة. يرجى التأكد من منح الإذن أو حاول لصق الصورة مباشرة في حقل إدخال (غير متوفر في هذا التصميم).');
        }
    });


    // ---------------------------------------------
    // 4. لوجيك الفيديو (Video Logic)
    // ---------------------------------------------

    // 4.1. الرفع من الجهاز
    uploadVideoBtn.addEventListener('click', () => {
        videoFileInput.click();
    });

    // معالجة اختيار الملف من الجهاز
    videoFileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const fileUrl = URL.createObjectURL(file);
            updateMediaPreview(videoPreview, videoPlaceholder, fileUrl, 'video');
            videoSourceInput.value = 'file'; // تحديد المصدر كملف
            videoUrlInputHidden.value = ''; // مسح حقل URL إذا تم اختيار ملف
        } else if (!videoPreview.src || videoPreview.src === window.location.href) {
            // إذا لم يتم اختيار ملف وكانت المعاينة فارغة، نعرض الحامل النصي
            updateMediaPreview(videoPreview, videoPlaceholder, null, 'video');
        }
    });

    // 4.2. الإدخال من رابط URL
    urlVideoBtn.addEventListener('click', () => {
        urlVideoModal.show();
    });

    submitVideoUrlBtn.addEventListener('click', () => {
        const url = videoUrlInputText.value.trim();
        if (url) {
            resetVideoInputs(); // مسح أي ملفات تم اختيارها مسبقاً
            updateMediaPreview(videoPreview, videoPlaceholder, url, 'video');
            videoSourceInput.value = 'url'; // تحديد المصدر كرابط
            videoUrlInputHidden.value = url; // حفظ الرابط في الحقل المخفي
            urlVideoModal.hide();
        }
    });

    // مسح حقل الإدخال النصي عند إغلاق المودال
    urlVideoModalEl.addEventListener('hidden.bs.modal', () => {
        videoUrlInputText.value = '';
    });

    // 4.3. الاختيار من المكتبة (Mock/Placeholder Logic)
    libraryVideoBtn.addEventListener('click', () => {
        libraryVideoModal.show();

        const libraryContent = document.getElementById('videoLibraryContent');
        // مثال بسيط: عند فتح المكتبة، نقوم بتحميل بعض الفيديوهات الوهمية
        if (!libraryContent.hasAttribute('data-loaded')) {
            libraryContent.innerHTML = `
                <div class="col-12 p-3 text-center">
                    <p class="text-muted">{{__('courses.mock_library_content')}}</p>
                    <button type="button" class="btn btn-sm btn-info select-mock-video" data-url="https://www.w3schools.com/html/mov_bbb.mp4">
                        {{__('courses.select_mock_video')}}
                    </button>
                    <p class="mt-2 text-sm text-warning">الرجاء ملاحظة: هذا فيديو وهمي. يجب استبدال الرابط برابط فيديو حقيقي من مكتبتك.</p>
                </div>
            `;
            libraryContent.setAttribute('data-loaded', 'true');
        }

        // إضافة مستمعي الأحداث لزر اختيار الفيديو
        libraryContent.querySelectorAll('.select-mock-video').forEach(btn => {
            btn.onclick = () => {
                const url = btn.getAttribute('data-url');
                resetVideoInputs(); // مسح أي ملفات تم اختيارها مسبقاً
                updateMediaPreview(videoPreview, videoPlaceholder, url, 'video');
                videoSourceInput.value = 'library'; // تحديد المصدر كمكتبة
                videoUrlInputHidden.value = url; // حفظ الرابط في الحقل المخفي
                libraryVideoModal.hide(); // إغلاق المودال
            };
        });
    });

    // ---------------------------------------------
    // 5. تهيئة أولية
    // ---------------------------------------------

    // تهيئة حالة العرض الأولية بناءً على القيم الموجودة في النموذج (المحتملة من Blade)
    // التأكد من إظهار المعاينة إذا كان هناك رابط موجود مسبقاً

    if (bannerUrlInputHidden.value) {
        updateMediaPreview(bannerPreview, bannerPlaceholder, bannerUrlInputHidden.value, 'image');
    }

    if (videoUrlInputHidden.value) {
        updateMediaPreview(videoPreview, videoPlaceholder, videoUrlInputHidden.value, 'video');
    }
});
