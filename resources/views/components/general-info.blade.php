@props([
'course',
'categories',
])
<div>
    <!-- Toast Container -->
    <div id="toastContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>
    <form action="{{route('instructor.courses.update.general-info', [$course->id])}}" method="POST" enctype="multipart/form-data">

        @csrf
        @method('PUT')
        {{route('instructor.courses.update.general-info', [$course->id])}}
        <div class="input-group mb-3">
            <label for="title" class="input-group-text">{{__('courses.course_title')}}</label>
            <input type="text" class="form-control" id="title" name="title" value="{{$course->title}}">
        </div>
        <div class="input-group mb-3">
            <label for="subtitle" class="input-group-text">{{__('courses.subtitle')}}</label>
            <input type="text" class="form-control" id="subtitle" name="subtitle" value="{{$course->subtitle}}">
        </div>
        <div class="form-floating mb-3">
            <textarea name="short_description" rows="3" placeholder="{{__('courses.enter_short_description')}}" id="short_description"
                class="form-control">{{$course->short_description}}</textarea>
            <label for="short_description">{{__('courses.short_description')}}</label>
        </div>
        <div class="form-floating mb-3">
            <textarea name="description" rows="3" placeholder="{{__('courses.enter_course_description')}}" id="description" class="form-control">{{$course->description}}</textarea>
            <label for="description">{{__('courses.course_description')}}</label>
        </div>

        <div class="row">
            <div class="col col-md-6">
                <div class="input-group mb-3">
                    <label for="category" class="input-group-text">{{__('courses.category')}}</label>
                    <select name="category" id="category" class="form-select">
                        <option value="">{{__('courses.select_category')}}</option>
                        @foreach ($categories as $category)
                        <option value="{{$category->id}}" @if ($category->id == $course->category_id) selected @endif>{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col col-md-6">
                <div class="input-group mb-3">
                    <label for="language" class="input-group-text">{{__('courses.language')}}</label>
                    <select name="language" id="language" class="form-select">
                        <option value="">{{__('courses.select_language')}}</option>
                        @foreach (__('courses.languages') as $key => $language)
                        <option value="{{$key}}" @if ($key==$course->language) selected @endif>{{$language}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col col-md-6">
                <div class="input-group mb-3">
                    <label for="level" class="input-group-text">{{__('courses.level')}}</label>
                    <select name="level" id="level" class="form-select">
                        <option value="">{{__('courses.select_level')}}</option>
                        @foreach (__('courses.audience_levels') as $key => $level)
                        <option value="{{$key}}" @if ($key==$course->level) selected @endif>{{$level}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col col-md-6">
                <div class="input-group mb-3">
                    <label for="language" class="input-group-text">{{__('courses.price')}}</label>
                    <input type="number" name="price" id="price" class="form-control" value="{{$course->price}}">
                    <label data-bs-toggle="tooltip" data-bstitle="{{__('courses.price_input_tips')}}" class="input-group-text"><i class="fa fa-info-circle"></i></label>
                </div>
            </div>
        </div>

        {{-- Banner and thumbnail --}}
        <fieldset class="my-3 pt-4 pb-0">
            <legend>{{__('courses.announcement_media')}}</legend>

            {{-- Banner Upload Section --}}
            <div class="row mb-4">
                <div class="col col-md-4 p-3">
                    <h6 class="mb-3">{{__('courses.upload_banner')}}</h6>
                    <button type="button" class="btn btn-outline-primary w-100 mb-2" id="uploadBannerBtn">
                        <i class="fa fa-upload me-2"></i>{{__('courses.upload_from_device')}}
                    </button>
                    <input type="file" name="banner" id="bannerFileInput" class="d-none" accept="image/*">
                    <input type="hidden" name="banner_source" id="bannerSource" value="">
                    <input type="hidden" name="banner_url" id="bannerUrl" value="">

                    <button type="button" class="btn btn-outline-secondary w-100 mb-2" id="libraryBannerBtn">
                        <i class="fa fa-folder-open me-2"></i>{{__('courses.choose_from_library')}}
                    </button>

                    <button type="button" class="btn btn-outline-secondary w-100 mb-2" id="urlBannerBtn">
                        <i class="fa fa-link me-2"></i>{{__('courses.from_url')}}
                    </button>

                    <button type="button" class="btn btn-outline-secondary w-100 mb-2" id="pasteBannerBtn">
                        <i class="fa fa-clipboard me-2"></i>{{__('courses.paste_from_clipboard')}}
                    </button>
                </div>
                <div class="col col-md-8">
                    <h6 class="mb-3">{{__('courses.recommended_size_1920x1080')}}</h6>
                    <div class="banner-preview-container" style="height: 250px; border: 2px dashed #ddd; border-radius: 8px; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;">
                        @if($course->banner)
                        <img src="{{asset('storage/' . $course->banner)}}" alt="Banner" id="bannerPreview" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                        @else
                        <div class="text-center text-muted" id="bannerPlaceholder">
                            <i class="fa fa-image fa-3x mb-2"></i>
                            <p>{{__('courses.upload_banner')}}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Promo Video Upload Section --}}
            <div class="row">
                <div class="col col-md-4 p-3">
                    <h6 class="mb-3">{{__('courses.upload_promo_video')}}</h6>
                    <button type="button" class="btn btn-outline-primary w-100 mb-2" id="uploadVideoBtn">
                        <i class="fa fa-upload me-2"></i>{{__('courses.upload_from_device')}}
                    </button>
                    <input type="file" name="promo_video" id="videoFileInput" class="d-none" accept="video/*">
                    <input type="hidden" name="video_source" id="videoSource" value="">
                    <input type="hidden" name="video_url" id="videoUrl" value="">

                    <button type="button" class="btn btn-outline-secondary w-100 mb-2" id="libraryVideoBtn">
                        <i class="fa fa-folder-open me-2"></i>{{__('courses.choose_from_library')}}
                    </button>

                    <button type="button" class="btn btn-outline-secondary w-100 mb-2" id="urlVideoBtn">
                        <i class="fa fa-link me-2"></i>{{__('courses.from_url')}}
                    </button>
                </div>
                <div class="col col-md-8">
                    <h6 class="mb-3">{{__('courses.duration_3_to_10_minutes')}}</h6>
                    <div class="video-preview-container" style="height: 250px; border: 2px dashed #ddd; border-radius: 8px; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;">
                        @if($course->promo_video)
                        <video src="{{asset('storage/' . $course->promo_video)}}" controls id="videoPreview" style="max-width: 100%; max-height: 100%;"></video>
                        @else
                        <div class="text-center text-muted" id="videoPlaceholder">
                            <i class="fa fa-video fa-3x mb-2"></i>
                            <p>{{__('courses.upload_promo_video')}}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset class="mt-4 pt-4 pb-0">
            <legend>Calculated Fields</legend>
            <style>
                .card-body {
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    background-color: #fff;
                    padding: 1rem;
                    margin-bottom: 1rem;
                    transition: all 0.3s ease-in-out;
                    text-align: center;
                }

                .card-body h3 {
                    font-weight: bold;
                }

                .card-body:hover {
                    box-shadow: 0 0 5px 2px rgba(0, 0, 0, 0.2);
                }
            </style>
            <div class="row">
                <div class="col col-md-3">
                    <div class="card-body p-3">
                        <h3>15<sup>hrs</sup></h3>
                        {{__('courses.duration')}}
                    </div>
                </div>
                <div class="col col-md-3">
                    <div class="card-body p-3">
                        <h3>4</h3>
                        {{__('courses.total_units')}}
                    </div>
                </div>
                <div class="col col-md-3">
                    <div class="card-body p-3">
                        <h3>23</h3>
                        {{__('courses.total_lessons')}}
                    </div>
                </div>
                <div class="col col-md-3">
                    <div class="card-body p-3">
                        <h3>4000<sup>+</sup></h3>
                        {{__('courses.total_enrollments')}}
                    </div>
                </div>
                <div class="col col-md-3">
                    <div class="card-body p-3">
                        <h3>Yes</h3>
                        {{__('courses.has_certificate')}}
                    </div>
                </div>
                <div class="col col-md-3">
                    <div class="card-body p-3">
                        <h3>Yes</h3>
                        {{__('courses.has_quizes')}}
                    </div>
                </div>
                <div class="col col-md-3">
                    <div class="card-body p-3">
                        <h3>Yes</h3>
                        {{__('courses.has_training')}}
                    </div>
                </div>
                <div class="col col-md-3">
                    <div class="card-body p-3">
                        <h3><i class="fa-solid fa-infinity"></i></h3>
                        {{__('courses.access_type')}}
                    </div>
                </div>
            </div>
        </fieldset>
        <div class="btns d-flex justify-content-end gap-2 mt-3">
            <button type="reset" class="btn btn-sm btn-warning">{{__('courses.reset')}}</button>
            <button type="submit" class="btn btn-sm btn-primary">{{__('courses.update')}}</button>
        </div>


    </form>

    {{-- Modal for URL Input (Banner) --}}
    <div class="modal fade" id="urlBannerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('courses.enter_image_url')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="url" class="form-control" id="bannerUrlInput" placeholder="https://example.com/image.jpg">
                    <small class="text-muted">{{__('courses.enter_image_url')}}</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('courses.cancel')}}</button>
                    <button type="button" class="btn btn-primary" id="submitBannerUrl">{{__('courses.submit')}}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal for URL Input (Video) --}}
    <div class="modal fade" id="urlVideoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('courses.enter_video_url')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="url" class="form-control" id="videoUrlInput" placeholder="https://example.com/video.mp4">
                    <small class="text-muted">{{__('courses.enter_video_url')}}</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('courses.cancel')}}</button>
                    <button type="button" class="btn btn-primary" id="submitVideoUrl">{{__('courses.submit')}}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal for Media Library (Banner) --}}
    <div class="modal fade" id="libraryBannerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('courses.media_library')}} - {{__('courses.upload_banner')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="bannerLibraryContent" class="row">
                        {{-- سيتم تحميل الملفات هنا عبر AJAX --}}
                        <div class="text-center p-5">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('courses.close')}}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal for Media Library (Video) --}}
    <div class="modal fade" id="libraryVideoModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('courses.media_library')}} - {{__('courses.promo_video')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="videoLibraryContent" class="row">
                        {{-- سيتم تحميل الملفات هنا عبر AJAX --}}
                        <div class="text-center p-5">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('courses.close')}}</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toast notification system
        function showToast(message, type = 'error') {
            const toastContainer = document.getElementById('toastContainer');
            const toastId = 'toast-' + Date.now();

            const toastColors = {
                'error': 'text-bg-danger',
                'success': 'text-bg-success',
                'warning': 'text-bg-warning',
                'info': 'text-bg-info'
            };

            const toastHtml = `
                <div class="toast ${toastColors[type] || toastColors['error']}" role="alert" id="${toastId}" data-bs-autohide="true" data-bs-delay="30000">
                    <div class="toast-header">
                        <strong class="me-auto">{{__('courses.validation_error')}}</strong>
                        <small class="text-muted">${new Date().toLocaleTimeString()}</small>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                    </div>
                    <div class="toast-body">
                        <i class="fa fa-exclamation-triangle me-2"></i>
                        ${message}
                    </div>
                </div>
            `;

            toastContainer.insertAdjacentHTML('beforeend', toastHtml);
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement);
            toast.show();

            // Remove toast element after it's hidden
            toastElement.addEventListener('hidden.bs.toast', () => {
                toastElement.remove();
            });
        }

        // Validation functions with specific error messages
        function validateImageFile(file) {
            const validMimeTypes = ['image/png', 'image/jpg', 'image/jpeg', 'image/svg+xml'];
            const maxSize = 2 * 1024 * 1024; // 1MB

            // Check MIME type
            if (!validMimeTypes.includes(file.type)) {
                showToast(`<strong>{{__("courses.invalid_file_format_error")}}:</strong><br>
                          {{__("courses.uploaded_file_type")}}: ${file.type}<br>
                          {{__("courses.allowed_image_formats")}}: PNG, JPG, JPEG, SVG<br>
                          {{__("courses.please_select_valid_image")}}`, 'error');
                return false;
            }

            // Check file size
            if (file.size > maxSize) {
                const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                showToast(`<strong>{{__("courses.file_size_error")}}:</strong><br>
                          {{__("courses.current_file_size")}}: ${fileSizeMB} MB<br>
                          {{__("courses.maximum_allowed_size")}}: 1 MB<br>
                          {{__("courses.please_compress_or_choose_smaller")}}`, 'error');
                return false;
            }

            return true;
        }

        function validateVideoFile(file) {
            const validMimeTypes = ['video/mp4', 'video/avi', 'video/x-msvideo', 'video/x-matroska', 'video/webm'];
            const maxSize = 20 * 1024 * 1024; // 5MB

            // Check MIME type first
            if (!validMimeTypes.includes(file.type)) {
                showToast(`<strong>{{__("courses.invalid_file_format_error")}}:</strong><br>
                          {{__("courses.uploaded_file_type")}}: ${file.type || 'غير معروف'}<br>
                          {{__("courses.allowed_video_formats")}}: MP4, AVI, MKV, WEBM<br>
                          {{__("courses.please_select_valid_video")}}`, 'error');
                return false;
            }

            // Check file size
            if (file.size > maxSize) {
                const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                showToast(`<strong>{{__("courses.file_size_error")}}:</strong><br>
                          {{__("courses.current_file_size")}}: ${fileSizeMB} MB<br>
                          {{__("courses.maximum_allowed_size")}}: 5 MB<br>
                          {{__("courses.please_compress_or_choose_smaller")}}`, 'error');
                return false;
            }

            return true;
        }

        function validateAspectRatio(width, height) {
            const targetRatio = 16 / 9;
            const actualRatio = width / height;
            const tolerance = 0.2; // 10% tolerance

            return Math.abs(actualRatio - targetRatio) <= tolerance;
        }

        function validateVideoDuration(video) {
            return new Promise((resolve) => {
                // Check if metadata is already loaded
                if (video.readyState >= 1) {
                    const duration = video.duration;
                    const maxDuration = 15 * 60; // 10 minutes in seconds

                    if (duration > maxDuration) {
                        const durationMinutes = Math.floor(duration / 60);
                        const durationSeconds = Math.floor(duration % 60);
                        showToast(`<strong>{{__("courses.video_duration_error")}}:</strong><br>
                                  {{__("courses.current_video_duration")}}: ${durationMinutes}:${durationSeconds.toString().padStart(2, '0')}<br>
                                  {{__("courses.maximum_allowed_duration")}}: 10:00<br>
                                  {{__("courses.please_trim_video_or_choose_shorter")}}`, 'error');
                        resolve(false);
                    } else {
                        resolve(true);
                    }
                    return;
                }

                const handleLoadedMetadata = () => {
                    const duration = video.duration;
                    const maxDuration = 15 * 60; // 10 minutes in seconds

                    if (duration > maxDuration) {
                        const durationMinutes = Math.floor(duration / 60);
                        const durationSeconds = Math.floor(duration % 60);
                        showToast(`<strong>{{__("courses.video_duration_error")}}:</strong><br>
                                  {{__("courses.current_video_duration")}}: ${durationMinutes}:${durationSeconds.toString().padStart(2, '0')}<br>
                                  {{__("courses.maximum_allowed_duration")}}: 10:00<br>
                                  {{__("courses.please_trim_video_or_choose_shorter")}}`, 'error');
                        resolve(false);
                    } else {
                        resolve(true);
                    }
                };

                const handleError = () => {
                    showToast(`<strong>{{__("courses.video_loading_error")}}:</strong><br>
                              {{__("courses.failed_to_read_video_metadata")}}<br>
                              {{__("courses.please_check_video_file")}}`, 'error');
                    resolve(false);
                };

                video.addEventListener('loadedmetadata', handleLoadedMetadata, {
                    once: true
                });
                video.addEventListener('error', handleError, {
                    once: true
                });

                // Timeout fallback
                setTimeout(() => {
                    video.removeEventListener('loadedmetadata', handleLoadedMetadata);
                    video.removeEventListener('error', handleError);
                    showToast(`<strong>{{__("courses.video_loading_timeout")}}:</strong><br>
                              {{__("courses.video_took_too_long_to_load")}}<br>
                              {{__("courses.please_try_different_file")}}`, 'error');
                    resolve(false);
                }, 8000); // Reduced timeout to 8 seconds
            });
        }

        function validateImageDimensions(file) {
            return new Promise((resolve) => {
                const img = new Image();
                const url = URL.createObjectURL(file);

                img.onload = () => {
                    URL.revokeObjectURL(url);

                    if (!validateAspectRatio(img.width, img.height)) {
                        const actualRatio = (img.width / img.height).toFixed(2);
                        showToast(`<strong>{{__("courses.aspect_ratio_error")}}:</strong><br>
                                  {{__("courses.current_image_dimensions")}}: ${img.width} × ${img.height}<br>
                                  {{__("courses.current_aspect_ratio")}}: ${actualRatio}:1<br>
                                  {{__("courses.required_aspect_ratio")}}: 1.78:1 (16:9)<br>
                                  {{__("courses.please_crop_or_resize_image")}}`, 'error');
                        resolve(false);
                    } else {
                        resolve(true);
                    }
                };

                img.onerror = () => {
                    URL.revokeObjectURL(url);
                    showToast(`<strong>{{__("courses.image_loading_error")}}:</strong><br>
                              {{__("courses.failed_to_read_image_data")}}<br>
                              {{__("courses.file_may_be_corrupted")}}`, 'error');
                    resolve(false);
                };

                img.src = url;
            });
        }

        function validateVideoDimensions(file) {
            return new Promise((resolve) => {
                const video = document.createElement('video');
                const url = URL.createObjectURL(file);

                const cleanup = () => {
                    URL.revokeObjectURL(url);
                    video.removeEventListener('loadedmetadata', handleLoadedMetadata);
                    video.removeEventListener('error', handleError);
                };

                const handleLoadedMetadata = async () => {
                    try {
                        // Check aspect ratio first
                        if (!validateAspectRatio(video.videoWidth, video.videoHeight)) {
                            const actualRatio = (video.videoWidth / video.videoHeight).toFixed(2);
                            showToast(`<strong>{{__("courses.aspect_ratio_error")}}:</strong><br>
                                      {{__("courses.current_video_dimensions")}}: ${video.videoWidth} × ${video.videoHeight}<br>
                                      {{__("courses.current_aspect_ratio")}}: ${actualRatio}:1<br>
                                      {{__("courses.required_aspect_ratio")}}: 1.78:1 (16:9)<br>
                                      {{__("courses.please_crop_or_resize_video")}}`, 'error');
                            cleanup();
                            resolve(false);
                            return;
                        }

                        // Check duration using the same video element
                        const durationValid = await validateVideoDuration(video);
                        cleanup();
                        resolve(durationValid);

                    } catch (error) {
                        console.error('Video validation error:', error);
                        cleanup();
                        resolve(false);
                    }
                };

                const handleError = () => {
                    showToast(`<strong>{{__("courses.video_loading_error")}}:</strong><br>
                              {{__("courses.failed_to_read_video_data")}}<br>
                              {{__("courses.file_may_be_corrupted")}}`, 'error');
                    cleanup();
                    resolve(false);
                };

                video.addEventListener('loadedmetadata', handleLoadedMetadata, {
                    once: true
                });
                video.addEventListener('error', handleError, {
                    once: true
                });

                // Set preload to metadata only to load just the basic info
                video.preload = 'metadata';
                video.src = url;

                // Reduced timeout for faster feedback
                setTimeout(() => {
                    if (video.readyState === 0) {
                        showToast(`<strong>{{__("courses.video_loading_timeout")}}:</strong><br>
                                  {{__("courses.video_took_too_long_to_load")}}<br>
                                  {{__("courses.check_internet_connection")}}`, 'error');
                        cleanup();
                        resolve(false);
                    }
                }, 10000); // 10 second timeout
            });
        }

        // Success messages with specific details
        function showSuccessMessage(type, fileName = '') {
            if (type === 'banner_upload') {
                showToast(`<strong>{{__("courses.upload_successful")}}!</strong><br>
                          <i class="fa fa-check-circle me-1"></i>{{__("courses.banner_image_uploaded")}}<br>
                          ${fileName ? '{{__("courses.file_name")}}: ' + fileName : ''}`, 'success');
            } else if (type === 'video_upload') {
                showToast(`<strong>{{__("courses.upload_successful")}}!</strong><br>
                          <i class="fa fa-check-circle me-1"></i>{{__("courses.promo_video_uploaded")}}<br>
                          ${fileName ? '{{__("courses.file_name")}}: ' + fileName : ''}`, 'success');
            } else if (type === 'banner_url') {
                showToast(`<strong>{{__("courses.load_successful")}}!</strong><br>
                          <i class="fa fa-check-circle me-1"></i>{{__("courses.banner_loaded_from_url")}}`, 'success');
            } else if (type === 'video_url') {
                showToast(`<strong>{{__("courses.load_successful")}}!</strong><br>
                          <i class="fa fa-check-circle me-1"></i>{{__("courses.video_loaded_from_url")}}`, 'success');
            } else if (type === 'banner_clipboard') {
                showToast(`<strong>{{__("courses.paste_successful")}}!</strong><br>
                          <i class="fa fa-check-circle me-1"></i>{{__("courses.banner_pasted_from_clipboard")}}`, 'success');
            }
        }

        // Updated banner upload handlers
        document.getElementById('uploadBannerBtn').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('bannerFileInput').click();
        });

        document.getElementById('bannerFileInput').addEventListener('change', async function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Basic file validation
            if (!validateImageFile(file)) {
                e.target.value = '';
                return;
            }

            // Dimension validation
            const dimensionsValid = await validateImageDimensions(file);
            if (!dimensionsValid) {
                e.target.value = '';
                return;
            }

            // If all validations pass
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('bannerPreview');
                const placeholder = document.getElementById('bannerPlaceholder');

                if (preview) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                } else {
                    placeholder.innerHTML = `<img src="${e.target.result}" alt="Banner" id="bannerPreview" style="max-width: 100%; max-height: 100%; object-fit: contain;">`;
                }

                if (placeholder && !preview) {
                    placeholder.style.display = 'none';
                }

                document.getElementById('bannerSource').value = 'upload';
                showSuccessMessage('banner_upload', file.name);
            };
            reader.readAsDataURL(file);
        });

        // Updated video upload handlers
        document.getElementById('uploadVideoBtn').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('videoFileInput').click();
        });

        document.getElementById('videoFileInput').addEventListener('change', async function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Show loading state
            const uploadBtn = document.getElementById('uploadVideoBtn');
            const originalBtnText = uploadBtn.innerHTML;
            uploadBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>{{__("courses.validating")}}...';
            uploadBtn.disabled = true;

            try {
                // Basic file validation
                if (!validateVideoFile(file)) {
                    e.target.value = '';
                    return;
                }

                // Show progress for dimension validation
                uploadBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>{{__("courses.checking_video_properties")}}...';

                // Dimension and duration validation
                const dimensionsValid = await validateVideoDimensions(file);
                if (!dimensionsValid) {
                    e.target.value = '';
                    return;
                }

                // Show progress for file processing
                uploadBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>{{__("courses.processing_video")}}...';

                // If all validations pass
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('videoPreview');
                    const placeholder = document.getElementById('videoPlaceholder');

                    if (preview) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    } else {
                        placeholder.innerHTML = `<video src="${e.target.result}" controls id="videoPreview" style="max-width: 100%; max-height: 100%;"></video>`;
                    }

                    if (placeholder && !preview) {
                        placeholder.style.display = 'none';
                    }

                    document.getElementById('videoSource').value = 'upload';
                    showSuccessMessage('video_upload', file.name);
                };

                reader.onerror = function() {
                    showToast(`<strong>{{__("courses.file_reading_error")}}:</strong><br>
                              {{__("courses.failed_to_read_video_file")}}<br>
                              {{__("courses.please_try_again")}}`, 'error');
                };

                reader.readAsDataURL(file);

            } catch (error) {
                console.error('Video validation error:', error);
                showToast(`<strong>{{__("courses.unexpected_error")}}:</strong><br>
                          {{__("courses.error_occurred_during_validation")}}<br>
                          {{__("courses.please_try_again")}}`, 'error');
                e.target.value = '';
            } finally {
                // Restore button state
                setTimeout(() => {
                    uploadBtn.innerHTML = originalBtnText;
                    uploadBtn.disabled = false;
                }, 1000);
            }
        });

        // URL validation for banner
        document.getElementById('urlBannerBtn').addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('urlBannerModal'));
            modal.show();
        });

        document.getElementById('submitBannerUrl').addEventListener('click', function() {
            const url = document.getElementById('bannerUrlInput').value;
            if (!url) {
                showToast(`<strong>{{__("courses.input_required")}}:</strong><br>
                          {{__("courses.please_enter_valid_image_url")}}`, 'warning');
                return;
            }

            // Validate image from URL
            const img = new Image();
            img.onload = function() {
                if (!validateAspectRatio(img.width, img.height)) {
                    const actualRatio = (img.width / img.height).toFixed(2);
                    showToast(`<strong>{{__("courses.aspect_ratio_error")}}:</strong><br>
                              {{__("courses.image_dimensions_from_url")}}: ${img.width} × ${img.height}<br>
                              {{__("courses.current_aspect_ratio")}}: ${actualRatio}:1<br>
                              {{__("courses.required_aspect_ratio")}}: 1.78:1 (16:9)`, 'error');
                    return;
                }

                const preview = document.getElementById('bannerPreview');
                const placeholder = document.getElementById('bannerPlaceholder');

                if (preview) {
                    preview.src = url;
                } else {
                    placeholder.innerHTML = `<img src="${url}" alt="Banner" id="bannerPreview" style="max-width: 100%; max-height: 100%; object-fit: contain;">`;
                    placeholder.style.display = 'none';
                }

                document.getElementById('bannerSource').value = 'url';
                document.getElementById('bannerUrl').value = url;
                document.getElementById('bannerUrlInput').value = '';

                const modal = bootstrap.Modal.getInstance(document.getElementById('urlBannerModal'));
                modal.hide();

                showSuccessMessage('banner_url');
            };

            img.onerror = function() {
                showToast(`<strong>{{__("courses.url_loading_error")}}:</strong><br>
                          {{__("courses.failed_to_load_image_from_url")}}<br>
                          {{__("courses.check_url_accessibility")}}`, 'error');
            };

            img.src = url;
        });

        // URL validation for video
        document.getElementById('urlVideoBtn').addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('urlVideoModal'));
            modal.show();
        });

        document.getElementById('submitVideoUrl').addEventListener('click', function() {
            const url = document.getElementById('videoUrlInput').value.trim();
            const submitBtn = document.getElementById('submitVideoUrl');
            const originalBtnText = submitBtn.innerHTML;

            if (!url) {
                showToast(`<strong>{{__("courses.input_required")}}:</strong><br>
                          {{__("courses.please_enter_valid_video_url")}}<br>
                          {{__("courses.example")}}: https://example.com/video.mp4`, 'warning');
                return;
            }

            // Show loading state
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>{{__("courses.validating")}}...';
            submitBtn.disabled = true;

            // Validate video from URL
            const video = document.createElement('video');

            const handleLoadedMetadata = async function() {
                try {
                    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>{{__("courses.checking_dimensions")}}...';

                    if (!validateAspectRatio(video.videoWidth, video.videoHeight)) {
                        const actualRatio = (video.videoWidth / video.videoHeight).toFixed(2);
                        showToast(`<strong>{{__("courses.aspect_ratio_error")}}:</strong><br>
                                  {{__("courses.video_dimensions_from_url")}}: ${video.videoWidth} × ${video.videoHeight}<br>
                                  {{__("courses.current_aspect_ratio")}}: ${actualRatio}:1<br>
                                  {{__("courses.required_aspect_ratio")}}: 1.78:1 (16:9)<br>
                                  {{__("courses.please_use_different_video")}}`, 'error');
                        return;
                    }

                    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>{{__("courses.checking_duration")}}...';

                    const durationValid = await validateVideoDuration(video);
                    if (!durationValid) {
                        return;
                    }

                    // Success - load the video
                    const preview = document.getElementById('videoPreview');
                    const placeholder = document.getElementById('videoPlaceholder');

                    if (preview) {
                        preview.src = url;
                    } else {
                        placeholder.innerHTML = `<video src="${url}" controls id="videoPreview" style="max-width: 100%; max-height: 100%;"></video>`;
                        placeholder.style.display = 'none';
                    }

                    document.getElementById('videoSource').value = 'url';
                    document.getElementById('videoUrl').value = url;
                    document.getElementById('videoUrlInput').value = '';

                    const modal = bootstrap.Modal.getInstance(document.getElementById('urlVideoModal'));
                    modal.hide();

                    showSuccessMessage('video_url');

                } catch (error) {
                    console.error('Video URL validation error:', error);
                    showToast(`<strong>{{__("courses.validation_error")}}:</strong><br>
                              {{__("courses.error_occurred_during_validation")}}<br>
                              {{__("courses.please_check_url_and_try_again")}}`, 'error');
                } finally {
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;
                }
            };

            const handleError = function() {
                showToast(`<strong>{{__("courses.url_loading_error")}}:</strong><br>
                          {{__("courses.failed_to_load_video_from_url")}}<br>
                          {{__("courses.check_url_accessibility")}}<br>
                          {{__("courses.ensure_direct_video_link")}}`, 'error');

                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
            };

            video.addEventListener('loadedmetadata', handleLoadedMetadata, {
                once: true
            });
            video.addEventListener('error', handleError, {
                once: true
            });

            // Timeout for URL loading
            setTimeout(() => {
                if (video.readyState === 0) {
                    showToast(`<strong>{{__("courses.url_loading_timeout")}}:</strong><br>
                              {{__("courses.video_url_took_too_long")}}<br>
                              {{__("courses.check_internet_connection")}}`, 'error');

                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;
                }
            }, 15000);

            video.src = url;
        });

        // Paste from clipboard handlers
        document.getElementById('pasteBannerBtn').addEventListener('click', async function() {
            try {
                const clipboardItems = await navigator.clipboard.read();
                const imageItems = clipboardItems.filter(item =>
                    item.types.some(type => type.startsWith('image/'))
                );

                if (imageItems.length === 0) {
                    showToast(`<strong>{{__("courses.clipboard_empty")}}:</strong><br>
                              {{__("courses.no_image_found_in_clipboard")}}<br>
                              {{__("courses.copy_image_first")}}`, 'warning');
                    return;
                }

                const imageItem = imageItems[0];
                const imageType = imageItem.types.find(type => type.startsWith('image/'));
                const blob = await imageItem.getType(imageType);

                // Create file object from blob
                const file = new File([blob], 'clipboard-image', {
                    type: imageType
                });

                // Validate the pasted image
                if (!validateImageFile(file)) {
                    return;
                }

                const dimensionsValid = await validateImageDimensions(file);
                if (!dimensionsValid) {
                    return;
                }

                // Display the image
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('bannerPreview');
                    const placeholder = document.getElementById('bannerPlaceholder');

                    if (preview) {
                        preview.src = e.target.result;
                    } else {
                        placeholder.innerHTML = `<img src="${e.target.result}" alt="Banner" id="bannerPreview" style="max-width: 100%; max-height: 100%; object-fit: contain;">`;
                        placeholder.style.display = 'none';
                    }

                    document.getElementById('bannerSource').value = 'clipboard';
                    showSuccessMessage('banner_clipboard');
                };
                reader.readAsDataURL(file);

            } catch (error) {
                showToast(`<strong>{{__("courses.clipboard_access_error")}}:</strong><br>
                          {{__("courses.failed_to_access_clipboard")}}<br>
                          {{__("courses.browser_permission_required")}}`, 'error');
            }
        });

        // Library handlers with proper modal management
        document.getElementById('libraryBannerBtn').addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('libraryBannerModal'));
            modal.show();
            loadMediaLibrary('images');
        });

        document.getElementById('libraryVideoBtn').addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('libraryVideoModal'));
            modal.show();
            loadMediaLibrary('videos');
        });

        // Add modal event listeners for proper cleanup
        document.getElementById('libraryBannerModal').addEventListener('hidden.bs.modal', function() {
            // Reset modal content when closed
            const container = document.getElementById('bannerLibraryContent');
            container.innerHTML = `
                <div class="text-center p-5">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `;
            // Remove backdrop if it exists
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
            // Restore body scroll
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('padding-right');
        });

        document.getElementById('libraryVideoModal').addEventListener('hidden.bs.modal', function() {
            // Reset modal content when closed
            const container = document.getElementById('videoLibraryContent');
            container.innerHTML = `
                <div class="text-center p-5">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `;
            // Remove backdrop if it exists
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
            // Restore body scroll
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('padding-right');
        });

        // Add event listeners for URL modals as well
        document.getElementById('urlBannerModal').addEventListener('hidden.bs.modal', function() {
            // Clear input when modal is closed
            document.getElementById('bannerUrlInput').value = '';
            // Clean up backdrop
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('padding-right');
        });

        document.getElementById('urlVideoModal').addEventListener('hidden.bs.modal', function() {
            // Clear input when modal is closed
            document.getElementById('videoUrlInput').value = '';
            // Clean up backdrop
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('padding-right');
        });

        // Media library loading function with improved error handling
        function loadMediaLibrary(type) {
            const contentContainer = type === 'images' ? 'bannerLibraryContent' : 'videoLibraryContent';
            const container = document.getElementById(contentContainer);

            // Show loading spinner
            container.innerHTML = `
                <div class="text-center p-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">{{__('courses.loading')}}</span>
                    </div>
                    <p class="mt-2 text-muted">{{__('courses.loading_media_library')}}</p>
                </div>
            `;

            // Check if media library route exists
            fetch(`/instructor/media-library/${type}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.media && data.media.length > 0) {
                        displayMediaLibrary(data.media, type, contentContainer);
                    } else {
                        showEmptyLibrary(contentContainer, type);
                    }
                })
                .catch(error => {
                    console.error('Media library error:', error);
                    showLibraryError(contentContainer, type, error.message);
                });
        }

        function displayMediaLibrary(media, type, containerId) {
            const container = document.getElementById(containerId);
            let html = '<div class="row g-3">';

            media.forEach(item => {
                if (type === 'images') {
                    html += `
                        <div class="col-md-3">
                            <div class="card h-100 media-item" data-url="${item.url}" data-id="${item.id}" style="cursor: pointer; transition: transform 0.2s;">
                                <img src="${item.thumbnail || item.url}" class="card-img-top" style="height: 120px; object-fit: cover;">
                                <div class="card-body p-2">
                                    <small class="text-muted d-block text-truncate" title="${item.name}">${item.name}</small>
                                    <small class="text-muted">${item.size}</small>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    html += `
                        <div class="col-md-4">
                            <div class="card h-100 media-item" data-url="${item.url}" data-id="${item.id}" style="cursor: pointer; transition: transform 0.2s;">
                                <div class="position-relative">
                                    <video class="card-img-top" style="height: 120px; width: 100%; object-fit: cover;" preload="metadata" muted>
                                        <source src="${item.url}" type="${item.mime_type}">
                                    </video>
                                    <div class="position-absolute top-50 start-50 translate-middle">
                                        <i class="fa fa-play-circle fa-2x text-white" style="text-shadow: 0 0 5px rgba(0,0,0,0.5);"></i>
                                    </div>
                                </div>
                                <div class="card-body p-2">
                                    <small class="text-muted d-block text-truncate" title="${item.name}">${item.name}</small>
                                    <small class="text-muted">${item.duration || ''} - ${item.size}</small>
                                </div>
                            </div>
                        </div>
                    `;
                }
            });

            html += '</div>';
            container.innerHTML = html;

            // Add hover effects and click handlers
            container.querySelectorAll('.media-item').forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.05)';
                });

                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });

                item.addEventListener('click', function() {
                    const url = this.dataset.url;
                    selectMediaFromLibrary(url, type);
                });
            });
        }

        function showEmptyLibrary(containerId, type) {
            const container = document.getElementById(containerId);
            const mediaType = type === 'images' ? '{{__("courses.images")}}' : '{{__("courses.videos")}}';

            container.innerHTML = `
                <div class="text-center p-5">
                    <i class="fa fa-folder-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">{{__('courses.no_media_found')}}</h5>
                    <p class="text-muted">لا توجد ${mediaType} في المكتبة حالياً</p>
                    <button type="button" class="btn btn-primary" onclick="uploadToLibrary('${type}')">
                        <i class="fa fa-upload me-2"></i>{{__('courses.upload_new_file')}}
                    </button>
                </div>
            `;
        }

        function showLibraryError(containerId, type, errorMessage) {
            const container = document.getElementById(containerId);

            container.innerHTML = `
                <div class="text-center p-5">
                    <i class="fa fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h5 class="text-warning">{{__('courses.media_library_not_available')}}</h5>
                    <p class="text-muted mb-3">{{__('courses.media_library_error_description')}}</p>
                    <div class="alert alert-info text-start">
                        <strong>{{__('courses.technical_details')}}:</strong><br>
                        <code>${errorMessage}</code>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary" onclick="createMediaLibrarySystem()">
                            <i class="fa fa-cog me-2"></i>{{__('courses.setup_media_library')}}
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="loadMediaLibrary('${type}')">
                            <i class="fa fa-refresh me-2"></i>{{__('courses.try_again')}}
                        </button>
                    </div>
                </div>
            `;
        }

        function selectMediaFromLibrary(url, type) {
            // Close modal properly
            const modalId = type === 'images' ? 'libraryBannerModal' : 'libraryVideoModal';
            const modalElement = document.getElementById(modalId);
            const modal = bootstrap.Modal.getInstance(modalElement);

            if (modal) {
                modal.hide();
            }

            // Force cleanup in case the event doesn't fire
            setTimeout(() => {
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
                document.body.classList.remove('modal-open');
                document.body.style.removeProperty('padding-right');
            }, 300);

            if (type === 'images') {
                const preview = document.getElementById('bannerPreview');
                const placeholder = document.getElementById('bannerPlaceholder');

                if (preview) {
                    preview.src = url;
                } else if (placeholder) {
                    placeholder.innerHTML = `<img src="${url}" alt="Banner" id="bannerPreview" style="max-width: 100%; max-height: 100%; object-fit: contain;">`;
                    placeholder.style.display = 'none';
                }

                document.getElementById('bannerSource').value = 'library';
                document.getElementById('bannerUrl').value = url;

                showToast(`<strong>{{__("courses.selection_successful")}}!</strong><br>
                          <i class="fa fa-check-circle me-1"></i>{{__("courses.banner_selected_from_library")}}`, 'success');
            } else {
                const preview = document.getElementById('videoPreview');
                const placeholder = document.getElementById('videoPlaceholder');

                if (preview) {
                    preview.src = url;
                } else if (placeholder) {
                    placeholder.innerHTML = `<video src="${url}" controls id="videoPreview" style="max-width: 100%; max-height: 100%;"></video>`;
                    placeholder.style.display = 'none';
                }

                document.getElementById('videoSource').value = 'library';
                document.getElementById('videoUrl').value = url;

                showToast(`<strong>{{__("courses.selection_successful")}}!</strong><br>
                          <i class="fa fa-check-circle me-1"></i>{{__("courses.video_selected_from_library")}}`, 'success');
            }
        }

        function createMediaLibrarySystem() {
            showToast(`<strong>{{__("courses.setup_in_progress")}}:</strong><br>
                      {{__("courses.creating_media_library_system")}}<br>
                      {{__("courses.please_wait")}}...`, 'info');

            fetch('/instructor/setup-media-library', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(`<strong>{{__("courses.setup_successful")}}!</strong><br>
                              {{__("courses.media_library_created_successfully")}}`, 'success');

                        // Reload the current modal after successful setup
                        setTimeout(() => {
                            const activeModal = document.querySelector('.modal.show');
                            if (activeModal) {
                                const type = activeModal.id.includes('Banner') ? 'images' : 'videos';
                                loadMediaLibrary(type);
                            }
                        }, 1000);
                    } else {
                        showToast(`<strong>{{__("courses.setup_failed")}}:</strong><br>
                              ${data.message || '{{__("courses.unknown_error")}}'}`, 'error');
                    }
                })
                .catch(error => {
                    console.error('Setup error:', error);
                    showToast(`<strong>{{__("courses.setup_error")}}:</strong><br>
                          {{__("courses.failed_to_create_media_library")}}`, 'error');
                });
        }

        function uploadToLibrary(type) {
            showToast(`<strong>{{__("courses.feature_coming_soon")}}:</strong><br>
                      {{__("courses.direct_library_upload_not_implemented")}}`, 'info');
        }

        // Force cleanup function for stubborn modals
        function forceModalCleanup() {
            // Remove all backdrops
            document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
                backdrop.remove();
            });

            // Remove modal-open class from body
            document.body.classList.remove('modal-open');

            // Reset body padding
            document.body.style.removeProperty('padding-right');

            // Hide all modals
            document.querySelectorAll('.modal.show').forEach(modal => {
                modal.classList.remove('show');
                modal.style.display = 'none';
            });
        }

        // Add escape key handler for all modals
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const activeModal = document.querySelector('.modal.show');
                if (activeModal) {
                    const modal = bootstrap.Modal.getInstance(activeModal);
                    if (modal) {
                        modal.hide();
                    } else {
                        forceModalCleanup();
                    }
                }
            }
        });

        // Add click outside handler
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal') && e.target.classList.contains('show')) {
                const modal = bootstrap.Modal.getInstance(e.target);
                if (modal) {
                    modal.hide();
                }
            }
        });
    </script>

    <style>
        .toast {
            min-width: 350px;
            max-width: 500px;
        }

        .toast-header {
            font-weight: 600;
        }

        .toast-body {
            word-wrap: break-word;
            line-height: 1.5;
        }

        .toast-body strong {
            color: #721c24;
        }

        .toast.text-bg-success .toast-body strong {
            color: #0f5132;
        }

        .toast.text-bg-warning .toast-body strong {
            color: #664d03;
        }

        .toast.text-bg-info .toast-body strong {
            color: #055160;
        }
    </style>
</div>