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
        <h3 class="mb-4 "><i class="fa fa-edit outline-secondary"></i> {{__('Edit Course Contents')}}</h3>
        <form action="{{ route('instructor.courses.update', $course) }}" method="POST" enctype="multipart/form-data" id="courseForm">
            @csrf
            @method('PUT')
            <div class="row ">
                <div class="col col-3">
                    <ul class="nav flex-column border">
                        <!-- Planning -->
                        <h5>{{__('Plan your course')}}</h5>
                        <li class="active" data-target="#intendedLearners"><i class="fa fa-circle-stop"></i>{{__('Intended Learners')}}</li>
                        <li data-target="#courseLayout"><i class="fa fa-circle-stop"></i>{{__('Course layout')}}</li>
                        <li data-target="#setupTestVideo"><i class="fa fa-circle-stop"></i>{{__('Setup & test video')}}</li>
                        <!-- Create Your Content -->
                        <h5>{{__('Create Your Content')}}</h5>
                        <li data-target="#snapEdit"><i class="fa fa-circle-stop"></i>{{__('Snap & Edit')}}</li>
                        <li data-target="#curriculum"><i class="fa fa-circle-stop"></i>{{__('Curriculum')}}</li>
                        <li data-target="#captions"><i class="fa fa-circle-stop"></i>{{__('Captions(Optional)')}}</li>
                        <li data-target="#accessibility"><i class="fa fa-circle-stop"></i>{{__('Accessibility(Optional)')}}</li>
                        <!-- Publish Your Course -->
                        <h5>{{__('Publish Your Course')}}</h5>
                        <li data-target="#landingPage"><i class="fa fa-circle-stop"></i>{{__('Landing Page')}}</li>
                        <li data-target="#pricing"><i class="fa fa-circle-stop"></i>{{__('Pricing')}}</li>
                        <li data-target="#promotion"><i class="fa fa-circle-stop"></i>{{__('Promotion')}}</li>
                        <li data-target="#courseMessages"><i class="fa fa-circle-stop"></i>{{__('Course Messages')}}</li>
                        <li><button type="submit" class="btn btn-secondary my-3"><i class="fa fa-upload"></i>{{__('send for review')}}</button></li>
                    </ul>
                </div>
                <div class="col col-lg-9">
                    <div id="form-sections">

                        <div id="intendedLearners" class="form-section active">
                            <h5 class="form-section-title">{{__('Intended Learners')}}</h5>
                            <div class="p-3">
                                <p class="form-notes m-3">{{__('The following descriptions will be publicly visible on your Course Landing Page and will have a direct impact on your course performance. These descriptions will help learners decide if your course is right for them.')}}</p>
                                <hr>
                                <x-add-to-list
                                    title="{{ __('What are the requirements or prerequisites for taking your course?') }}"
                                    description="{{ __('You must enter at least 4 learning objectives or outcomes that learners can expect to achieve after completing your course.') }}"
                                    inputName="learning_objectives"
                                    placeholder="{{ __('Example: Define the roles and responsibilities of a project manager') }}" />
                                <x-add-to-list
                                    title="{{ __('What will Students learn in your course?') }}"
                                    description="{{ __('List the required skills, experience, tools or equipment learners should have prior to taking your course. If there are no requirements, use this space as an opportunity to lower the barrier for beginners.') }}"
                                    inputName="post_requirements"
                                    placeholder="{{ __('Example: No programming experience needed.') }}" />
                                <x-add-to-list
                                    title="{{ __('What is your course for?') }}"
                                    description="{{ __('Write a clear description of the intended learners for your course who will find your course content valuable. This will help you attract the right learners to your course.') }}"
                                    inputName="course_audience"
                                    placeholder="{{ __('Example: Beginner Python developers curious about data science.') }}" />
                            </div>
                        </div>

                        <!-- Course Layout & instructions -->
                        <div id="courseLayout" class="form-section">
                            <h5 class="form-section-title">{{__('Course Layout')}}</h5>
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

                        <!-- Course Layout & instructions -->
                        <div id="setupTestVideo" class="form-section">
                            <h5 class="form-section-title">{{__('courses.setup_test_video')}}</h5>
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