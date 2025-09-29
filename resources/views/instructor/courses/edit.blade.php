@extends('layouts.instructor-wide')

@push('styles')
<style>
    .ck-editor__editable {
        min-height: 200px;
    }

    .ck.ck-editor {
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('title', __('instructor.edit_course'))

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}">{{ __('instructor.instructor_dashboard') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('instructor.courses.index') }}">{{ __('instructor.my_courses') }}</a></li>
        <li class="breadcrumb-item active">{{ __('instructor.edit_course') }}</li>
    </ol>
</nav>
@endsection

@section('content')
<style>
    body {
        background-color: #f9f9f9;
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
        border-block: 1px solid #dee2e6;
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
        background-color: #343a40;
        color: #fff;
        font-weight: bold;
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
<div class="d-flex justify-content-between align-items-center pt-5 mb-4">
    <div id="updateCourseForm" class="container pt-5">
        <h3 class="mb-4 "><i class="fa fa-edit outline-secondary"></i> {{__('courses.edit_course_main_heading')}}</h3>
        <form action="{{ route('instructor.courses.update', $course) }}" method="POST" enctype="multipart/form-data" id="courseForm">
            @csrf
            @method('PUT')
            <div class="row ">
                <div class="col col-3">
                    <ul class="nav flex-column border">
                        <!-- Planning -->
                        <h5>{{__('courses.sidenav.plan_your_course')}}</h5>
                        <li class="" data-target="#intendedLearners"><i class="fa fa-circle-stop"></i>{{__('courses.sidenav.intended_learners')}}</li>
                        <li data-target="#courseLayout"><i class="fa fa-circle-stop"></i>{{__('courses.sidenav.course_layout')}}</li>
                        <li data-target="#setupTestVideo"><i class="fa fa-circle-stop"></i>{{__('courses.sidenav.setup_test_video')}}</li>
                        <!-- Create Your Content -->
                        <h5>{{__('Create Your Content')}}</h5>
                        <li data-target="#snapEdit"><i class="fa fa-circle-stop"></i>{{__('courses.sidenav.snap_edit')}}</li>
                        <li class="active" data-target="#generalInfo"><i class="fa fa-circle-stop"></i>{{__('courses.sidenav.general_info')}}</li>
                        <li data-target="#curriculum"><i class="fa fa-circle-stop"></i>{{__('courses.sidenav.curriculum')}}</li>
                        <li data-target="#captions"><i class="fa fa-circle-stop"></i>{{__('courses.sidenav.captions')}}</li>
                        <li data-target="#accessibility"><i class="fa fa-circle-stop"></i>{{__('courses.sidenav.accessibility')}}</li>
                        <!-- Publish Your Course -->
                        <h5>{{__('Publish Your Course')}}</h5>
                        <li data-target="#landingPage"><i class="fa fa-circle-stop"></i>{{__('courses.sidenav.landing_page')}}</li>
                        <li data-target="#pricing"><i class="fa fa-circle-stop"></i>{{__('courses.sidenav.pricing')}}</li>
                        <li data-target="#promotion"><i class="fa fa-circle-stop"></i>{{__('courses.sidenav.promotion')}}</li>
                        <li data-target="#courseMessages"><i class="fa fa-circle-stop"></i>{{__('courses.sidenav.course_messages')}}</li>
                        <li><button type="submit" class="btn btn-secondary my-3"><i class="fa fa-upload"></i>{{__('labels.send_for_review')}}</button></li>
                    </ul>
                </div>
                <div class="col col-lg-9">
                    <div id="form-sections">

                        <div id="intendedLearners" class="form-section">
                            <h4 class="form-section-title">{{__('courses.intended_learners')}}</h4>
                            <div class="p-3">
                                <p class="form-notes m-3">{{__('courses.intended_learners_paragraph')}}</p>
                                <hr>
                                <x-add-to-list
                                    title="{{ __('courses.intended_learners_section_one_question') }}"
                                    description="{{ __('courses.intended_learners_section_one_description') }}"
                                    inputName="learning_objectives"
                                    placeholder="{{ __('courses.intended_learners_section_one_placeholder') }}" />
                                <x-add-to-list
                                    title="{{ __('courses.intended_learners_section_two_question') }}"
                                    description="{{ __('courses.intended_learners_section_two_description') }}"
                                    inputName="post_requirements"
                                    placeholder="{{ __('courses.intended_learners_section_two_placeholder') }}" />
                                <x-add-to-list
                                    title="{{ __('courses.intended_learners_section_three_question') }}"
                                    description="{{ __('courses.intended_learners_section_three_description') }}"
                                    inputName="course_audience"
                                    placeholder="{{ __('courses.intended_learners_section_three_placeholder') }}" />
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
                                            <x-ads-component
                                                src="{{asset('images/teaching-center-ads-image.png')}}"
                                                alt="{{__('courses.teaching_center_ads_title')}}"
                                                head="{{__('courses.teaching_center_ads_title')}}"
                                                text="{{__('courses.teaching_center_ads_paragraph')}}"
                                                button_text="{{__('courses.teaching_center_ads_button')}}"
                                                btn_link="{{__('courses.teaching_center_ads_link')}}" />
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

                                <!-- Course Requirements Section -->
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
                                            <x-ads-component
                                                src="{{asset('images/video-making-ads.png')}}"
                                                alt="{{__('courses.video_studio_ads_title')}}"
                                                head="{{__('courses.video_studio_ads_title')}}"
                                                text="{{__('courses.video_studio_ads_paragraph')}}"
                                                button_text="{{__('courses.video_studio_ads_button')}}"
                                                btn_link="{{__('courses.video_studio_ads_link')}}" />
                                        </div>
                                    </div>
                                    <hr>

                                </section>

                                <!-- Course Creation Tips Section -->
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
                                            <x-ads-component
                                                src="{{asset('images/creators-community.png')}}"
                                                alt="{{__('courses.share_knowledge_ads_title')}}"
                                                head="{{__('courses.share_knowledge_ads_title')}}"
                                                text="{{__('courses.share_knowledge_ads_paragraph')}}"
                                                button_text="{{__('courses.share_knowledge_ads_button')}}"
                                                btn_link="{{__('courses.share_knowledge_ads_link')}}" />
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

                        <div id="generalInfo" class="form-section active">
                            <h4 class="form-section-title">{{__('courses.general_info_title')}}</h4>
                            <h3 class="text-center">Student view</h3>
                            <div class="p-3">
                                <video
                                    id="my-video"
                                    class="video-js vjs-default-skin shadow rounded"
                                    controls
                                    preload="auto"
                                    width="500"
                                    height="300"
                                    poster="{{asset('images/platform-logo.jpg')}}"
                                    data-setup="{}">

                                    <source src="{{asset('images/GF_2026.mp4')}}" type="video/mp4">
                                    <track kind="subtitles" src="{{asset('images/GF_2026.mp4')}}" srclang="en" label="English">
                                </video>
                            </div>
                        </div>

                        <!-- Snap & Edit Videos -->
                        <div id="curriculum" class="form-section">
                            <h4 class="form-section-title">{{__('courses.curriculum')}}</h4>
                            <div class="p-3">
                                <section>
                                    <x-dismissable-note
                                        paragraph_text="{{__('courses.curriculum_paragraph')}}"
                                        btn_text="{{__('labels.dismiss')}}" />
                                </section>
                                <x-curriculum-builder :courseId="$course->id" :sections="$course->sections" />
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>


</div>

@endsection

@push('scripts')
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