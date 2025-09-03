@extends('layouts.dashboard')

@section('title', __('admin.courses_management'))

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin.admin_dashboard') }}</a></li>
        <li class="breadcrumb-item active">{{ __('admin.courses_management') }}</li>
    </ol>
</nav>
@endsection

@section('content')

<!-- Search and Filters -->
<fieldset class="search-filters mb-2">
    <legend>
        <span>{{ __('admin.courses_management') }}</span>
        <a href="{{ route('instructor.courses.create') }}" class="">
            <i class="fa fa-plus-circle" style="margin-inline-end: 0.5rem;"></i>
        </a>
    </legend>
    <div class="card-body mt-3">
        <form method="GET" action="{{ route('admin.courses.index') }}">

            <div class="mb-3 input-group">
                <label for="search" class="input-group-text">{{ __('admin.search') }}</label>
                <input type="text" class="form-control" id="search" name="search"
                    value="{{ request('search') }}"
                    placeholder="{{ __('admin.search_courses_placeholder') }}">

                <label for="status" class="input-group-text">{{ __('admin.status') }}</label>
                <select class="form-select" id="status" name="status">
                    <option value="">{{ __('admin.all_statuses') }}</option>
                    <option value="pending">{{ __('admin.pending') }}</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>{{ __('admin.draft') }}</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>{{ __('admin.published') }}</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>{{ __('admin.rejected') }}</option>
                </select>

                <label for="category" class="input-group-text">{{ __('admin.category') }}</label>
                <select class="form-select" id="category" name="category">
                    <option value="">{{ __('admin.all_categories') }}</option>
                </select>


                <button type="submit" class="input-group-text">
                    <i class="fa fa-search" style="margin-inline-end: 0.5rem;"></i>{{ __('admin.search') }}
                </button>
            </div>

        </form>
    </div>


    <!-- Courses Table -->
    <div class="card admin-card">
        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table table-hover my-0">
                    <thead>
                        <tr>
                            <th>{{ __('admin.course') }}</th>
                            <th>{{ __('admin.instructor') }}</th>
                            <th>{{ __('admin.category') }}</th>
                            <th>{{ __('admin.status') }}</th>
                            <th>{{ __('admin.enrollments') }}</th>
                            <th>{{ __('admin.created_date') }}</th>
                            <th>{{ __('admin.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="user-row">
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://via.placeholder.com/60x40?text=Course"
                                        alt="Course" class="course-thumbnail" style="margin-inline-end: 0.5rem;">
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
                                <span class="badge bg-success">{{ __('admin.published') }}</span>
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
                <h4 class="mt-3">{{ __('admin.no_courses_found') }}</h4>
                <p class="text-muted">{{ __('admin.no_courses_message') }}</p>
            </div>
        </div>
    </div>
    @endsection

    @section('widgets')
    <div class="widget">
        <div class="widget-header">
            <h6>{{ __('admin.courses_statistics') }}</h6>
        </div>
        <div class="widget-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span>{{ __('admin.total_courses') }}</span>
                <span class="badge bg-primary">89</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span>{{ __('admin.published_courses') }}</span>
                <span class="badge bg-success">67</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span>{{ __('admin.pending_courses') }}</span>
                <span class="badge bg-warning">15</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span>{{ __('admin.draft_courses') }}</span>
                <span class="badge bg-secondary">7</span>
            </div>
        </div>
    </div>

    <div class="widget">
        <div class="widget-header">
            <h6>{{ __('admin.quick_actions') }}</h6>
        </div>
        <div class="widget-body">
            <div class="d-grid gap-2">
                <a href="{{ route('instructor.courses.create') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-plus-circle" style="margin-inline-end: 0.5rem;"></i>{{ __('admin.add_course') }}
                </a>
                <a href="#" class="btn btn-warning btn-sm">
                    <i class="fa fa-clock" style="margin-inline-end: 0.5rem;"></i>{{ __('admin.pending_approval') }}
                </a>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-info btn-sm">
                    <i class="fa fa-tags" style="margin-inline-end: 0.5rem;"></i>{{ __('admin.manage_categories') }}
                </a>
            </div>
        </div>
    </div>
    @endsection