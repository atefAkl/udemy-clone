@extends('layouts.dashboard')

@section('title', __('admin.all_users'))

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('admin.admin_dashboard') }}</a></li>
        <li class="breadcrumb-item active">{{ __('admin.all_users') }}</li>
    </ol>
</nav>
@endsection

@section('content')

<fieldset class="search-filters mb-2">
    <legend>
        <span>{{ __('admin.all_users') }}</span>
        <a href="{{ route('admin.users.create') }}" class="">
            <i class="fa fa-plus-circle" style="margin-inline-end: 0.5rem" title="{{ __('admin.add_user') }}"></i>
        </a>
    </legend>

    <!-- Search and Filters -->
    <div class="py-3 ">
        <form method="GET" action="{{ route('admin.users.index') }}">
            <div class="input-group mb-1">
                <label for="search" class="input-group-text">{{ __('admin.search') }}</label>
                <input type="text" class="form-control" id="search" name="search"
                    value="{{ request('search') }}"
                    placeholder="{{ __('admin.search_users_placeholder') }}"

                <label for="role" class="input-group-text">{{ __('admin.role') }}</label>
                <select class="form-select" id="role" name="role">
                    <option value="">{{ __('admin.all_roles') }}</option>
                    <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>{{ __('admin.student') }}</option>
                    <option value="instructor" {{ request('role') === 'instructor' ? 'selected' : '' }}>{{ __('admin.instructor') }}</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>{{ __('admin.admin') }}</option>
                </select>

                <label for="status" class="input-group-text">{{ __('admin.status') }}</label>
                <select class="form-select" id="status" name="status">
                    <option value="">{{ __('admin.all_statuses') }}</option>
                    <option value="1" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('admin.active') }}</option>
                    <option value="0" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('admin.inactive') }}</option>
                </select>

                <button type="submit" class="input-group-text btn btn-outline-primary">
                    <i class="fa fa-search" style="margin-inline-end: 0.5rem"></i>{{ __('admin.search') }}
                </button>
            </div>
        </form>

    </div>

    <!-- Users Table -->
    <div class="card admin-card">
        <div class="card-body py-0">
            @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover my-0">
                    <thead>
                        <tr>
                            <th>{{ __('admin.user') }}</th>
                            <th>{{ __('admin.email') }}</th>
                            <th>{{ __('admin.role') }}</th>
                            <th>{{ __('admin.status') }}</th>
                            <th>{{ __('admin.joined_date') }}</th>
                            <th>{{ __('admin.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr class="user-row">
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $user->avatar_url ?? 'https://via.placeholder.com/40x40?text=' . substr($user->name, 0, 1) }}"
                                        alt="{{ $user->name }}" class="user-avatar">
                                    <div>
                                        <div class="fw-semibold">{{ $user->name }}</div>
                                        @if($user->phone)
                                        <small class="text-muted">{{ $user->phone }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'admin')
                                <span class="badge bg-danger role-badge">{{ __('admin.admin') }}</span>
                                @elseif($user->role === 'instructor')
                                <span class="badge bg-warning role-badge">{{ __('admin.instructor') }}</span>
                                @else
                                <span class="badge bg-primary role-badge">{{ __('admin.student') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($user->deleted_at !== null)
                                <span class="status-inactive">
                                    <i class="fa fa-x-circle" style="margin-inline-end: 0.5rem"></i>{{ __('admin.inactive') }}
                                </span>
                                @else
                                <span class="status-active">
                                    <i class="fa fa-check-circle" style="margin-inline-end: 0.5rem"></i>{{ __('admin.active') }}
                                </span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="user-actions">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @if($user->deleted_at)
                                    <form method="POST" action="{{ route('admin.users.restore', $user->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success"
                                            onclick="return confirm('{{ __('admin.confirm_restore_user') }}')"
                                            <i class="fa fa-arrow-clockwise"></i>
                                        </button>
                                    </form>
                                    @else
                                    <form method="POST" action="{{ route('admin.users.delete', $user) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('{{ __('admin.confirm_delete_user') }}')"
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $users->appends(request()->query())->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-people display-1 text-muted"></i>
                <h4 class="mt-3">{{ __('admin.no_users_found') }}</h4>
                <p class="text-muted">{{ __('admin.no_users_message') }}</p>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus-circle" style="margin-inline-end: 0.5rem"></i>{{ __('admin.add_first_user') }}
                </a>
            </div>
            @endif
        </div>
    </div>
</fieldset>

@endsection

@section('widgets')
<div class="widget">
    <div class="widget-header">
        <h6>{{ __('admin.users_statistics') }}</h6>
    </div>
    <div class="widget-body">
        @if(isset($stats))
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span>{{ __('admin.total_users') }}</span>
            <span class="badge bg-primary">{{ $stats['total_users'] ?? 0 }}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span>{{ __('admin.active_users') }}</span>
            <span class="badge bg-success">{{ $stats['active_users'] ?? 0 }}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span>{{ __('admin.inactive_users') }}</span>
            <span class="badge bg-danger">{{ $stats['inactive_users'] ?? 0 }}</span>
        </div>
        @endif
    </div>
</div>

<div class="widget">
    <div class="widget-header">
        <h6>{{ __('admin.quick_actions') }}</h6>
    </div>
    <div class="widget-body">
        <div class="d-grid gap-2">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus-circle" style="margin-inline-end: 0.5rem"></i>{{ __('admin.add_user') }}
            </a>
            <a href="{{ route('admin.instructors.index') }}" class="btn btn-warning btn-sm">
                <i class="fa fa-users" style="margin-inline-end: 0.5rem"></i>{{ __('admin.manage_instructors') }}
            </a>
            <a href="{{ route('admin.students.index') }}" class="btn btn-info btn-sm">
                <i class="fa fa-graduation-cap" style="margin-inline-end: 0.5rem"></i>{{ __('admin.manage_students') }}
            </a>
        </div>
    </div>
</div>
@endsection