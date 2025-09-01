@extends('layouts.dashboard')

@section('title', __('app.courses_management'))

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('app.admin_dashboard') }}</a></li>
        <li class="breadcrumb-item active">{{ __('app.courses') }}</li>
    </ol>
</nav>
@endsection

@section('content')
@php
$isRTL = session('locale', 'ar') === 'ar';
$marginEnd = $isRTL ? 'ms-2' : 'me-2';
@endphp


<!-- Search and Filters -->
<fieldset class="search-filters mb-2">
    <legend>
        <span>{{ __('app.courses_management') }}</span>
        <a href="{{ route('instructor.courses.create') }}" class="">
            <i class="fa fa-plus-circle {{ $marginEnd }}"></i>
        </a>
    </legend>
    <div class="card-body mt-3">
        <form method="GET" action="{{ route('admin.courses.index') }}">

            <div class="mb-3 input-group">
                <label for="search" class="input-group-text">{{ __('app.search') }}</label>
                <input type="text" class="form-select" id="search" name="search"
                    value="{{ request('search') }}"
                    placeholder="{{ __('app.search_courses_placeholder') }}">

                <label for="status" class="input-group-text">{{ __('app.status') }}</label>
                <select class="form-select" id="status" name="status">
                    <option value="">{{ __('app.all_statuses') }}</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>{{ __('app.draft') }}</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>{{ __('app.published') }}</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('app.pending') }}</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>{{ __('app.rejected') }}</option>
                </select>

                <label for="category" class="input-group-text">{{ __('app.category') }}</label>
                <select class="form-select" id="category" name="category">
                    <option value="">{{ __('app.all_categories') }}</option>
                </select>


                <button type="submit" class="input-group-text">
                    <i class="fa fa-search {{ $marginEnd }}"></i>{{ __('app.search') }}
                </button>
            </div>

        </form>
    </div>


    <!-- Courses Table -->
    <div class="card admin-card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('app.course') }}</th>
                            <th>{{ __('app.instructor') }}</th>
                            <th>{{ __('app.category') }}</th>
                            <th>{{ __('app.status') }}</th>
                            <th>{{ __('app.enrollments') }}</th>
                            <th>{{ __('app.created_date') }}</th>
                            <th>{{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="user-row">
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://via.placeholder.com/60x40?text=Course"
                                        alt="Course" class="course-thumbnail {{ $marginEnd }}">
                                    <div>
                                        <div class="fw-semibold">Sample Course Title</div>
                                        <small class="text-muted">$99.99</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>John Instructor</div>
                                <small class="text-muted">john@example.com</small>
                            </td>
                            <td>
                                <span class="badge bg-info">Programming</span>
                            </td>
                            <td>
                                <span class="badge bg-success">{{ __('app.published') }}</span>
                            </td>
                            <td>
                                <span class="text-primary fw-semibold">125</span>
                            </td>
                            <td>2024-01-15</td>
                            <td>
                                <div class="user-actions">
                                    <a href="#" class="btn btn-sm btn-outline-info">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-warning">
                                        <i class="fa fa-pause"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <div class="text-center py-5" style="display: none;">
                <i class="fa fa-book display-1 text-muted"></i>
                <h4 class="mt-3">{{ __('app.no_courses_found') }}</h4>
                <p class="text-muted">{{ __('app.no_courses_message') }}</p>
            </div>
        </div>
    </div>
    @endsection

    @section('widgets')
    <div class="widget">
        <div class="widget-header">
            <h6>{{ __('app.courses_statistics') }}</h6>
        </div>
        <div class="widget-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span>{{ __('app.total_courses') }}</span>
                <span class="badge bg-primary">89</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span>{{ __('app.published_courses') }}</span>
                <span class="badge bg-success">67</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span>{{ __('app.pending_courses') }}</span>
                <span class="badge bg-warning">15</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span>{{ __('app.draft_courses') }}</span>
                <span class="badge bg-secondary">7</span>
            </div>
        </div>
    </div>

    <div class="widget">
        <div class="widget-header">
            <h6>{{ __('app.quick_actions') }}</h6>
        </div>
        <div class="widget-body">
            <div class="d-grid gap-2">
                <a href="{{ route('instructor.courses.create') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-plus-circle {{ $marginEnd }}"></i>{{ __('app.add_course') }}
                </a>
                <a href="#" class="btn btn-warning btn-sm">
                    <i class="fa fa-clock {{ $marginEnd }}"></i>{{ __('app.pending_approval') }}
                </a>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-info btn-sm">
                    <i class="fa fa-tags {{ $marginEnd }}"></i>{{ __('app.manage_categories') }}
                </a>
            </div>
        </div>
    </div>
    @endsection