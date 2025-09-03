@extends('layouts.dashboard')

@section('title', __('admin.categories_management'))

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin.admin_dashboard') }}</a></li>
        <li class="breadcrumb-item active">{{ __('admin.categories') }}</li>
    </ol>
</nav>
@endsection

@section('content')

<!-- Search and Filters -->
<fieldset class="search-filters mb-2">
    <legend>
        <span>{{ __('admin.categories_management') }}</span>
        <span type="button" class="" data-bs-toggle="modal" data-bs-target="#createCategoryModal" onclick="openCreateModal()">
            <i class="fa fa-plus-circle text-primary" style="margin-inline-end: 0.5rem;" title="{{ __('admin.add_root_category') }}"></i>
        </span>
    </legend>

    <div class="py-3">
        <form method="GET" action="{{ route('admin.categories.index') }}">
            <div class="input-group mb-1">
                <label for="search" class="input-group-text">{{ __('admin.search') }}</label>
                <input type="text" class="form-control" id="search" name="search"
                    value="{{ request('search') }}"
                    placeholder="{{ __('admin.search_categories_placeholder') }}">

                <label for="status" class="input-group-text">{{ __('admin.status') }}</label>
                <select class="form-select" id="status" name="status">
                    <option value="">{{ __('admin.all_statuses') }}</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>{{ __('admin.active') }}</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>{{ __('admin.inactive') }}</option>
                </select>

                <button type="submit" class="input-group-text btn btn-outline-primary">
                    <i class="fa fa-search" style="margin-inline-end: 0.5rem;"></i>{{ __('admin.search') }}
                </button>
            </div>
        </form>
    </div>
    <style>
        .simple-tree .tree-toggle {

            cursor: pointer;
            color: #888;
            transition: color 0.3s ease;
            border: 1px solid #777;
        }

        .simple-tree .tree-toggle:hover {
            color: #333;
        }

        .simple-tree .tree-toggle.active {
            color: #333;
        }

        .simple-tree .subcategories {
            margin-inline-start: 0.6rem;
            padding: 0;
            border-inline-start: 1px solid #777;
        }

        .simple-tree .subcategories li {
            padding: 0 0.5rem;
        }

        .simple-tree .category-name {
            padding: 0 0.5rem;
        }
    </style>

    <!-- Categories Tree View -->
    <div class="card admin-card">
        <div class="card-body pt-0">
            <div class="tree-container">
                <div class="row">
                    <div class="col col-4">
                        <ul class="simple-tree" style="list-style-type: none; padding-left: 0;">
                            @forelse($categories->where('parent_id', null) as $rootCategory)
                            <li class="tree-item" data-category-id="{{ $rootCategory->id }}">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center justify-content-between">
                                        @if($rootCategory->children->count() > 0)
                                        <i class="fa fa-plus tree-toggle p-1 border" data-category-id="{{ $rootCategory->id }}"></i>
                                        @else
                                        <i class="fa fa-folder" style="margin-right: 8px; color: #6c757d;"></i>
                                        @endif
                                        <span class="category-name">{{ $rootCategory->name }}</span>
                                        <span class="badge bg-primary ms-2">{{ $rootCategory->children->count() }}</span>
                                    </div>

                                    <!-- Actions -->
                                    <div class="dropdown">
                                        <button class="btn btn-sm" type="button" data-bs-toggle="dropdown">
                                            <i class="fa fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" data-action="create" data-parent-id="{{ $rootCategory->id }}" data-bs-toggle="modal" data-bs-target="#createCategoryModal"><i class="fa fa-plus me-2"></i>{{ __('admin.add_subcategory') }}</a></li>
                                            <li><a class="dropdown-item" href="#" data-action="edit" data-id="{{ $rootCategory->id }}" data-name="{{ $rootCategory->name }}" data-description="{{ $rootCategory->description ?? '' }}" data-bs-toggle="modal" data-bs-target="#editCategoryModal"><i class="fa fa-edit me-2"></i>{{ __('admin.edit') }}</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item text-danger" href="#" data-action="delete" data-id="{{ $rootCategory->id }}"><i class="fa fa-trash me-2"></i>{{ __('admin.delete') }}</a></li>
                                        </ul>
                                    </div>
                                </div>

                                @if($rootCategory->children->count() > 0)
                                <ul class="subcategories list-unstyled" id="subcategories-{{ $rootCategory->id }}" style="display: none;">
                                    @foreach($rootCategory->children as $subcategory)
                                    <li class="subcategory-item" data-subcategory-id="{{ $subcategory->id }}">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <i class="fa fa-file me-2" style="color:rgb(99, 149, 192);"></i>
                                                <span class="subcategory-name">{{ $subcategory->name }}</span>
                                                <span class="badge bg-success ms-2">{{ $subcategory->courses_count ?? 0 }}</span>
                                            </div>
                                            <!-- Subcategory Actions -->
                                            <div class="dropdown">
                                                <button class="btn btn-sm" type="button" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#" data-action="view-courses" data-id="{{ $subcategory->id }}"><i class="fa fa-book me-2"></i>{{ __('admin.view_courses') }}</a></li>
                                                    <li><a class="dropdown-item" href="#" data-action="edit" data-id="{{ $subcategory->id }}" data-name="{{ $subcategory->name }}" data-description="{{ $subcategory->description ?? '' }}" data-bs-toggle="modal" data-bs-target="#editCategoryModal"><i class="fa fa-edit me-2"></i>{{ __('admin.edit') }}</a></li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item text-danger" href="#" data-action="delete" data-id="{{ $subcategory->id }}"><i class="fa fa-trash me-2"></i>{{ __('admin.delete') }}</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                                @endif
                            </li>
                            @empty
                            <li class="text-center py-5">
                                <i class="fa fa-folder-open fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">{{ __('admin.no_categories_found') }}</h5>
                                <p class="text-muted">{{ __('admin.no_categories_message') }}</p>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal" data-action="create">
                                    <i class="fa fa-plus me-2"></i>{{ __('admin.add_first_category') }}
                                </button>
                            </li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="col col-8"></div>
                </div>


            </div>
        </div>
    </div>
