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