/**
 * Promotion Upload Handler - Built from scratch
 * Handles banner and promo video upload with multiple input methods
 */

(function() {
    'use strict';
    
    console.log('🚀 Promotion Upload Handler - Loading...');
    
    // Get language and direction from HTML
    const htmlTag = document.documentElement;
    const appLang = htmlTag.getAttribute('lang') || 'ar';
    const isRtl = htmlTag.getAttribute('dir') === 'rtl';
    
    // Translation messages
    const messages = {
        ar: {
            invalidImage: 'الرجاء اختيار ملف صورة صالح',
            invalidVideo: 'الرجاء اختيار ملف فيديو صالح',
            fileTooLarge: 'حجم الملف كبير جداً',
            videoDuration: 'مدة الفيديو يجب أن تكون بين 1 و 10 دقائق',
            clipboardError: 'فشل في قراءة الحافظة'
        },
        en: {
            invalidImage: 'Please select a valid image file',
            invalidVideo: 'Please select a valid video file',
            fileTooLarge: 'File size is too large',
            videoDuration: 'Video duration should be between 1 and 10 minutes',
            clipboardError: 'Failed to read clipboard'
        }
    };
    
    const msg = messages[appLang] || messages.ar;
    
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    function init() {
        console.log('✅ DOM Ready - Initializing handlers...');
        
        // Initialize Banner Upload
        initBannerUpload();
        
        // Initialize Video Upload
        initVideoUpload();
        
        console.log('✅ Promotion Upload Handler - Ready!');
    }
    
    /**
     * Initialize Banner Upload
     */
    function initBannerUpload() {
        const fileInput = document.getElementById('bannerFileInput');
        const preview = document.getElementById('bannerPreview');
        const placeholder = document.getElementById('bannerPlaceholder');
        const sourceInput = document.getElementById('bannerSource');
        const urlInput = document.getElementById('bannerUrlInput');
        
        // Setup button actions
        setupAction('[data-action="upload-banner"]', () => {
            console.log('📸 Upload Banner clicked');
            fileInput.click();
        });
        
        setupAction('[data-action="url-banner"]', () => {
            console.log('🔗 URL Banner clicked');
            openBannerUrlModal();
        });
        
        setupAction('[data-action="paste-banner"]', async () => {
            console.log('📋 Paste Banner clicked');
            await pasteFromClipboard(fileInput);
        });
        
        // File input change handler
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            
            console.log('📁 Banner file selected:', file.name, file.size, 'bytes');
            
            // Validate
            if (!validateImage(file)) {
                fileInput.value = '';
                return;
            }
            
            // Preview
            previewImage(file, preview, placeholder);
            
            // Set source
            if (sourceInput) sourceInput.value = 'device';
            
            console.log('✅ Banner ready for upload');
        });
    }
    
    /**
     * Initialize Video Upload
     */
    function initVideoUpload() {
        const fileInput = document.getElementById('videoFileInput');
        const preview = document.getElementById('videoPreview');
        const placeholder = document.getElementById('videoPlaceholder');
        const sourceInput = document.getElementById('videoSource');
        const urlInput = document.getElementById('videoUrlInput');
        
        // Setup button actions
        setupAction('[data-action="upload-video"]', () => {
            console.log('🎬 Upload Video clicked');
            fileInput.click();
        });
        
        setupAction('[data-action="url-video"]', () => {
            console.log('🔗 URL Video clicked');
            openVideoUrlModal();
        });
        
        // File input change handler
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            console.log(this)
            if (!file) return;
            
            console.log('📁 Video file selected:', file.name, file.size, 'bytes');
            
            // Validate
            if (!validateVideo(file)) {
                fileInput.value = '';
                return;
            }
            
            // Preview
            previewVideo(file, preview, placeholder);
            
            // Set source
            if (sourceInput) sourceInput.value = 'device';
            
            console.log('✅ Video ready for upload');
        });
    }
    
    /**
     * Setup button action with event delegation
     */
    function setupAction(selector, callback) {
        document.addEventListener('click', function(e) {
            const target = e.target.closest(selector);
            if (target) {
                e.preventDefault();
                e.stopPropagation();
                callback(e);
            }
        });
    }
    
    /**
     * Validate Image File
     */
    function validateImage(file) {
        // Check type
        if (!file.type.startsWith('image/')) {
            alert(msg.invalidImage);
            return false;
        }
        
        // Check size (max 5MB)
        const maxSize = 5 * 1024 * 1024;
        if (file.size > maxSize) {
            alert(msg.fileTooLarge + ' (max 5MB)');
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate Video File
     */
    function validateVideo(file) {
        // Check type
        if (!file.type.startsWith('video/')) {
            alert(msg.invalidVideo);
            return false;
        }
        
        // Check size (max 100MB)
        const maxSize = 100 * 1024 * 1024;
        if (file.size > maxSize) {
            alert(msg.fileTooLarge + ' (max 100MB)');
            return false;
        }
        
        return true;
    }
    
    /**
     * Preview Image
     */
    function previewImage(file, preview, placeholder) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            placeholder.classList.add('d-none');
            console.log('✅ Image preview loaded');
        };
        
        reader.onerror = function() {
            console.error('❌ Error reading file');
        };
        
        reader.readAsDataURL(file);
    }
    
    /**
     * Preview Video
     */
    function previewVideo(file, preview, placeholder) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            placeholder.classList.add('d-none');
            
            // Validate duration after metadata loads
            preview.addEventListener('loadedmetadata', function() {
                const duration = preview.duration;
                const minDuration = 0.5 * 60; // 30 seconds
                const maxDuration = 10 * 60; // 10 minutes
                
                console.log('📹 Video duration:', duration, 'seconds');
                
                if (duration < minDuration || duration > maxDuration) {
                    alert(msg.videoDuration);
                    preview.src = '';
                    preview.classList.add('d-none');
                    placeholder.classList.remove('d-none');
                    document.getElementById('videoFileInput').value = '';
                } else {
                    console.log('✅ Video preview loaded');
                }
            }, { once: true });
        };
        
        reader.onerror = function() {
            console.error('❌ Error reading file');
        };
        
        reader.readAsDataURL(file);
    }
    
    /**
     * Open Banner URL Modal
     */
    function openBannerUrlModal() {
        const modal = new bootstrap.Modal(document.getElementById('bannerUrlModal'));
        modal.show();
        
        document.getElementById('submitBannerUrl').onclick = function() {
            const url = document.getElementById('bannerUrlModalInput').value.trim();
            if (url) {
                const preview = document.getElementById('bannerPreview');
                const placeholder = document.getElementById('bannerPlaceholder');
                const sourceInput = document.getElementById('bannerSource');
                const urlInput = document.getElementById('bannerUrlInput');
                
                preview.src = url;
                preview.classList.remove('d-none');
                placeholder.classList.add('d-none');
                
                if (sourceInput) sourceInput.value = 'url';
                if (urlInput) urlInput.value = url;
                
                modal.hide();
                console.log('✅ Banner loaded from URL');
            }
        };
    }
    
    /**
     * Open Video URL Modal
     */
    function openVideoUrlModal() {
        const modal = new bootstrap.Modal(document.getElementById('videoUrlModal'));
        modal.show();
        
        document.getElementById('submitVideoUrl').onclick = function() {
            const url = document.getElementById('videoUrlModalInput').value.trim();
            if (url) {
                const preview = document.getElementById('videoPreview');
                const placeholder = document.getElementById('videoPlaceholder');
                const sourceInput = document.getElementById('videoSource');
                const urlInput = document.getElementById('videoUrlInput');
                
                preview.src = url;
                preview.classList.remove('d-none');
                placeholder.classList.add('d-none');
                
                if (sourceInput) sourceInput.value = 'url';
                if (urlInput) urlInput.value = url;
                
                modal.hide();
                console.log('✅ Video loaded from URL');
            }
        };
    }
    
    /**
     * Paste from Clipboard
     */
    async function pasteFromClipboard(fileInput) {
        try {
            const clipboardItems = await navigator.clipboard.read();
            
            for (const item of clipboardItems) {
                const imageType = item.types.find(type => type.startsWith('image/'));
                
                if (imageType) {
                    const blob = await item.getType(imageType);
                    const file = new File([blob], 'clipboard-image.png', { type: blob.type });
                    
                    // Create DataTransfer to set file input
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fileInput.files = dataTransfer.files;
                    
                    // Trigger change event
                    fileInput.dispatchEvent(new Event('change', { bubbles: true }));
                    
                    console.log('✅ Image pasted from clipboard');
                    return;
                }
            }
            
            alert(msg.clipboardError);
        } catch (err) {
            console.error('❌ Clipboard error:', err);
            alert(msg.clipboardError);
        }
    }
    
})();
