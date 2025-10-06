document.addEventListener('DOMContentLoaded', function() {
    // Media Library Logic
    const bannerModal = document.getElementById('uploadBannerModal');
    const promoVideoModal = document.getElementById('uploadPromoVideoModal');

    if (bannerModal) {
        bannerModal.addEventListener('shown.bs.modal', () => loadMediaLibrary('images', 'banner'));
    }

    if (promoVideoModal) {
        promoVideoModal.addEventListener('shown.bs.modal', () => loadMediaLibrary('videos', 'promo_video'));
    }

    function loadMediaLibrary(type, context) {
        const libraryGrid = document.querySelector(`#${context}MediaLibraryGrid`);
        const loadingSpinner = document.querySelector(`#${context}LoadingSpinner`);
        const noMediaMessage = document.querySelector(`#${context}NoMediaMessage`);

        if (!libraryGrid || !loadingSpinner || !noMediaMessage) return;

        loadingSpinner.style.display = 'block';
        libraryGrid.innerHTML = '';
        noMediaMessage.style.display = 'none';

        fetch(`/instructor/media-library?type=${type}`)
            .then(response => response.json())
            .then(data => {
                loadingSpinner.style.display = 'none';
                if (data.success && data.files.length > 0) {
                    data.files.forEach(file => {
                        const col = document.createElement('div');
                        col.className = 'col-3 mb-3';
                        const isSelected = (context === 'banner' && file.url === document.getElementById('banner_preview').src) || (context === 'promo_video' && file.url === document.getElementById('promo_video_preview_src').src);

                        let mediaElement;
                        if (type === 'images') {
                            mediaElement = `<img src="${file.url}" class="img-fluid rounded" alt="${file.name}" style="cursor: pointer; aspect-ratio: 16/9; object-fit: cover;">`;
                        } else {
                            mediaElement = `<video muted class="img-fluid rounded" style="cursor: pointer; aspect-ratio: 16/9; object-fit: cover;"><source src="${file.url}" type="video/mp4"></video>`;
                        }

                        col.innerHTML = `
                            <div class="media-library-item rounded overflow-hidden position-relative ${isSelected ? 'selected' : ''}" data-url="${file.url}">
                                ${mediaElement}
                                <div class="position-absolute top-0 start-0 p-1"><i class="fas fa-check-circle text-primary"></i></div>
                            </div>`;
                        libraryGrid.appendChild(col);
                    });
                } else if (data.needs_setup) {
                    noMediaMessage.innerHTML = `
                        <p>${data.message}</p>
                        <button class="btn btn-primary" onclick="createMediaLibrarySystem()">${data.button_text}</button>`;
                    noMediaMessage.style.display = 'block';
                } else {
                    noMediaMessage.innerHTML = `<p>${data.message || 'No media files found.'}</p>`;
                    noMediaMessage.style.display = 'block';
                }
            })
            .catch(error => {
                loadingSpinner.style.display = 'none';
                noMediaMessage.innerHTML = `<p>Error loading media library.</p>`;
                noMediaMessage.style.display = 'block';
                console.error('Error:', error);
            });
    }

    window.createMediaLibrarySystem = function() {
        const toastContainer = document.getElementById('toastContainer');
        if (!toastContainer) return;

        showToast(`<strong>Setup in progress:</strong><br>Creating media library system...<br>Please wait...`, 'info');

        fetch('/instructor/setup-media-library', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(`<strong>Setup successful!</strong><br>Media library created successfully.`, 'success');
                    setTimeout(() => {
                        const activeModal = document.querySelector('.modal.show');
                        if (activeModal) {
                            const context = activeModal.id.includes('Banner') ? 'banner' : 'promo_video';
                            const type = context === 'banner' ? 'images' : 'videos';
                            loadMediaLibrary(type, context);
                        }
                    }, 1000);
                } else {
                    showToast(`<strong>Setup failed:</strong><br>${data.message || 'Unknown error'}`, 'error');
                }
            })
            .catch(error => {
                console.error('Setup error:', error);
                showToast(`<strong>Setup error:</strong><br>Failed to create media library.`, 'error');
            });
    }

    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(event) {
            if (event.target.classList.contains('media-library-item')) {
                const url = event.target.closest('.media-library-item').dataset.url;
                const context = modal.id.includes('Banner') ? 'banner' : 'promo_video';
                handleMediaSelection(url, context);
                const bsModal = bootstrap.Modal.getInstance(modal);
                bsModal.hide();
            }
        });
    });

    function handleMediaSelection(url, context) {
        if (context === 'banner') {
            document.getElementById('banner_url').value = url;
            document.getElementById('banner_preview').src = url;
            document.getElementById('banner_preview_wrapper').style.display = 'block';
            document.getElementById('banner_source').value = 'library';
        } else if (context === 'promo_video') {
            document.getElementById('promo_video_url').value = url;
            const videoPreview = document.getElementById('promo_video_preview');
            const videoSource = document.getElementById('promo_video_preview_src');
            videoSource.src = url;
            videoPreview.load();
            document.getElementById('promo_video_preview_wrapper').style.display = 'block';
            document.getElementById('video_source').value = 'library';
        }
    }

    window.handleUrlSubmit = function(context) {
        const modalId = context === 'banner' ? 'uploadBannerModal' : 'uploadPromoVideoModal';
        const urlInputId = context === 'banner' ? 'imageUrl' : 'videoUrl';
        const url = document.getElementById(urlInputId).value;
        if (url) {
            handleMediaSelection(url, context);
            const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
            modal.hide();
        }
    }

    window.handleFilePaste = function(event, context) {
        const items = (event.clipboardData || event.originalEvent.clipboardData).items;
        for (let item of items) {
            if (item.type.indexOf('image') === 0) {
                const blob = item.getAsFile();
                const reader = new FileReader();
                reader.onload = function(e) {
                    handleMediaSelection(e.target.result, context);
                };
                reader.readAsDataURL(blob);
                const modal = bootstrap.Modal.getInstance(document.getElementById(context === 'banner' ? 'uploadBannerModal' : 'uploadPromoVideoModal'));
                modal.hide();
            }
        }
    }

    window.handleFileUpload = function(event, context) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                handleMediaSelection(e.target.result, context);
            }
            reader.readAsDataURL(file);
            const modal = bootstrap.Modal.getInstance(document.getElementById(context === 'banner' ? 'uploadBannerModal' : 'uploadPromoVideoModal'));
            modal.hide();
        }
    }
});
