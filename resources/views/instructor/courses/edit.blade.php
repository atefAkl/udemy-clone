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
<div class="d-flex justify-content-between align-items-center pt-5 mb-4">
    <div class="container pt-5">
        <div class="row">
            <div class="col col-3">
                <ul class="nav flex-column ">
                    <h5>{{__('Plan your course')}}</h5>
                    <li><i class="fa fa-circle-stop text-muted"></i>{{__('Intended Learners')}}</li>
                    <li><i class="fa fa-circle-stop text-muted"></i>{{__('Course layout')}}</li>
                    <li><i class="fa fa-circle-stop text-muted"></i>{{__('Setup & test video')}}</li>
                    <!-- Create Your Content -->
                    <h5>{{__('Create Your Content')}}</h5>
                    <li><i class="fa fa-circle-stop text-muted"></i>{{__('Snap & Edit')}}</li>
                    <li><i class="fa fa-circle-stop text-muted"></i>{{__('Curriculum')}}</li>
                    <li><i class="fa fa-circle-stop text-muted"></i>{{__('Captions(Optional)')}}</li>
                    <li><i class="fa fa-circle-stop text-muted"></i>{{__('Accessibility(Optional)')}}</li>
                </ul>
            </div>
        </div>
        <div class="col col-lg-9">
            <form action="{{ route('instructor.courses.update', $course) }}" method="POST" enctype="multipart/form-data" id="courseForm">
                @csrf
                @method('PUT')



            </form>
        </div>
    </div>


</div>
<!-- Delete Course Modal -->
<div class="modal fade" id="deleteCourseModal" tabindex="-1" aria-labelledby="deleteCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCourseModalLabel">{{ __('instructor.delete_course') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('instructor.confirm_delete_course') }}</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('instructor.cancel') }}</button>
                <form action="{{ route('instructor.courses.delete', $course) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('instructor.delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

@endpush