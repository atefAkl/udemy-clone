{{-- جميع الدورات المسجلة للطالب --}}
<div class="tab-pane fade show active" id="all-courses" role="tabpanel" aria-labelledby="all-courses-tab">
    <h4 class="mb-4">{{ __('courses.all_courses') }}</h4>
    @if(isset($enrolledCourses) && $enrolledCourses->count())
    <div class="row">
        @foreach($enrolledCourses as $course)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <img src="{{ $course->thumbnail_url }}" class="card-img-top" alt="{{ $course->title }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $course->title }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($course->description, 80) }}</p>
                    <a href="{{ route('courses.show', $course) }}" class="btn btn-primary btn-sm">{{ __('student.view_course') }}</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div>
        <b>{{ __('courses.no_enrollments') }}</b>
        <br>
        {{ __('courses.see_more_courses') }} -
        <a href="{{ route('courses.index') }}" class="btn btn-primary btn-sm">{{ __('courses.view_courses') }}</a>
    </div>
    @endif
</div>