</fieldset>

<script>
    // Event listener for tree toggle clicks
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('tree-toggle')) {
            const categoryId = e.target.dataset.categoryId;
            const subcategories = document.getElementById(`subcategories-${categoryId}`);

            if (subcategories.style.display === 'none' || subcategories.style.display === '') {
                subcategories.style.display = 'block';
                e.target.classList.remove('fa-plus');
                e.target.classList.add('fa-minus');
            } else {
                subcategories.style.display = 'none';
                e.target.classList.remove('fa-minus');
                e.target.classList.add('fa-plus');
            }
        }
    });

    // Context menu actions
    function addSubcategory(parentId) {
        // Redirect to create subcategory page
        window.location.href = `/admin/categories/create?parent_id=${parentId}`;
    }

    function editCategory(categoryId) {
        // Redirect to edit category page
        window.location.href = `/admin/categories/${categoryId}/edit`;
    }

    function deleteCategory(categoryId) {
        if (confirm('{{ __("admin.confirm_delete") }}')) {
            // Submit delete form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/categories/${categoryId}`;
            form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    function viewCategoryCourses(categoryId) {
        // Redirect to courses filtered by category
        window.location.href = `/admin/courses?category=${categoryId}`;
    }

    // Event delegation for dropdown actions
    document.addEventListener('click', function(e) {
        const target = e.target.closest('[data-action]');
        if (!target) return;

        const action = target.dataset.action;
        const id = target.dataset.id;

        switch (action) {
            case 'create':
                openCreateModal(target.dataset.parentId);
                break;
            case 'edit':
                openEditModal(id, target.dataset.name, target.dataset.description);
                break;
            case 'delete':
                deleteCategory(id);
                break;
            case 'view-courses':
                viewCategoryCourses(id);
                break;
        }
    });

    // Modal functions
    function openCreateModal(parentId = null) {
        const titleElement = document.getElementById('createModalTitle');
        const parentInput = document.getElementById('parent_id');

        if (parentId) {
            titleElement.textContent = '{{ __("admin.add_subcategory") }}';
            parentInput.value = parentId;
        } else {
            titleElement.textContent = '{{ __("admin.add_root_category") }}';
            parentInput.value = '';
        }

        document.getElementById('createCategoryForm').reset();
        if (parentId) parentInput.value = parentId;
    }

    function openEditModal(categoryId, name, description) {
        document.getElementById('edit_category_id').value = categoryId;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_description').value = description;
        document.getElementById('editCategoryForm').action = `/admin/categories/${categoryId}`;
    }
</script>

<!-- Create Category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalTitle">{{ __('admin.add_root_category') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createCategoryForm" method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <input type="hidden" id="parent_id" name="parent_id" value="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('admin.name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">{{ __('admin.description') }}</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="icon" class="form-label">{{ __('admin.icon') }}</label>
                        <input type="text" class="form-control" id="icon" name="icon" placeholder="fa fa-code">
                        <small class="form-text text-muted">{{ __('admin.icon_help') }}</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('admin.create') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('admin.edit_category') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_category_id" name="category_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">{{ __('admin.name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">{{ __('admin.description') }}</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_icon" class="form-label">{{ __('admin.icon') }}</label>
                        <input type="text" class="form-control" id="edit_icon" name="icon" placeholder="fa fa-code">
                        <label class="input-group-text" id="display_icons">Pick Icon</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('admin.save') }}</button>
                </div>
            </form>
        </div>

    </div>

</div>

@endsection

@section('widgets')
<div class="widget">
    <div class="widget-header">
        <h5 class="widget-title">{{ __('admin.categories_statistics') }}</h5>
    </div>
    <div class="widget-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6>{{ __('admin.total_categories') }}</h6>
            <span class="badge bg-primary">{{ $stats['total_categories'] }}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6>{{ __('admin.active_categories') }}</h6>
            <span class="badge bg-success">{{ $stats['active_categories'] }}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span>{{ __('admin.inactive_categories') }}</span>
            <span class="badge bg-secondary">{{ $stats['inactive_categories'] }}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span>{{ __('admin.root_categories') }}</span>
            <span class="badge bg-info">{{ $stats['root_categories'] }}</span>
        </div>
    </div>
</div>

<div class="widget">
    <div class="widget-header">
        <h6>{{ __('admin.quick_actions') }}</h6>
    </div>
    <div class="widget-body">
        <div class="d-grid gap-2">
            <a href="" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i>{{ __('admin.add_category') }}
            </a>
            <a href="{{ route('admin.courses.index') }}" class="btn btn-info btn-sm">
                <i class="bi bi-book"></i>{{ __('admin.manage_courses') }}
            </a>
        </div>
    </div>
</div>
@endsection