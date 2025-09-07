@extends('layouts.student')

@section('title', __('student.student_dashboard'))
@section ('breadcrumb')
<li class="breadcrumb-item text-light active" aria-current="page">{{ __('student.student_dashboard') }}</li>
@endsection
@section('content')


<!-- Performance Chart -->
<ul class="top-nav d-flex gap-3 justify-content-center">
    <li class="fs-5 fw-bold">{{__('my_profile')}}</li>
    <li class="fs-5 fw-bold active">{{__('my_courses')}}</li>
    <li class="fs-5 fw-bold">{{__('my_lists')}}</li>
    <li class="fs-5 fw-bold">{{__('wishlists')}}</li>
    <li class="fs-5 fw-bold">{{__('certificates')}}</li>
    <li class="fs-5 fw-bold">{{__('archeived')}}</li>
    <li class="fs-5 fw-bold">{{__('my_tools')}}</li>

</ul>
<div class="container">

    <div class="contents">
        @if(isset($tab))
        @include('student.tabs.' . $tab)
        @else
        @include('student.tabs.profile')
        @endif
    </div>
</div>

<script>
    function openForEdit(id) {
        const target = document.getElementById(id)
        target.select()
        target.focus()
        target.setAttribute('type', 'text')
    }
</script>
@endsection