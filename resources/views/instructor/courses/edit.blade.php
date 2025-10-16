@extends('layouts.instructor-wide')

@push('styles')
<style>
    .ck-editor__editable {
        min-height: 200px;
    }

    .ck.ck-editor {
        margin-bottom: 1rem;
    }

    #updateCourseForm ul.nav {
        list-style: none;
        border-radius: 1rem;
        border: 1px solid #dee2e6;
        background-color: #fff;
        transition: all 0.3s ease-in-out;
        overflow: auto;
    }

    #updateCourseForm #form-sections:hover,
    #updateCourseForm ul.nav:hover {
        box-shadow: 0 0 0.5rem 0.1rem rgba(0, 0, 0, 0.1);
    }

    #updateCourseForm .form-section .form-section-title,
    #updateCourseForm ul.nav h5 {
        padding: 1rem;
        text-align: center;
        border-block-end: 1px solid #dee2e6;
        background-color: #f9f9f9;
    }

    #updateCourseForm ul.nav h5:first-child {
        border-top: 0;
        text-align: center;
    }

    #updateCourseForm ul.nav li {
        cursor: pointer;
        list-style: none;
        padding: 0.5rem 1rem;
        transition: background-color 0.2s ease-in-out;
        margin-bottom: 0.25rem;
    }

    #updateCourseForm ul.nav li:hover {
        background-color: #f1f1f1;
    }

    #updateCourseForm ul.nav li.active {
        background-color: #c5705d;
        color: #e9e2d5;
        text-shadow: 1px 1px 1px #000;

    }

    #updateCourseForm #form-sections {
        border-radius: 1rem;
        border: 1px solid #dee2e6;
        background-color: #fff;
        transition: all 0.3s ease-in-out;
        overflow: auto;
    }

    #updateCourseForm .form-section {
        display: none;
    }

    #updateCourseForm .form-section.active {
        display: block;
    }

    #ads-component {
        box-shadow: 0 0 2px 1px #eee;
        transition: all 0.3s ease-in-out;
    }

    #ads-component:hover {
        box-shadow: 0 0 10px 3px #ccc;
    }
</style>
@endpush

