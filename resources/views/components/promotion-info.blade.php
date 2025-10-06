@props(['course'])

<div>
    <form action="{{ route('instructor.courses.update.promotion-info', [$course->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

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
                        @if($course->banner_url)
                        <img src="{{ $course->banner_url }}" alt="Banner" id="bannerPreview" style="max-width: 100%; max-height: 100%; object-fit: contain;">
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
                        @if($course->promo_video_url)
                        <video src="{{ $course->promo_video_url }}" controls id="videoPreview" style="max-width: 100%; max-height: 100%;"></video>
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

        <div class="btns d-flex justify-content-end gap-2 mt-3">
            <button type="reset" class="btn btn-sm btn-warning">{{__('courses.reset')}}</button>
            <button type="submit" class="btn btn-sm btn-primary">{{__('courses.update')}}</button>
        </div>
    </form>
</div>

<script>
    // Paste the entire JavaScript block here
</script>