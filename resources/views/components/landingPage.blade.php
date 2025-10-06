@props([
'course',
])

<div>
    <div class="row">
        {{-- Course Banner --}}
        <div class="col-md-7">
            <div class="form-group">
                <h5>{{__('courses.course_banner')}}</h5>
                <p class="text-muted">{{__('courses.banner_instructions')}}</p>

                <div class="banner-preview-container border rounded p-2 text-center" style="height: 250px; background-color: #f8f9fa;">
                    @if($course->banner)
                        <img id="bannerPreview" src="{{ asset('storage/courses/banners/' . $course->banner) }}" alt="Banner Preview" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                        <div id="bannerPlaceholder" style="display: none;"></div>
                    @else
                        <img id="bannerPreview" src="" alt="Banner Preview" style="max-width: 100%; max-height: 100%; object-fit: contain; display: none;">
                        <div id="bannerPlaceholder" class="d-flex flex-column justify-content-center align-items-center h-100">
                            <i class="fa-solid fa-image fa-3x text-muted"></i>
                            <p class="mt-2">{{__('courses.no_banner_uploaded')}}</p>
                        </div>
                    @endif
                </div>

                <div class="d-flex justify-content-center gap-2 mt-3">
                    <button id="uploadBannerBtn" class="btn btn-sm btn-primary"><i class="fa-solid fa-upload me-2"></i>{{__('courses.upload_banner')}}</button>
                    <button id="urlBannerBtn" class="btn btn-sm btn-secondary"><i class="fa-solid fa-link me-2"></i>{{__('courses.from_url')}}</button>
                    <button id="pasteBannerBtn" class="btn btn-sm btn-info"><i class="fa-solid fa-paste me-2"></i>{{__('courses.paste_from_clipboard')}}</button>
                    <button id="libraryBannerBtn" class="btn btn-sm btn-success"><i class="fa-solid fa-photo-film me-2"></i>{{__('courses.from_library')}}</button>
                </div>
            </div>
        </div>

        {{-- Promo Video --}}
        <div class="col-md-5">
            <div class="form-group">
                <h5>{{__('courses.promo_video')}}</h5>
                <p class="text-muted">{{__('courses.video_instructions')}}</p>

                <div class="video-preview-container border rounded p-2 text-center" style="height: 250px; background-color: #f8f9fa;">
                     @if($course->promo_video)
                        <video id="videoPreview" src="{{ asset('storage/courses/promo_videos/' . $course->promo_video) }}" controls style="max-width: 100%; max-height: 100%;"></video>
                        <div id="videoPlaceholder" style="display: none;"></div>
                    @else
                        <video id="videoPreview" src="" controls style="max-width: 100%; max-height: 100%; display: none;"></video>
                        <div id="videoPlaceholder" class="d-flex flex-column justify-content-center align-items-center h-100">
                            <i class="fa-solid fa-video fa-3x text-muted"></i>
                            <p class="mt-2">{{__('courses.no_video_uploaded')}}</p>
                        </div>
                    @endif
                </div>

                <div class="d-flex justify-content-center gap-2 mt-3">
                    <button id="uploadVideoBtn" class="btn btn-sm btn-primary"><i class="fa-solid fa-upload me-2"></i>{{__('courses.upload_video')}}</button>
                    <button id="urlVideoBtn" class="btn btn-sm btn-secondary"><i class="fa-solid fa-link me-2"></i>{{__('courses.from_url')}}</button>
                    <button id="libraryVideoBtn" class="btn btn-sm btn-success"><i class="fa-solid fa-photo-film me-2"></i>{{__('courses.from_library')}}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden Inputs for form submission --}}
    <input type="file" id="bannerFileInput" name="banner" class="d-none">
    <input type="file" id="videoFileInput" name="promo_video" class="d-none">
    <input type="hidden" id="bannerSource" name="banner_source" value="">
    <input type="hidden" id="videoSource" name="video_source" value="">
    <input type="hidden" id="bannerUrl" name="banner_url" value="">
    <input type="hidden" id="videoUrl" name="video_url" value="">

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
</div>

@push('scripts')
<script src="{{ asset('js/general-info.js') }}"></script>
@endpush