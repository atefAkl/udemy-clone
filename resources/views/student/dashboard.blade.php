@extends('layouts.student')

@section('title', __('student.student_dashboard'))
@section ('breadcrumb')
<li class="breadcrumb-item text-light active" aria-current="page">{{ __('student.student_dashboard') }}</li>
@endsection
@section('content')


<!-- Navigation Tabs -->
<ul class="top-nav d-flex gap-3 justify-content-center">
    <li class="fs-5 fw-bold {{ (!isset($tab) || $tab == 'profile') ? 'active' : '' }}">
        <a href="{{ route('student.dashboard', ['tab' => 'profile']) }}" class="text-decoration-none">
            {{__('student.my_profile')}}
        </a>
    </li>
    <li class="fs-5 fw-bold {{ (isset($tab) && $tab == 'my_courses') ? 'active' : '' }}">
        <a href="{{ route('student.dashboard', ['tab' => 'my_courses']) }}" class="text-decoration-none">
            {{__('student.my_courses')}}
        </a>
    </li>
    <li class="fs-5 fw-bold {{ (isset($tab) && $tab == 'my_lists') ? 'active' : '' }}">
        <a href="{{ route('student.dashboard', ['tab' => 'my_lists']) }}" class="text-decoration-none">
            {{__('student.my_lists')}}
        </a>
    </li>
    <li class="fs-5 fw-bold {{ (isset($tab) && $tab == 'wishlists') ? 'active' : '' }}">
        <a href="{{ route('student.dashboard', ['tab' => 'wishlists']) }}" class="text-decoration-none">
            {{__('student.wishlists')}}
        </a>
    </li>
    <li class="fs-5 fw-bold {{ (isset($tab) && $tab == 'certificates') ? 'active' : '' }}">
        <a href="{{ route('student.dashboard', ['tab' => 'certificates']) }}" class="text-decoration-none">
            {{__('student.certificates')}}
        </a>
    </li>
    <li class="fs-5 fw-bold {{ (isset($tab) && $tab == 'archived') ? 'active' : '' }}">
        <a href="{{ route('student.dashboard', ['tab' => 'archived']) }}" class="text-decoration-none">
            {{__('student.archived_courses')}}
        </a>
    </li>
    <li class="fs-5 fw-bold {{ (isset($tab) && $tab == 'my_tools') ? 'active' : '' }}">
        <a href="{{ route('student.dashboard', ['tab' => 'my_tools']) }}" class="text-decoration-none">
            {{__('student.my_tools')}}
        </a>
    </li>
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