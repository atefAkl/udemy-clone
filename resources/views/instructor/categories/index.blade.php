@extends('layouts.dashboard')

@section('title', __('app.manage_categories'))

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}">{{ __('app.instructor_dashboard') }}</a></li>
        <li class="breadcrumb-item active">{{ __('app.manage_categories') }}</li>
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
            <a class="nav-link active" href="{{ route('instructor.categories.index') }}">
                <i class="fas fa-list {{ $marginEnd }}"></i>{{ __('app.all_categories') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('instructor.categories.create') }}">
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
        <h1 class="h3 mb-0">{{ __('app.manage_categories') }}</h1>
        <p class="text-muted mb-0">{{ __('app.organize_courses_categories') }}</p>
    </div>
    <a href="{{ route('instructor.categories.create') }}" class="btn btn-primary">
        <i class="fas fa-plus {{ $marginEnd }}"></i>{{ __('app.create_category') }}
    </a>
</div>

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="{{ __('app.search_categories') }}" id="searchInput">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="statusFilter">
                    <option value="">{{ __('app.all_status') }}</option>
                    <option value="active">{{ __('app.active') }}</option>
                    <option value="inactive">{{ __('app.inactive') }}</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="parentFilter">
                    <option value="">{{ __('app.all_categories') }}</option>
                    <option value="parent">{{ __('app.parent_categories') }}</option>
                    <option value="sub">{{ __('app.subcategories') }}</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Categories Table -->
<div class="card">
    <div class="card-body">
        @if(isset($categories) && $categories->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ __('app.category_name') }}</th>
                        <th>{{ __('app.parent_category') }}</th>
                        <th>{{ __('app.courses_count') }}</th>
                        <th>{{ __('app.status') }}</th>
                        <th>{{ __('app.created_at') }}</th>
                        <th class="{{ $reverseAlign }}">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($category->icon)
                                <i class="{{ $category->icon }} {{ $marginEnd }} text-primary"></i>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $category->name }}</h6>
                                    @if($category->description)
                                    <small class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($category->parent)
                            <span class="badge bg-light text-dark">{{ $category->parent->name }}</span>
                            @else
                            <span class="text-muted">{{ __('app.no_parent') }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $category->courses_count ?? 0 }}</span>
                        </td>
                        <td>
                            @if($category->status === 'active')
                            <span class="badge bg-success">{{ __('app.active') }}</span>
                            @else
                            <span class="badge bg-secondary">{{ __('app.inactive') }}</span>
                            @endif
                        </td>
                        <td>{{ $category->created_at->format('Y-m-d') }}</td>
                        <td class="{{ $reverseAlign }}">
                            <div class="btn-group" role="group">
                                <a href="{{ route('instructor.categories.show', $category) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('instructor.categories.edit', $category) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteCategory({{ $category->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($categories->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $categories->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-5">
            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
            <h5>{{ __('app.no_categories') }}</h5>
            <p class="text-muted">{{ __('app.no_categories_message') }}</p>
            <a href="{{ route('instructor.categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus {{ $marginEnd }}"></i>{{ __('app.create_first_category') }}
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('app.delete_category') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('app.confirm_delete_category') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('app.delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('widgets')
<div class="widget-card">
    <div class="widget-header">
        <h6 class="widget-title">{{ __('app.quick_stats') }}</h6>
    </div>
    <div class="widget-body">
        <div class="stat-item">
            <div class="stat-value">{{ $stats['total_categories'] ?? 0 }}</div>
            <div class="stat-label">{{ __('app.total_categories') }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $stats['active_categories'] ?? 0 }}</div>
            <div class="stat-label">{{ __('app.active_categories') }}</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $stats['parent_categories'] ?? 0 }}</div>
            <div class="stat-label">{{ __('app.parent_categories') }}</div>
        </div>
    </div>
</div>

<div class="widget-card">
    <div class="widget-header">
        <h6 class="widget-title">{{ __('app.recent_activity') }}</h6>
    </div>
    <div class="widget-body">
        <div class="activity-item">
            <div class="activity-icon bg-success">
                <i class="fas fa-plus"></i>
            </div>
            <div class="activity-content">
                <p class="activity-text">{{ __('app.category_created') }}</p>
                <small class="activity-time">{{ __('app.one_hour_ago') }}</small>
            </div>
        </div>
        <div class="activity-item">
            <div class="activity-icon bg-info">
                <i class="fas fa-edit"></i>
            </div>
            <div class="activity-content">
                <p class="activity-text">{{ __('app.category_updated') }}</p>
                <small class="activity-time">{{ __('app.three_hours_ago') }}</small>
            </div>
        </div>
    </div>
</div>

<div class="widget-card">
    <div class="widget-header">
        <h6 class="widget-title">{{ __('app.quick_actions') }}</h6>
    </div>
    <div class="widget-body">
        <a href="{{ route('instructor.categories.create') }}" class="btn btn-primary btn-sm w-100 mb-2">
            <i class="fas fa-plus {{ $marginEnd }}"></i>{{ __('app.new_category') }}
        </a>
        <a href="{{ route('instructor.courses.index') }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
            <i class="fas fa-book {{ $marginEnd }}"></i>{{ __('app.manage_courses') }}
        </a>
        <a href="{{ route('instructor.dashboard') }}" class="btn btn-outline-secondary btn-sm w-100">
            <i class="fas fa-chart-bar {{ $marginEnd }}"></i>{{ __('app.view_analytics') }}
        </a>
    </div>
</div>
@endsection

@if(session('locale', 'ar') === 'ar')
<style>
    /* RTL-specific styles */
    .table th:last-child,
    .table td:last-child {
        text-align: right;
    }

    .btn-group {
        flex-direction: row-reverse;
    }

    .input-group .input-group-text {
        border-left: 1px solid #dee2e6;
        border-right: 0;
    }

    .input-group .form-control {
        border-right: 1px solid #dee2e6;
        border-left: 0;
    }
</style>
@else
<style>
    /* LTR-specific styles */
    .table th:last-child,
    .table td:last-child {
        text-align: right;
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

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    .badge {
        font-size: 0.75rem;
    }
</style>

<script>
    function deleteCategory(categoryId) {
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = `/instructor/categories/${categoryId}`;

        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('tbody tr');

        tableRows.forEach(row => {
            const categoryName = row.querySelector('td:first-child h6').textContent.toLowerCase();
            const categoryDesc = row.querySelector('td:first-child small')?.textContent.toLowerCase() || '';

            if (categoryName.includes(searchTerm) || categoryDesc.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Status filter
    document.getElementById('statusFilter').addEventListener('change', function() {
        const selectedStatus = this.value;
        const tableRows = document.querySelectorAll('tbody tr');

        tableRows.forEach(row => {
            if (!selectedStatus) {
                row.style.display = '';
                return;
            }

            const statusBadge = row.querySelector('td:nth-child(4) .badge');
            const rowStatus = statusBadge.classList.contains('bg-success') ? 'active' : 'inactive';

            if (rowStatus === selectedStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>