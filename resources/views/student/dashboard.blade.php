@extends('layouts.student')

@section('title', __('student.student_dashboard'))
@section ('breadcrumb')
<li class="breadcrumb-item text-light active" aria-current="page">{{ __('student.student_dashboard') }}</li>
@endsection
@section('content')


<!-- Performance Chart -->
<ul class="top-nav d-flex gap-3 justify-content-center">
    <li class="fs-5 fw-bold active">{{__('my_profile')}}</li>
    <li class="fs-5 fw-bold">{{__('my_courses')}}</li>
    <li class="fs-5 fw-bold">{{__('my_lists')}}</li>
    <li class="fs-5 fw-bold">{{__('wishlists')}}</li>
    <li class="fs-5 fw-bold">{{__('certificates')}}</li>
    <li class="fs-5 fw-bold">{{__('archeived')}}</li>
    <li class="fs-5 fw-bold">{{__('my_tools')}}</li>

</ul>
<div class="container">

    <div class="contents">
        <div id="myProfileTabs" class="row">
            <div class="navs  bg-dark">
                <nav class="nav flex-column">
                    <a class="nav-link border-0" data-target="#account" aria-current="page" href="#">{{__('account')}}</a>
                    <a class="nav-link border-0 active" data-target="#profileInfo" href="#">{{__('profile_info')}}</a>
                    <a class="nav-link border-0" data-target="#credit" href="#">{{__('credit')}}</a>
                    <a class="nav-link border-0" data-target="#badges" href="#">{{__('badges')}}</a>
                    <a class="nav-link border-0" data-target="#files" href="">{{__('my_files')}}</a>
                </nav>
            </div>
            <div class="col tabs">
                <div class="" id="account">
                    <div class="card">
                        <div class="card-header">
                            {{__('account_info')}}
                        </div>
                        <div class="card-body">
                            <form action="">
                                @csrf
                                <div class="input-group mb-3">
                                    <label for="userName" class="input-group-text">{{__('username')}}</label>
                                    <input type="button" class="form-control" id="userName" name="name" value="{{auth()->user()->name}}">

                                </div>

                                <div class="input-group mb-3">
                                    <label for="email" class="input-group-text">{{__('email')}}</label>
                                    <input type="button" class="form-control" id="email" name="email" value="{{auth()->user()->email}}">

                                </div>

                                <div class="input-group mb-3">
                                    <label for="phone" class="input-group-text">{{__('phone')}}</label>
                                    <input type="button" class="form-control" id="phone" name="phone" value="{{auth()->user()->phone}}">

                                </div>

                                <div class="input-group mb-3">
                                    <label for="role" class="input-group-text">{{__('role')}}</label>
                                    <input type="button" class="form-control" id="role" name="role" value="{{auth()->user()->role}}">
                                </div>

                                <button class="btn btn-outline-secondary">{{__('submit')}}</button>
                            </form>

                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            {{__('change_password')}}
                        </div>
                        <div class="card-body">
                            <form action="">
                                @csrf
                                <div class="input-group mb-3">
                                    <label for="userName" class="input-group-text">{{__('old_password')}}</label>
                                    <input type="button" class="form-control" id="userName" name="name" value="{{auth()->user()->name}}">
                                    <label class="input-group-text" onclick="openForEdit('userName')">
                                        <i class="fa fa-edit"></i>
                                    </label>
                                </div>

                                <div class="input-group mb-3">
                                    <label for="email" class="input-group-text">{{__('new_password')}}</label>
                                    <input type="button" class="form-control" id="email" name="email" value="{{auth()->user()->email}}">
                                    <label class="input-group-text" onclick="openForEdit('email')">
                                        <i class="fa fa-edit"></i>
                                    </label>
                                </div>

                                <div class="input-group mb-3">
                                    <label for="phone" class="input-group-text">{{__('confirm_password')}}</label>
                                    <input type="button" class="form-control" id="phone" name="phone" value="{{auth()->user()->phone}}">
                                    <label class="input-group-text" onclick="openForEdit('phone')">
                                        <i class="fa fa-edit"></i>
                                    </label>
                                </div>

                                <button class="btn btn-outline-secondary">{{__('submit')}}</button>
                            </form>
                            <!-- Password strength roles and validation -->
                        </div>
                    </div>

                </div>
                <div id="profileInfo" class="active">

                    <div class="card">
                        <div class="card-header">
                            {{__('profile_info')}}
                        </div>
                        <div class="card-body">
                            <form action="">
                                @csrf
                                <div class="input-group mb-3">
                                    <!-- Name Info In Engthish -->
                                    <label for="first_name" class="input-group-text">{{__('full_name')}}</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{auth()->user()->first_name}}" placeholder="{{__('first_name')}}">
                                    <input type="text" class="form-control" id="mid_name" name="mid_name" value="{{auth()->user()->mid_name}}" placeholder="{{__('middle_name')}}">
                                    <input type="text" class="form-control" id="family" name="family" value="{{auth()->user()->family}}" placeholder="{{__('family')}}">
                                </div>

                                <div class="input-group mb-3">
                                    <!-- Name Info In othe language -->
                                    <label for="first_name" class="input-group-text">{{__('full_name_ol')}}</label>
                                    <input type="text" class="form-control" id="first_name_ol" name="first_name_ol" value="{{auth()->user()->first_name_ol}}" placeholder="{{__('first_name_ol')}}">
                                    <input type="text" class="form-control" id="mid_name_ol" name="mid_name_ol" value="{{auth()->user()->mid_name_ol}}" placeholder="{{__('middle_name_ol')}}">
                                    <input type="text" class="form-control" id="family_ol" name="family_ol" value="{{auth()->user()->family_ol}}" placeholder="{{__('family_ol')}}">
                                </div>

                                <div class="input-group mb-3">
                                    <label for="phone" class="input-group-text">{{__('gender')}}</label>
                                    <input type="button" class="form-control" id="phone" name="phone" value="{{auth()->user()->phone}}">
                                </div>

                                <div class="input-group mb-3">
                                    <label for="birth_date" class="input-group-text">{{__('birth_date')}}</label>
                                    <input type="button" class="form-control" id="birth_date" name="birth_date" value="{{auth()->user()->birth_date}}">
                                    <!-- Date Joined -->
                                    <label for="joined_at" class="input-group-text">{{__('joined_at')}}</label>
                                    <input type="button" class="form-control" value="{{auth()->user()->createded_at}}">
                                </div>

                                <button class="btn btn-outline-secondary">{{__('submit')}}</button>
                            </form>

                        </div>
                    </div>
                </div>
                <div id="credit">
                    <h4>{{__('credit_and_invoices')}}</h4>

                </div>
                <div id="badges">
                    <h4>{{__('my_bages')}}</h4>

                </div>
                <div id="files">
                    <h4>{{__('my_files')}}</h4>

                </div>

            </div>
        </div>
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