@section('title', __('instructor.edit_course'))

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}">{{ __('instructor.instructor_dashboard') }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('instructor.courses.index') }}">{{ __('instructor.my_courses') }}</a></li>
<li class="breadcrumb-item active">{{ __('instructor.edit_course') }}</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center pt-3 mb-4">
    <div id="updateCourseForm" class="container pt-5">
        <x-page-title
            title="{{__('courses.edit_course_main_heading')}}"
            description="{{__('courses.edit_course_main_description')}}"
            icon="fa fa-home"
            btn_url="{{route('instructor.courses.index')}}"
            btn_text="{{__('courses.back_to_courses')}}" />
        <div class="row ">
            <div class="col col-3">
                <ul class="nav flex-column border-0">
                    <!-- Planning -->
                    <h5 class="primary-gradient-bg">{{__('courses.sidenav.plan_your_course')}}</h5>
                    <li class="active" data-target="#generalInfo"><i class="fa fa-circle-stop"></i><span>{{__('courses.sidenav.general_info')}}</span></li>
                    <li class="" data-target="#intendedLearners"><i class="fa fa-circle-stop"></i><span>{{__('courses.sidenav.intended_learners')}}</span></li>
                    <li data-target="#courseLayout"><i class="fa fa-circle-stop"></i><span>{{__('courses.sidenav.course_layout')}}</span></li>
                    <li data-target="#setupTestVideo"><i class="fa fa-circle-stop"></i><span>{{__('courses.sidenav.setup_test_video')}}</span></li>
                    <!-- Create Your Content -->
                    <h5 class="primary-gradient-bg">{{__('courses.sidenav.create_your_content')}}</h5>
                    <li data-target="#snapEdit"><i class="fa fa-circle-stop"></i><span>{{__('courses.sidenav.snap_edit')}}</span></li>
                    <li data-target="#curriculum"><i class="fa fa-circle-stop"></i><span>{{__('courses.sidenav.curriculum')}}</span></li>
                    <li data-target="#captions"><i class="fa fa-circle-stop"></i><span>{{__('courses.sidenav.captions')}}</span></li>
                    <li data-target="#accessibility"><i class="fa fa-circle-stop"></i><span>{{__('courses.sidenav.accessibility')}}</span></li>
                    <!-- Publish Your Course -->
                    <h5 class="primary-gradient-bg">{{__('courses.sidenav.publish_your_course')}}</h5>
                    <li data-target="#landingPage"><i class="fa fa-circle-stop"></i><span>{{__('courses.sidenav.landing_page')}}</span></li>
                    <li data-target="#pricing"><i class="fa fa-circle-stop"></i><span>{{__('courses.sidenav.pricing')}}</span></li>
                    <li data-target="#promotion"><i class="fa fa-circle-stop"></i><span>{{__('courses.sidenav.promotion')}}</span></li>
                    <li data-target="#courseMessages"><i class="fa fa-circle-stop"></i><span>{{__('courses.sidenav.course_messages')}}</span></li>
                    <li>
                        <form action="{{route('instructor.courses.send-for-review', $course)}}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-secondary my-3"><i class="fa fa-upload"></i>{{__('labels.send_for_review')}}</button>
                        </form>
                    </li>
                </ul>
            </div>
            <div class="col col-lg-9">
                <div id="form-sections" class="border-0">
                    <div id="generalInfo" class="form-section active">
                        <h4 class="form-section-title primary-gradient-bg">{{__('courses.general_info_title')}}</h4>

                        <div class="p-3">
                            <!-- Validation Errors -->
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <x-general-info
                                :course="$course"
                                :categories="$categories"
                                :action="route('instructor.courses.update.general-info', $course)" />
                        </div>
                    </div>

                    <div id="intendedLearners" class="form-section">
                        <h4 class="form-section-title">{{__('courses.intended_learners')}}</h4>
                        <div class="p-3">
                            <form action="{{ route('instructor.courses.update', $course) }}" method="POST" enctype="multipart/form-data" id="courseForm">
                                @csrf
                                @method('PUT')
                                <p class="form-notes m-3">{{__('courses.intended_learners_paragraph')}}</p>
                                <hr>
                                <x-add-to-list title="{{ __('courses.intended_learners_section_one_question') }}"
                                    description="{{ __('courses.intended_learners_section_one_description') }}" inputName="learning_objectives"
                                    placeholder="{{ __('courses.intended_learners_section_one_placeholder') }}" />
                                <x-add-to-list title="{{ __('courses.intended_learners_section_two_question') }}"
                                    description="{{ __('courses.intended_learners_section_two_description') }}" inputName="post_requirements"
                                    placeholder="{{ __('courses.intended_learners_section_two_placeholder') }}" />
                                <x-add-to-list title="{{ __('courses.intended_learners_section_three_question') }}"
                                    description="{{ __('courses.intended_learners_section_three_description') }}" inputName="course_audience"
                                    placeholder="{{ __('courses.intended_learners_section_three_placeholder') }}" />
                            </form>
                        </div>
                    </div>

                    <!-- Course Layout & instructions -->
                    <div id="courseLayout" class="form-section">
                        <h4 class="form-section-title">{{__('Course Layout')}}</h4>
                        <div class="p-3">
                            <section>
                                <div class="row">
                                    <div class="col col-md-7">
                                        <h4 class="pt-5">{{__('courses.teaching_center_section_title')}}</h4>
                                        <p class="pb-3">{{__('courses.teaching_center_section_paragraph')}}</p>
                                    </div>
                                    <div class="col col-md-5">
                                        <x-ads-component src="{{asset('images/teaching-center-ads-image.png')}}" alt="{{__('courses.teaching_center_ads_title')}}"
                                            head="{{__('courses.teaching_center_ads_title')}}" text="{{__('courses.teaching_center_ads_paragraph')}}"
                                            button_text="{{__('courses.teaching_center_ads_button')}}" btn_link="{{__('courses.teaching_center_ads_link')}}" />
                                    </div>
                                </div>
                                <hr>

                            </section>

                            <!-- Course Creation Tips Section -->
                            <section>
                                <h4>{{__('courses.tips_title')}}</h4>
                                @foreach (__('courses.plan_your_course_tips_subtitles') as $tip)
                                <b>{{ $tip[0] }}</b>
                                <p>{{ $tip[1] }}</p>

                                <hr class="mt-1 mb-3 p-0">
                                @endforeach
                            </section>

                            <!-- Course Requirements Section -->
                            <section>
                                <h4>{{__('courses.requirements-title')}}</h4>
                                <ul>
                                    @foreach (__('courses.plan_your_course_requirements') as $requirement)
                                    <li>{{ $requirement }}</li>
                                    @endforeach
                                </ul>
                                <hr class="mt-1 mb-3 p-0">
                            </section>

                            <!-- Course Resources Section -->
                            <section>
                                <h4>{{__('courses.resources_title')}}</h4>

                                @foreach (__('courses.plan_your_course_resources') as $resource)
                                <a href="{{ $resource[1] }}"><b>{{ $resource[0] }}</b></a>
                                <p>{{ $resource[2] }}</p>
                                @endforeach


                            </section>
                        </div>
                    </div>

                    <!-- Setup & Test Videos -->
                    <div id="setupTestVideo" class="form-section">
                        <h4 class="form-section-title">{{__('courses.setup_test_video')}}</h4>
                        <div class="p-3">
                            <section>
                                <div class="row">
                                    <div class="col col-md-7">
                                        <h4 class="pt-5">{{__('courses.video_studio_section_title')}}</h4>
                                        <p class="pb-3">{{__('courses.video_studio_section_paragraph')}}</p>
                                    </div>
                                    <div class="col col-md-5">
                                        <x-ads-component src="{{asset('images/video-making-ads.png')}}" alt="{{__('courses.video_studio_ads_title')}}"
                                            head="{{__('courses.video_studio_ads_title')}}" text="{{__('courses.video_studio_ads_paragraph')}}"
                                            button_text="{{__('courses.video_studio_ads_button')}}" btn_link="{{__('courses.video_studio_ads_link')}}" />
                                    </div>
                                </div>
                                <hr>

                            </section>

                            <!-- Setup & Test Course Videos Section -->
                            <section>
                                <h4>{{__('courses.tips_title')}}</h4>
                                @foreach (__('courses.setup_test_video_tips_subtitles') as $tip)
                                <b>{{ $tip[0] }}</b>
                                <p>{{ $tip[1] }}</p>

                                <hr class="mt-1 mb-3 p-0">
                                @endforeach
                            </section>

                            <!-- Course Requirements Section -->
                            <section>
                                <h4>{{__('courses.requirements_title')}}</h4>
                                <ul>
                                    @foreach (__('courses.setup_test_video_requirements') as $requirement)
                                    <li>{{ $requirement }}</li>
                                    @endforeach
                                </ul>
                                <hr class="mt-1 mb-3 p-0">
                            </section>

                            <!-- Course Requirements Section -->
                            <section>
                                <h4>{{__('courses.resources_title')}}</h4>

                                @foreach (__('courses.setup_test_video_resources') as $resource)
                                <a href="{{ $resource[1] }}"><b>{{ $resource[0] }}</b></a>
                                <p>{{ $resource[2] }}</p>
                                @endforeach

                            </section>
                        </div>
                    </div>

                    <!-- Snap & Edit Videos -->
                    <div id="snapEdit" class="form-section">
                        <h4 class="form-section-title">{{__('courses.snap_edit')}}</h4>
                        <div class="p-3">
                            <section>
                                <div class="row">
                                    <div class="col col-md-7">
                                        <h4 class="pt-5">{{__('courses.video_studio_section_title')}}</h4>
                                        <p class="pb-3">{{__('courses.video_studio_section_paragraph')}}</p>
                                    </div>
                                    <div class="col col-md-5">
                                        <x-ads-component src="{{asset('images/creators-community.png')}}" alt="{{__('courses.share_knowledge_ads_title')}}"
                                            head="{{__('courses.share_knowledge_ads_title')}}" text="{{__('courses.share_knowledge_ads_paragraph')}}"
                                            button_text="{{__('courses.share_knowledge_ads_button')}}" btn_link="{{__('courses.share_knowledge_ads_link')}}" />
                                    </div>
                                </div>
                                <hr>

                            </section>

                            <!-- Course Creation Tips Section -->
                            <section>
                                <h4>{{__('courses.tips_title')}}</h4>
                                @foreach (__('courses.snap_edit_video_tips_subtitles') as $tip)
                                <b>{{ $tip[0] }}</b>
                                <p>{{ $tip[1] }}</p>

                                <hr class="mt-1 mb-3 p-0">
                                @endforeach
                            </section>

                            <!-- Course Requirements Section -->
                            <section>
                                <h4>{{__('courses.requirements_title')}}</h4>
                                <ul>
                                    @foreach (__('courses.snap_edit_video_requirements') as $requirement)
                                    <li>{{ $requirement }}</li>
                                    @endforeach
                                </ul>
                                <hr class="mt-1 mb-3 p-0">
                            </section>

                            <!-- Course Requirements Section -->
                            <section>
                                <h4>{{__('courses.resources_title')}}</h4>

                                @foreach (__('courses.snap_edit_video_resources') as $resource)
                                <a href="{{ $resource[1] }}"><b>{{ $resource[0] }}</b></a>
                                <p>{{ $resource[2] }}</p>
                                @endforeach


                            </section>

                        </div>
                    </div>



                    <!-- Snap & Edit Videos -->
                    <div id="curriculum" class="form-section">
                        <h4 class="form-section-title">{{__('courses.curriculum')}}</h4>
                        <div class="p-3">
                            <section>
                                <x-dismissable-note paragraph_text="{{__('courses.curriculum_paragraph')}}" btn_text="{{__('labels.dismiss')}}" />
                            </section>
                            <x-curriculum-builder :courseId="$course->id" :sections="$course->sections" />
                        </div>
                    </div>

                    <!-- Snap & Edit Videos -->
                    <div id="captions" class="form-section">
                        <h4 class="form-section-title">{{__('courses.captions')}}</h4>
                        <div class="p-3">
                            <section>
                                <x-dismissable-note paragraph_text="{{__('courses.curriculum_paragraph')}}" btn_text="{{__('labels.dismiss')}}" />
                            </section>
                            <x-captions-builder :courseId="$course->id" :sections="$course->sections" />
                        </div>
                    </div>

                    <!-- Snap & Edit Videos -->
                    <div id="accessibility" class="form-section">
                        <h4 class="form-section-title">{{__('courses.accessibility')}}</h4>
                        <div class="p-3">

                            <x-accessibility :course="$course" />
                        </div>
                    </div>

                    <!-- Landing Page -->
                    <div id="landingPage" class="form-section">
                        <h4 class="form-section-title">{{__('courses.landing_page')}}</h4>
                        <div class="p-3">
                            <section>
                                <x-dismissable-note paragraph_text="{{__('courses.landing_page_paragraph')}}" btn_text="{{__('labels.dismiss')}}" />
                            </section>
                            <x-landingPage :course="$course" />
                        </div>
                    </div>

                    <!-- promotion -->
                    <div id="pricing" class="form-section">
                        <h4 class="form-section-title">{{__('courses.pricing')}}</h4>

                    </div>

                    <!-- promotion -->
                    <div id="promotion" class="form-section">
                        <h4 class="form-section-title">{{__('courses.promotion')}}</h4>
                        <div class="p-3">
                            <section>
                                <x-dismissable-note paragraph_text="{{__('courses.promotion_paragraph')}}" btn_text="{{__('labels.dismiss')}}" />
                            </section>
                            <x-promotion-info :course="$course" />
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>


