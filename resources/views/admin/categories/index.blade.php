@extends('layouts.dashboard')

@section('title', __('app.categories_management'))

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('app.admin_dashboard') }}</a></li>
        <li class="breadcrumb-item active">{{ __('app.categories') }}</li>
    </ol>
</nav>
@endsection

@section('content')
@php
$isRTL = session('locale', 'ar') === 'ar';
$marginEnd = $isRTL ? 'ms-2' : 'me-2';
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('app.categories_management') }}</h2>
    <div>
        <a href="{{ route('instructor.categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle {{ $marginEnd }}"></i>{{ __('app.add_category') }}
        </a>
    </div>
</div>

<!-- Search and Filters -->
<div class="card search-filters">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.categories.index') }}">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="search" class="form-label">{{ __('app.search') }}</label>
                        <input type="text" class="form-control" id="search" name="search"
                            value="{{ request('search') }}"
                            placeholder="{{ __('app.search_categories_placeholder') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="status" class="form-label">{{ __('app.status') }}</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">{{ __('app.all_statuses') }}</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>{{ __('app.active') }}</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>{{ __('app.inactive') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="bi bi-search {{ $marginEnd }}"></i>{{ __('app.search') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Categories Grid -->
<div class="row">
    <!-- Sample Category Card -->
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card admin-card category-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="category-icon">
                        <i class="bi bi-code-slash text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-eye {{ $marginEnd }}"></i>{{ __('app.view') }}</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-pencil {{ $marginEnd }}"></i>{{ __('app.edit') }}</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash {{ $marginEnd }}"></i>{{ __('app.delete') }}</a></li>
                        </ul>
                    </div>
                </div>

                <h5 class="card-title">Programming</h5>
                <p class="card-text text-muted">Learn various programming languages and frameworks</p>

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-success">{{ __('app.active') }}</span>
                    </div>
                    <div class="text-muted">
                        <small>45 {{ __('app.courses') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card admin-card category-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="category-icon">
                        <i class="bi bi-palette text-warning" style="font-size: 2rem;"></i>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-eye {{ $marginEnd }}"></i>{{ __('app.view') }}</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-pencil {{ $marginEnd }}"></i>{{ __('app.edit') }}</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash {{ $marginEnd }}"></i>{{ __('app.delete') }}</a></li>
                        </ul>
                    </div>
                </div>

                <h5 class="card-title">Design</h5>
                <p class="card-text text-muted">Graphic design, UI/UX, and creative arts</p>

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-success">{{ __('app.active') }}</span>
                    </div>
                    <div class="text-muted">
                        <small>23 {{ __('app.courses') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card admin-card category-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="category-icon">
                        <i class="bi bi-graph-up text-success" style="font-size: 2rem;"></i>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-eye {{ $marginEnd }}"></i>{{ __('app.view') }}</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-pencil {{ $marginEnd }}"></i>{{ __('app.edit') }}</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash {{ $marginEnd }}"></i>{{ __('app.delete') }}</a></li>
                        </ul>
                    </div>
                </div>

                <h5 class="card-title">Business</h5>
                <p class="card-text text-muted">Marketing, management, and entrepreneurship</p>

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-secondary">{{ __('app.inactive') }}</span>
                    </div>
                    <div class="text-muted">
                        <small>12 {{ __('app.courses') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Empty State -->
<div class="text-center py-5" style="display: none;">
    <i class="bi bi-tags display-1 text-muted"></i>
    <h4 class="mt-3">{{ __('app.no_categories_found') }}</h4>
    <p class="text-muted">{{ __('app.no_categories_message') }}</p>
    <a href="{{ route('instructor.categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle {{ $marginEnd }}"></i>{{ __('app.add_first_category') }}
    </a>
</div>
@endsection

@section('widgets')
<div class="widget">
    <div class="widget-header">
        <h6>{{ __('app.categories_statistics') }}</h6>
    </div>
    <div class="widget-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span>{{ __('app.total_categories') }}</span>
            <span class="badge bg-primary">12</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span>{{ __('app.active_categories') }}</span>
            <span class="badge bg-success">10</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span>{{ __('app.inactive_categories') }}</span>
            <span class="badge bg-secondary">2</span>
        </div>
    </div>
</div>

<div class="widget">
    <div class="widget-header">
        <h6>{{ __('app.quick_actions') }}</h6>
    </div>
    <div class="widget-body">
        <div class="d-grid gap-2">
            <a href="{{ route('instructor.categories.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle {{ $marginEnd }}"></i>{{ __('app.add_category') }}
            </a>
            <a href="{{ route('admin.courses.index') }}" class="btn btn-info btn-sm">
                <i class="bi bi-book {{ $marginEnd }}"></i>{{ __('app.manage_courses') }}
            </a>
        </div>
    </div>
</div>
@endsection