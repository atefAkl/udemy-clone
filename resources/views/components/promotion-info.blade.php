@props(['course'])

<div class="promotion-info-component">
    <form action="{{ route('instructor.courses.update.promotion-info', [$course->id]) }}" method="POST" enctype="multipart/form-data" id="promotionForm">
        @csrf
        @method('PUT')

        <fieldset class="my-4">
            <legend class="h5 mb-4">{{__('courses.announcement_media')}}</legend>

            {{-- Banner Upload Section --}}
            <div class="row mb-5">
                <div class="col-md-4">
                    <h6 class="mb-3 fw-bold">{{__('courses.upload_banner')}}</h6>

                    <button id="browseBannerBtn" type="button" class="btn btn-primary w-100 mb-2" data-action="upload-banner">
                        <i class="fa fa-upload me-2"></i>{{__('courses.upload_from_device')}}
                    </button>

                    <button id="libraryBannerBtn" type="button" class="btn btn-outline-secondary w-100 mb-2" data-action="library-banner">
                        <i class="fa fa-folder-open me-2"></i>{{__('courses.choose_from_library')}}
                    </button>

                    <button type="button" class="btn btn-outline-secondary w-100 mb-2" data-action="url-banner">
                        <i class="fa fa-link me-2"></i>{{__('courses.from_url')}}
                    </button>

                    <button type="button" class="btn btn-outline-secondary w-100 mb-2" data-action="paste-banner">
                        <i class="fa fa-clipboard me-2"></i>{{__('courses.paste_from_clipboard')}}
                    </button>

                    {{-- Hidden file input for banner --}}
                    <input type="file" name="banner" id="bannerFileInput" class="d-none" accept="image/jpeg,image/png,image/jpg,image/gif">
                    <input type="hidden" name="banner_source" id="bannerSource" value="">
                    <input type="hidden" name="banner_url" id="bannerUrlInput" value="">
                </div>

                <div class="col-md-8">
                    <h6 class="mb-3 text-muted">{{__('courses.recommended_size_1920x1080')}}</h6>
                    <div id="bannerPreviewContainer" class="preview-container">
                        <img src="{{ $course->banner_url }}" alt="Banner" id="bannerPreview" class="preview-image ">

                        <div id="bannerPlaceholder" class="preview-placeholder {{ $course->banner_url ? 'd-none' : '' }}">
                            <i class="fa fa-image fa-4x mb-3 text-muted"></i>
                            <p class="text-muted">{{__('courses.upload_banner')}}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Promo Video Upload Section --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <h6 class="mb-3 fw-bold">{{__('courses.upload_promo_video')}}</h6>

                    <button type="button" class="btn btn-primary w-100 mb-2" data-action="upload-video">
                        <i class="fa fa-upload me-2"></i>{{__('courses.upload_from_device')}}
                    </button>

                    <button type="button" class="btn btn-outline-secondary w-100 mb-2" data-action="library-video">
                        <i class="fa fa-folder-open me-2"></i>{{__('courses.choose_from_library')}}
                    </button>

                    <button type="button" class="btn btn-outline-secondary w-100 mb-2" data-action="url-video">
                        <i class="fa fa-link me-2"></i>{{__('courses.from_url')}}
                    </button>

                    {{-- Hidden file input for video --}}
                    <input type="file" name="promo_video" id="videoFileInput" class="d-none" accept="video/mp4,video/mov,video/avi,video/wmv">
                    <input type="hidden" name="video_source" id="videoSource" value="">
                    <input type="hidden" name="video_url" id="videoUrlInput" value="">
                </div>

                <div class="col-md-8">
                    <h6 class="mb-3 text-muted">{{__('courses.duration_3_to_10_minutes')}}</h6>
                    <div id="videoPreviewContainer" class="preview-container">
                        @if($course->promo_video_url)
                        <video src="{{ $course->promo_video_url }}" controls id="videoPreview" class="preview-video"></video>
                        @else
                        <video src="" controls id="videoPreview" class="preview-video d-none"></video>
                        @endif
                        <div id="videoPlaceholder" class="preview-placeholder {{ $course->promo_video_url ? 'd-none' : '' }}">
                            <i class="fa fa-video fa-4x mb-3 text-muted"></i>
                            <p class="text-muted">{{__('courses.upload_promo_video')}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <button type="reset" class="btn btn-warning">
                <i class="fa fa-rotate-left me-2"></i>{{__('courses.reset')}}
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save me-2"></i>{{__('courses.update')}}
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
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .preview-image,
        .preview-video {
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
        }
    </style>

    {{-- Include JS file --}}
    <script src="{{ asset('js/promotion-upload.js') }}" defer></script>
</div>