</div>

@endsection

@push('scripts')
<script>
// Media Uploader - Banner and Video Upload Handler
(function() {
    console.log("Media uploader script loaded!");
    
    // Wait a bit to ensure DOM is fully ready
    setTimeout(function() {
        // ==================== HELPER FUNCTIONS ====================
        function displayBannerPreview(url) {
            const container = document.querySelector(".banner-preview-container");
            const placeholder = document.getElementById("bannerPlaceholder");

            if (placeholder) {
                placeholder.style.display = "none";
            }

            let img = document.getElementById("bannerPreview");
            if (!img) {
                img = document.createElement("img");
                img.id = "bannerPreview";
                img.style.maxWidth = "100%";
                img.style.maxHeight = "100%";
                img.style.objectFit = "contain";
                container.appendChild(img);
            }

            img.src = url;
            img.alt = "Banner Preview";
            img.style.display = "block";
        }

        function displayVideoPreview(url) {
            const container = document.querySelector(".video-preview-container");
            const placeholder = document.getElementById("videoPlaceholder");

            if (placeholder) {
                placeholder.style.display = "none";
            }

            let video = document.getElementById("videoPreview");
            if (!video) {
                video = document.createElement("video");
                video.id = "videoPreview";
                video.controls = true;
                video.style.maxWidth = "100%";
                video.style.maxHeight = "100%";
                container.appendChild(video);
            }

            video.src = url;
            video.style.display = "block";
        }

        // ==================== BANNER UPLOAD ====================
        // Use event delegation on document to catch the click
        document.addEventListener("click", function(e) {
            // Check if clicked element is the upload banner button
            if (e.target && (e.target.id === "uploadBannerBtn" || e.target.closest("#uploadBannerBtn"))) {
                e.preventDefault();
                e.stopPropagation();
                console.log("Banner button clicked via delegation!");
                
                const bannerFileInput = document.getElementById("bannerFileInput");
                if (bannerFileInput) {
                    console.log("Opening banner file picker...");
                    bannerFileInput.click();
                } else {
                    console.error("Banner file input not found!");
                }
                return false;
            }
            
            // Check if clicked element is the upload video button
            if (e.target && (e.target.id === "uploadVideoBtn" || e.target.closest("#uploadVideoBtn"))) {
                e.preventDefault();
                e.stopPropagation();
                console.log("Video button clicked via delegation!");
                
                const videoFileInput = document.getElementById("videoFileInput");
                if (videoFileInput) {
                    console.log("Opening video file picker...");
                    videoFileInput.click();
                } else {
                    console.error("Video file input not found!");
                }
                return false;
            }
        });
        
        const uploadBannerBtn = document.getElementById("uploadBannerBtn");
        const bannerFileInput = document.getElementById("bannerFileInput");
        
        console.log("Upload button:", uploadBannerBtn);
        console.log("File input:", bannerFileInput);

        if (bannerFileInput) {
            console.log("Adding change listener to banner file input...");

            bannerFileInput.addEventListener("change", (e) => {
                const file = e.target.files[0];
                console.log("Banner file selected:", file);
                if (file && file.type.startsWith("image/")) {
                    displayBannerPreview(URL.createObjectURL(file));
                    document.getElementById("bannerSource").value = "device";
                } else {
                    alert("Please select a valid image file.");
                }
            });
        } else {
            console.error("Banner file input not found!");
        }

        // ==================== VIDEO UPLOAD ====================
        const videoFileInput = document.getElementById("videoFileInput");
        
        if (videoFileInput) {
            console.log("Adding change listener to video file input...");

            videoFileInput.addEventListener("change", (e) => {
                const file = e.target.files[0];
                console.log("Video file selected:", file);
                if (file && file.type.startsWith("video/")) {
                    displayVideoPreview(URL.createObjectURL(file));
                    document.getElementById("videoSource").value = "device";
                } else {
                    alert("Please select a valid video file.");
                }
            });
        } else {
            console.error("Video file input not found!");
        }

        // ==================== URL MODALS ====================
        // Banner URL Modal
        const urlBannerBtn = document.getElementById("urlBannerBtn");
        const submitBannerUrl = document.getElementById("submitBannerUrl");
        const bannerUrlInput = document.getElementById("bannerUrlInput");

        if (urlBannerBtn) {
            urlBannerBtn.addEventListener("click", (e) => {
                e.preventDefault();
                e.stopPropagation();
                const modalElement = document.getElementById("urlBannerModal");
                if (modalElement) {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                }
            });
        }

        if (submitBannerUrl && bannerUrlInput) {
            submitBannerUrl.addEventListener("click", () => {
                const url = bannerUrlInput.value.trim();
                if (!url) {
                    alert("Please enter a URL");
                    return;
                }
                
                if (!isValidImageUrl(url)) {
                    alert("Invalid image URL. Please enter a valid image URL.");
                    return;
                }

                displayBannerPreview(url);
                document.getElementById("bannerSource").value = "url";
                document.getElementById("bannerUrl").value = url;
                
                const modalElement = document.getElementById("urlBannerModal");
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();
                }
                bannerUrlInput.value = "";
            });
        }

        // Video URL Modal
        const urlVideoBtn = document.getElementById("urlVideoBtn");
        const submitVideoUrl = document.getElementById("submitVideoUrl");
        const videoUrlInput = document.getElementById("videoUrlInput");

        if (urlVideoBtn) {
            urlVideoBtn.addEventListener("click", (e) => {
                e.preventDefault();
                e.stopPropagation();
                const modalElement = document.getElementById("urlVideoModal");
                if (modalElement) {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                }
            });
        }

        if (submitVideoUrl && videoUrlInput) {
            submitVideoUrl.addEventListener("click", () => {
                const url = videoUrlInput.value.trim();
                if (!url) {
                    alert("Please enter a URL");
                    return;
                }
                
                if (!isValidVideoUrl(url)) {
                    alert("Invalid video URL. Please enter a valid video URL.");
                    return;
                }

                displayVideoPreview(url);
                document.getElementById("videoSource").value = "url";
                document.getElementById("videoUrl").value = url;
                
                const modalElement = document.getElementById("urlVideoModal");
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) modal.hide();
                }
                videoUrlInput.value = "";
            });
        }

        // ==================== CLIPBOARD PASTE ====================
        const pasteBannerBtn = document.getElementById("pasteBannerBtn");

        if (pasteBannerBtn) {
            pasteBannerBtn.addEventListener("click", async (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                try {
                    const clipboardItems = await navigator.clipboard.read();
                    let imageFound = false;

                    for (const item of clipboardItems) {
                        for (const type of item.types) {
                            if (type.startsWith("image/")) {
                                const blob = await item.getType(type);
                                const url = URL.createObjectURL(blob);
                                displayBannerPreview(url);
                                document.getElementById("bannerSource").value = "clipboard";

                                // Convert blob to file and set to input
                                const file = new File([blob], "clipboard-image.png", { type: blob.type });
                                const dataTransfer = new DataTransfer();
                                dataTransfer.items.add(file);
                                bannerFileInput.files = dataTransfer.files;

                                imageFound = true;
                                break;
                            }
                        }
                        if (imageFound) break;
                    }

                    if (!imageFound) {
                        alert("No image found in clipboard. Please copy an image first.");
                    }
                } catch (error) {
                    alert("Error accessing clipboard: " + error.message);
                }
            });
        }

        // ==================== LIBRARY MODALS ====================
        // Banner Library
        const libraryBannerBtn = document.getElementById("libraryBannerBtn");
        
        if (libraryBannerBtn) {
            libraryBannerBtn.addEventListener("click", (e) => {
                e.preventDefault();
                e.stopPropagation();
                const modalElement = document.getElementById("libraryBannerModal");
                if (modalElement) {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                    loadMediaLibrary("image", "bannerLibraryContent");
                }
            });
        }

        // Video Library
        const libraryVideoBtn = document.getElementById("libraryVideoBtn");
        
        if (libraryVideoBtn) {
            libraryVideoBtn.addEventListener("click", (e) => {
                e.preventDefault();
                e.stopPropagation();
                const modalElement = document.getElementById("libraryVideoModal");
                if (modalElement) {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                    loadMediaLibrary("video", "videoLibraryContent");
                }
            });
        }

        // ==================== VALIDATION HELPERS ====================
        function isValidImageUrl(url) {
            const imageExtensions = [".jpg", ".jpeg", ".png", ".gif", ".bmp", ".webp", ".svg"];
            const urlLower = url.toLowerCase();
            return imageExtensions.some(ext => urlLower.includes(ext)) || url.startsWith("data:image/");
        }

        function isValidVideoUrl(url) {
            const videoExtensions = [".mp4", ".webm", ".ogg", ".mov", ".avi"];
            const urlLower = url.toLowerCase();
            return videoExtensions.some(ext => urlLower.includes(ext)) || url.startsWith("data:video/");
        }

        // ==================== LOAD MEDIA LIBRARY ====================
        async function loadMediaLibrary(type, containerId) {
            const container = document.getElementById(containerId);
            
            if (!container) {
                console.error("Container not found:", containerId);
                return;
            }

            try {
                // For now, show a message that library is not implemented yet
                // You can implement the API endpoint later
                container.innerHTML = `
                    <div class="col-12 text-center p-5">
                        <i class="fa fa-folder-open fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Media library feature coming soon!</p>
                        <p class="text-muted small">You can upload files from your device or use URLs for now.</p>
                    </div>
                `;
                
                // Uncomment this when you have the API endpoint ready
                /*
                const response = await fetch(`/api/media-library?type=${type}`);
                const data = await response.json();

                if (data.success && data.files.length > 0) {
                    let html = "";
                    data.files.forEach((file) => {
                        html += `
                            <div class="col-md-3 mb-3">
                                <div class="card media-item" style="cursor: pointer;" data-url="${file.url}" data-type="${type}">
                                    ${type === "image" 
                                        ? `<img src="${file.url}" class="card-img-top" alt="${file.name}" style="height: 150px; object-fit: cover;">`
                                        : `<video src="${file.url}" class="card-img-top" style="height: 150px; object-fit: cover;"></video>`
                                    }
                                    <div class="card-body p-2">
                                        <small class="text-truncate d-block">${file.name}</small>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    container.innerHTML = html;

                    // Add click handlers
                    container.querySelectorAll(".media-item").forEach((item) => {
                        item.addEventListener("click", () => {
                            const url = item.dataset.url;
                            const mediaType = item.dataset.type;

                            if (mediaType === "image") {
                                displayBannerPreview(url);
                                document.getElementById("bannerSource").value = "library";
                                document.getElementById("bannerUrl").value = url;
                                bootstrap.Modal.getInstance(document.getElementById("libraryBannerModal")).hide();
                            } else {
                                displayVideoPreview(url);
                                document.getElementById("videoSource").value = "library";
                                document.getElementById("videoUrl").value = url;
                                bootstrap.Modal.getInstance(document.getElementById("libraryVideoModal")).hide();
                            }
                        });
                    });
                } else {
                    container.innerHTML = '<div class="col-12 text-center p-5"><p class="text-muted">No media files found in your library.</p></div>';
                }
                */
            } catch (error) {
                container.innerHTML = `<div class="col-12 text-center p-5"><p class="text-danger">Error loading media library: ${error.message}</p></div>`;
            }
        }

    }, 100); // End setTimeout
})(); // End IIFE
</script>
<script>
    $(document).ready(function() {
        // Reusable script for all add-to-list components
        $('.add-to-list-component').each(function() {
            const component = $(this);
            const input = component.find('.item-input');
            const submitBtn = component.find('.submit-item');
            const list = component.find('.item-list');
            const counter = component.find('.item-counter');
            const hiddenInput = component.find('.items-json-input');
            const maxLength = input.attr('maxlength') || 150;

            let items = [];

            const updateHiddenInput = () => {
                hiddenInput.val(JSON.stringify(items));
            };

            const renderList = () => {
                list.empty();
                items.forEach((item, index) => {
                    const listItem = $(`
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>${item}</span>
                            <button type="button" class="btn btn-sm btn-outline-danger remove-item" data-index="${index}">&times;</button>
                        </li>
                    `);
                    list.append(listItem);
                });
                updateHiddenInput();
            };

            const addItem = () => {
                const value = input.val().trim();
                if (value !== '' && !items.includes(value)) {
                    items.push(value);
                    input.val('');
                    counter.text(maxLength);
                    renderList();
                }
            };

            submitBtn.on('click', addItem);

            input.on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    addItem();
                }
            });

            input.on('keyup', function() {
                const remaining = maxLength - $(this).val().length;
                counter.text(remaining);
            });

            list.on('click', '.remove-item', function() {
                const index = $(this).data('index');
                items.splice(index, 1);
                renderList();
            });
        });

        // Set the first nav item and section as active on page load
        if ($('.nav li[data-target]').length > 0) {
            $('.nav li[data-target]').first().addClass('active');
            const firstTarget = $('.nav li[data-target]').first().data('target');
            $(firstTarget).addClass('active');
        }

        // Navigation functionality for course sections
        $('.nav li[data-target]').on('click', function() {
            const target = $(this).data('target');

            // Remove active class from all sections and nav items
            $('.form-section').removeClass('active');
            $('.nav li').removeClass('active');

            // Add active class to clicked nav item and target section
            $(this).addClass('active');
            $(target).addClass('active');
        });
    });
</script>
@endpush