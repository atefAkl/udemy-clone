@extends('layouts.instructor-simple')

@section('title', __('app.create_category'))

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}">{{ __('app.instructor_dashboard') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('instructor.categories.index') }}">{{ __('app.manage_categories') }}</a></li>
        <li class="breadcrumb-item active">{{ __('app.create_category') }}</li>
    </ol>
</nav>
@endsection

@section('sidebar-nav')
@php
$isRTL = session('locale', 'ar') === 'ar';
$align = $isRTL ? 'text-end' : 'text-start';
$reverseAlign = $isRTL ? 'text-start' : 'text-end';
$marginEnd = $isRTL ? 'ms-2' : 'me-2';
$marginStart = $isRTL ? 'me-2' : 'ms-2';
@endphp

<div class="nav-section">
    <h6 class="nav-section-title">{{ __('app.categories') }}</h6>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('instructor.categories.index') }}">
                <i class="fas fa-list {{ $marginEnd }}"></i>{{ __('app.all_categories') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('instructor.categories.create') }}">
                <i class="fas fa-plus {{ $marginEnd }}"></i>{{ __('app.create_category') }}
            </a>
        </li>
    </ul>
</div>

<div class="nav-section">
    <h6 class="nav-section-title">{{ __('app.quick_actions') }}</h6>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('instructor.courses.index') }}">
                <i class="fas fa-book {{ $marginEnd }}"></i>{{ __('app.my_courses') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('instructor.dashboard') }}">
                <i class="fas fa-chart-bar {{ $marginEnd }}"></i>{{ __('app.analytics') }}
            </a>
        </li>
    </ul>
</div>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">{{ __('app.create_category') }}</h1>
        <p class="text-muted mb-0">{{ __('app.create_new_category_description') }}</p>
    </div>
    <a href="{{ route('instructor.categories.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left {{ $marginEnd }}"></i>{{ __('app.back_to_categories') }}
    </a>
</div>

