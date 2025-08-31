@extends('layouts.dashboard')

@section('title', __('app.create_course'))

@section('sidebar-nav')
<a href="{{ route('instructor.dashboard') }}" class="nav-link">
    <i class="fas fa-tachometer-alt" style="margin-inline-end: 10px;"></i>
    {{ __('app.dashboard') }}
</a>
<a href="{{ route('instructor.courses.index') }}" class="nav-link">
    <i class="fas fa-book" style="margin-inline-end: 10px;"></i>
    {{ __('app.my_courses') }}
</a>
<a href="{{ route('instructor.courses.create') }}" class="nav-link active">
    <i class="fas fa-plus-circle" style="margin-inline-end: 10px;"></i>
    {{ __('app.create_course') }}
</a>
<a href="#" class="nav-link">
    <i class="fas fa-chart-line" style="margin-inline-end: 10px;"></i>
    {{ __('app.analytics') }}
</a>
<a href="#" class="nav-link">
    <i class="fas fa-dollar-sign" style="margin-inline-end: 10px;"></i>
    {{ __('app.earnings') }}
</a>
<a href="#" class="nav-link">
    <i class="fas fa-cog" style="margin-inline-end: 10px;"></i>
    {{ __('app.settings') }}
</a>
@endsection

@section('content')
<div class="container-fluid p-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('instructor.dashboard') }}">{{ __('app.dashboard') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('instructor.courses.index') }}">{{ __('app.my_courses') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ __('app.create_course') }}</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">{{ __('app.create_new_course') }}</h2>
            <p class="text-muted">{{ __('app.create_course_description') }}</p>
        </div>
        <a href="{{ route('instructor.courses.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left" style="margin-inline-end: 8px;"></i>
            {{ __('app.back_to_courses') }}
        </a>
    </div>

    <!-- Course Creation Form -->
    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('instructor.courses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Basic Information Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle text-primary" style="margin-inline-end: 10px;"></i>
                            {{ __('app.basic_information') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="title" class="form-label">{{ __('app.course_title') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title') }}"
                                    placeholder="{{ __('app.enter_course_title') }}" required>
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">{{ __('app.course_description') }} <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                    id="description" name="description" rows="4"
                                    placeholder="{{ __('app.enter_course_description') }}" required>{{ old('description') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">{{ __('app.category') }} <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror"
                                    id="category_id" name="category_id" required>
                                    <option value="">{{ __('app.select_category') }}</option>

                                    @foreach($categories as $category)
                                    <option value="{{ $category['id'] }}" {{ old('category_id') == $category['id'] ? 'selected' : '' }}>
                                        {{ $category['name'] }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="level" class="form-label">{{ __('app.difficulty_level') }} <span class="text-danger">*</span></label>
                                <select class="form-select @error('level') is-invalid @enderror"
                                    id="level" name="level" required>
                                    <option value="">{{ __('app.select_level') }}</option>
                                    <option value="beginner" {{ old('level') == 'beginner' ? 'selected' : '' }}>
                                        {{ __('app.beginner') }}
                                    </option>
                                    <option value="intermediate" {{ old('level') == 'intermediate' ? 'selected' : '' }}>
                                        {{ __('app.intermediate') }}
                                    </option>
                                    <option value="advanced" {{ old('level') == 'advanced' ? 'selected' : '' }}>
                                        {{ __('app.advanced') }}
                                    </option>
                                </select>
                                @error('level')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pricing Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-dollar-sign text-success" style="margin-inline-end: 10px;"></i>
                            {{ __('app.pricing') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">{{ __('app.course_price') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                        id="price" name="price" value="{{ old('price') }}"
                                        placeholder="0.00" step="0.01" min="0" required>
                                    @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">{{ __('app.set_price_zero_for_free') }}</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="duration" class="form-label">{{ __('app.estimated_duration') }}</label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('duration') is-invalid @enderror"
                                        id="duration" name="duration" value="{{ old('duration') }}"
                                        placeholder="0" min="1">
                                    <span class="input-group-text">{{ __('app.hours') }}</span>
                                    @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Media Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-image text-info" style="margin-inline-end: 10px;"></i>
                            {{ __('app.course_media') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">{{ __('app.course_thumbnail') }}</label>
                            <input type="file" class="form-control @error('thumbnail') is-invalid @enderror"
                                id="thumbnail" name="thumbnail" accept="image/*">
                            @error('thumbnail')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">{{ __('app.recommended_size_1280x720') }}</small>
                        </div>

                        <div class="mb-3">
                            <label for="preview_video" class="form-label">{{ __('app.preview_video_url') }}</label>
                            <input type="url" class="form-control @error('preview_video') is-invalid @enderror"
                                id="preview_video" name="preview_video" value="{{ old('preview_video') }}"
                                placeholder="https://youtube.com/watch?v=...">
                            @error('preview_video')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">{{ __('app.youtube_vimeo_supported') }}</small>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-3 mb-4">
                    <button type="submit" name="action" value="draft" class="btn btn-outline-primary">
                        <i class="fas fa-save" style="margin-inline-end: 8px;"></i>
                        {{ __('app.save_as_draft') }}
                    </button>
                    <button type="submit" name="action" value="publish" class="btn btn-primary">
                        <i class="fas fa-rocket" style="margin-inline-end: 8px;"></i>
                        {{ __('app.create_and_publish') }}
                    </button>
                    <a href="{{ route('instructor.courses.index') }}" class="btn btn-secondary">
                        {{ __('app.cancel') }}
                    </a>
                </div>
            </form>
        </div>

        <!-- Sidebar Tips -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb" style="margin-inline-end: 10px;"></i>
                        {{ __('app.course_creation_tips') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">{{ __('app.compelling_title') }}</h6>
                        <p class="small text-muted">{{ __('app.title_tip') }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-success">{{ __('app.clear_description') }}</h6>
                        <p class="small text-muted">{{ __('app.description_tip') }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-info">{{ __('app.attractive_thumbnail') }}</h6>
                        <p class="small text-muted">{{ __('app.thumbnail_tip') }}</p>
                    </div>
                    <div class="mb-0">
                        <h6 class="text-warning">{{ __('app.competitive_pricing') }}</h6>
                        <p class="small text-muted">{{ __('app.pricing_tip') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('widgets')
<!-- Course Statistics -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0">
        <h6 class="mb-0">
            <i class="fas fa-chart-bar text-primary" style="margin-inline-end: 10px;"></i>
            {{ __('app.your_statistics') }}
        </h6>
    </div>
    <div class="card-body">
        <div class="d-flex align-items-center p-3 bg-primary bg-opacity-10 rounded mb-3">
            <div style="margin-inline-end: 15px;">
                <i class="fas fa-book text-primary fa-2x"></i>
            </div>
            <div>
                <h4 class="fw-bold text-primary mb-0">{{ $totalCourses ?? 0 }}</h4>
                <small class="text-muted">{{ __('app.total_courses') }}</small>
            </div>
        </div>
        <div class="d-flex align-items-center p-3 bg-success bg-opacity-10 rounded mb-3">
            <div style="margin-inline-end: 15px;">
                <i class="fas fa-users text-success fa-2x"></i>
            </div>
            <div>
                <h4 class="fw-bold text-success mb-0">{{ $totalStudents ?? 0 }}</h4>
                <small class="text-muted">{{ __('app.total_students') }}</small>
            </div>
        </div>
        <div class="d-flex align-items-center p-3 bg-warning bg-opacity-10 rounded">
            <div style="margin-inline-end: 15px;">
                <i class="fas fa-star text-warning fa-2x"></i>
            </div>
            <div>
                <h4 class="fw-bold text-warning mb-0">{{ $averageRating ?? '0.0' }}</h4>
                <small class="text-muted">{{ __('app.average_rating') }}</small>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0">
        <h6 class="mb-0">
            <i class="fas fa-bolt text-warning" style="margin-inline-end: 10px;"></i>
            {{ __('app.quick_actions') }}
        </h6>
    </div>
    <div class="card-body">
        <div class="d-grid gap-2">
            <a href="{{ route('instructor.courses.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-list" style="margin-inline-end: 10px;"></i>{{ __('app.view_all_courses') }}
            </a>
            <a href="#" class="btn btn-outline-success">
                <i class="fas fa-chart-line" style="margin-inline-end: 10px;"></i>{{ __('app.view_analytics') }}
            </a>
            <a href="#" class="btn btn-outline-info">
                <i class="fas fa-dollar-sign" style="margin-inline-end: 10px;"></i>{{ __('app.earnings_report') }}
            </a>
        </div>
    </div>
</div>
@endsection