<form action="{{ route('instructor.categories.store') }}" method="POST" enctype="multipart/form-data" id="categoryForm">
    @csrf

    <div class="row">
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('app.basic_information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">{{ __('app.category_name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="slug" class="form-label">{{ __('app.category_slug') }}</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}">
                            <small class="form-text text-muted">{{ __('app.slug_auto_generated') }}</small>
                            @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">{{ __('app.category_description') }}</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="parent_id" class="form-label">{{ __('app.parent_category') }}</label>
                            <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                                <option value="">{{ __('app.no_parent') }}</option>
                                @if(isset($parentCategories))
                                @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                                @endforeach
                                @endif
                            </select>
                            @error('parent_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">{{ __('app.status') }}</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>{{ __('app.active') }}</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>{{ __('app.inactive') }}</option>
                            </select>
                            @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appearance -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('app.appearance') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="icon" class="form-label">{{ __('app.category_icon') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" value="{{ old('icon') }}" placeholder="fas fa-book">
                                <button type="button" class="btn btn-outline-secondary" id="iconPreview">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small class="form-text text-muted">{{ __('app.icon_example') }}</small>
                            @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="color" class="form-label">{{ __('app.category_color') }}</label>
                            <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" id="color" name="color" value="{{ old('color', '#007bff') }}">
                            @error('color')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="sort_order" class="form-label">{{ __('app.sort_order') }}</label>
                            <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                            @error('sort_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    {{ __('app.featured_category') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO Settings -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('app.seo_settings') }}</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="meta_title" class="form-label">{{ __('app.category_meta_title') }}</label>
                        <input type="text" class="form-control @error('meta_title') is-invalid @enderror" id="meta_title" name="meta_title" value="{{ old('meta_title') }}">
                        @error('meta_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="meta_description" class="form-label">{{ __('app.category_meta_description') }}</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description" name="meta_description" rows="3">{{ old('meta_description') }}</textarea>
                        @error('meta_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save {{ $marginEnd }}"></i>{{ __('app.create_category') }}
                </button>
                <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                    <i class="fas fa-undo {{ $marginEnd }}"></i>{{ __('app.reset') }}
                </button>
                <a href="{{ route('instructor.categories.index') }}" class="btn btn-outline-danger">
                    <i class="fas fa-times {{ $marginEnd }}"></i>{{ __('app.cancel') }}
                </a>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Category Preview -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">{{ __('app.category_preview') }}</h6>
                </div>
                <div class="card-body text-center">
                    <div class="category-preview-icon mb-3">
                        <i id="previewIcon" class="fas fa-folder fa-3x text-primary"></i>
                    </div>
                    <h5 id="previewName" class="mb-2">{{ __('app.category_name') }}</h5>
                    <p id="previewDescription" class="text-muted small">{{ __('app.category_description') }}</p>
                    <div class="d-flex justify-content-center gap-2">
                        <span id="previewStatus" class="badge bg-success">{{ __('app.active') }}</span>
                        <span id="previewFeatured" class="badge bg-warning" style="display: none;">{{ __('app.featured') }}</span>
                    </div>
                </div>
            </div>

            <!-- Creation Tips -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">{{ __('app.creation_tips') }}</h6>
                </div>
                <div class="card-body">
                    <div class="tip-item mb-3">
                        <div class="tip-icon">
                            <i class="fas fa-lightbulb text-warning"></i>
                        </div>
                        <div class="tip-content">
                            <small>{{ __('app.category_name_tip') }}</small>
                        </div>
                    </div>
                    <div class="tip-item mb-3">
                        <div class="tip-icon">
                            <i class="fas fa-palette text-info"></i>
                        </div>
                        <div class="tip-content">
                            <small>{{ __('app.category_color_tip') }}</small>
                        </div>
                    </div>
                    <div class="tip-item">
                        <div class="tip-icon">
                            <i class="fas fa-search text-success"></i>
                        </div>
                        <div class="tip-content">
                            <small>{{ __('app.category_seo_tip') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">{{ __('app.quick_stats') }}</h6>
                </div>
                <div class="card-body">
                    <div class="stat-item mb-2">
                        <div class="stat-value">{{ $stats['total_categories'] ?? 0 }}</div>
                        <div class="stat-label">{{ __('app.total_categories') }}</div>
                    </div>
                    <div class="stat-item mb-2">
                        <div class="stat-value">{{ $stats['parent_categories'] ?? 0 }}</div>
                        <div class="stat-label">{{ __('app.parent_categories') }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $stats['subcategories'] ?? 0 }}</div>
                        <div class="stat-label">{{ __('app.subcategories') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('widgets')
<div class="widget-card">
    <div class="widget-header">
        <h6 class="widget-title">{{ __('app.recent_categories') }}</h6>
    </div>
    <div class="widget-body">
        @if(isset($recentCategories))
        @foreach($recentCategories as $category)
        <div class="activity-item">
            <div class="activity-icon" style="background-color: {{ $category->color ?? '#007bff' }};">
                <i class="{{ $category->icon ?? 'fas fa-folder' }}"></i>
            </div>
            <div class="activity-content">
                <p class="activity-text">{{ $category->name }}</p>
                <small class="activity-time">{{ $category->created_at->diffForHumans() }}</small>
            </div>
        </div>
        @endforeach
        @else
        <p class="text-muted small">{{ __('app.no_recent_categories') }}</p>
        @endif
    </div>
</div>

<div class="widget-card">
    <div class="widget-header">
        <h6 class="widget-title">{{ __('app.quick_actions') }}</h6>
    </div>
    <div class="widget-body">
        <a href="{{ route('instructor.categories.index') }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
            <i class="fas fa-list {{ $marginEnd }}"></i>{{ __('app.view_all_categories') }}
        </a>
        <a href="{{ route('instructor.courses.create') }}" class="btn btn-outline-success btn-sm w-100 mb-2">
            <i class="fas fa-plus {{ $marginEnd }}"></i>{{ __('app.create_course') }}
        </a>
        <a href="{{ route('instructor.dashboard') }}" class="btn btn-outline-secondary btn-sm w-100">
            <i class="fas fa-home {{ $marginEnd }}"></i>{{ __('app.dashboard') }}
        </a>
    </div>
</div>
@endsection

@if(session('locale', 'ar') === 'ar')
<style>
    /* RTL-specific styles */
    .input-group .btn {
        border-right: 1px solid #dee2e6;
        border-left: 0;
    }

    .form-check-input {
        margin-right: 0;
        margin-left: 0.25em;
    }

    .tip-item {
        display: flex;
        flex-direction: row-reverse;
        align-items: flex-start;
    }

    .tip-icon {
        margin-left: 0.5rem;
        margin-right: 0;
    }
</style>
@else
<style>
    /* LTR-specific styles */
    .tip-item {
        display: flex;
        align-items: flex-start;
    }

    .tip-icon {
        margin-right: 0.5rem;
    }
</style>
@endif

<style>
    .widget-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
    }

    .widget-header {
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e9ecef;
    }

    .widget-title {
        margin: 0;
        font-weight: 600;
        color: #495057;
    }

    .stat-item {
        margin-bottom: 1rem;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: bold;
        color: #007bff;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .activity-item {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f8f9fa;
    }

    .activity-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .activity-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-inline-end: 0.75rem;
    }

    .activity-icon i {
        font-size: 0.875rem;
        color: white;
    }

    .activity-content {
        flex: 1;
    }

    .activity-text {
        margin: 0;
        font-size: 0.875rem;
        color: #495057;
    }

    .activity-time {
        color: #6c757d;
    }

    .nav-section {
        margin-bottom: 2rem;
    }

    .nav-section-title {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #6c757d;
        margin-bottom: 0.5rem;
        padding: 0 1rem;
    }

    .category-preview-icon {
        padding: 2rem;
        background: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 1rem;
    }

    .tip-item {
        margin-bottom: 1rem;
    }

    .tip-content {
        flex: 1;
    }

    .form-control-color {
        width: 100%;
        height: 38px;
    }
</style>

<script>
    // Auto-generate slug from name
    document.getElementById('name').addEventListener('input', function() {
        const name = this.value;
        const slug = name.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
        document.getElementById('slug').value = slug;

        // Update preview
        document.getElementById('previewName').textContent = name || '{{ __("app.category_name") }}';
    });

    // Update description preview
    document.getElementById('description').addEventListener('input', function() {
        const description = this.value;
        document.getElementById('previewDescription').textContent = description || '{{ __("app.category_description") }}';
    });

    // Update icon preview
    document.getElementById('icon').addEventListener('input', function() {
        const icon = this.value || 'fas fa-folder';
        document.getElementById('previewIcon').className = icon + ' fa-3x text-primary';
    });

    // Update status preview
    document.getElementById('status').addEventListener('change', function() {
        const status = this.value;
        const statusBadge = document.getElementById('previewStatus');
        statusBadge.textContent = status === 'active' ? '{{ __("app.active") }}' : '{{ __("app.inactive") }}';
        statusBadge.className = status === 'active' ? 'badge bg-success' : 'badge bg-secondary';
    });

    // Update featured preview
    document.getElementById('is_featured').addEventListener('change', function() {
        const featured = this.checked;
        const featuredBadge = document.getElementById('previewFeatured');
        featuredBadge.style.display = featured ? 'inline' : 'none';
    });

    // Update color preview
    document.getElementById('color').addEventListener('change', function() {
        const color = this.value;
        document.getElementById('previewIcon').style.color = color;
    });

    // Icon preview button
    document.getElementById('iconPreview').addEventListener('click', function() {
        const icon = document.getElementById('icon').value;
        if (icon) {
            document.getElementById('previewIcon').className = icon + ' fa-3x text-primary';
        }
    });

    // Reset form
    function resetForm() {
        document.getElementById('categoryForm').reset();
        document.getElementById('previewName').textContent = '{{ __("app.category_name") }}';
        document.getElementById('previewDescription').textContent = '{{ __("app.category_description") }}';
        document.getElementById('previewIcon').className = 'fas fa-folder fa-3x text-primary';
        document.getElementById('previewStatus').className = 'badge bg-success';
        document.getElementById('previewStatus').textContent = '{{ __("app.active") }}';
        document.getElementById('previewFeatured').style.display = 'none';
    }

    // Form validation
    document.getElementById('categoryForm').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        if (!name) {
            e.preventDefault();
            alert('{{ __("app.category_name_required") }}');
            document.getElementById('name').focus();
        }
    });
</script>