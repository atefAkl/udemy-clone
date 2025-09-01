@extends('layouts.dashboard')

@section('title', __('app.all_users'))

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('app.admin_dashboard') }}</a></li>
        <li class="breadcrumb-item active">{{ __('app.all_users') }}</li>
    </ol>
</nav>
@endsection

@section('content')
@php
$isRTL = sessionfa fa', 'ar') === 'a$ style="margin-inline-end: 0.5rem"isRTL ? 'ms-2' : 'me-2';
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('app.all_users') }}</h2>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="fa fa-plus-circle" style="margin-inline-end: 0.5rem"></i>{{ __('app.add_user') }}
    </a>
</div>

<!-- Search and Filters -->
<div class="card search-filters">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users.index') }}">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="search" class="form-label">{{ __('app.search') }}</label>
                        <input type="text" class="form-control" id="search" name="search"
                            value="{{ request('search') }}"
                            placeholder="{{ __('app.search_users_placeholder') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="role" class="form-label">{{ __('app.role') }}</label>
                        <select class="form-select" id="role" name="role">
                            <option value="">{{ __('app.all_roles') }}</option>
                            <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>{{ __('app.student') }}</option>
                            <option value="instructor" {{ request('role') === 'instructor' ? 'selected' : '' }}>{{ __('app.instructor') }}</option>
                            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>{{ __('app.admin') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="status" class="form-label">{{ __('app.status') }}</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">{{ __('app.all_statuses') }}</option>
                            <option value="1" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('app.active') }}</option>
                            <option value="0" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('app.inactive') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fa fa-search" style="margin-inline-end: 0.5rem"></i>{{ __('app.search') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card admin-card">
    <div class="card-body">
        @if($users->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ __('app.user') }}</th>
                        <th>{{ __('app.email') }}</th>
                        <th>{{ __('app.role') }}</th>
                        <th>{{ __('app.status') }}</th>
                        <th>{{ __('app.joined_date') }}</th>
                        <th>{{ __('app.actions') }}</th>
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
                            <span class="badge bg-danger role-badge">{{ __('app.admin') }}</span>
                            @elseif($user->role === 'instructor')
                            <span class="badge bg-warning role-badge">{{ __('app.instructor') }}</span>
                            @else
                            <span class="badge bg-primary role-badge">{{ __('app.student') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($user->deleted_at !== null)
                            <span class="status-inactive">
                                <i class="fa fa-x-circle" style="margin-inline-end: 0.5rem"></i>{{ __('app.inactive') }}
                            </span>
                            @else
                            <span class="status-active">
                                <i class="fa fa-check-circle" style="margin-inline-end: 0.5rem"></i>{{ __('app.active') }}
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
                                        onclick="return confirm('{{ __('app.confirm_restore_user') }}')">
                                        <i class="fa fa-arrow-clockwise"></i>
                                    </button>
                                </form>
                                @else
                                <form method="POST" action="{{ route('admin.users.delete', $user) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('{{ __('app.confirm_delete_user') }}')">
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
            <h4 class="mt-3">{{ __('app.no_users_found') }}</h4>
            <p class="text-muted">{{ __('app.no_users_message') }}</p>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fa fa-plus-circle" style="margin-inline-end: 0.5rem"></i>{{ __('app.add_first_user') }}
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@section('widgets')
<div class="widget">
    <div class="widget-header">
        <h6>{{ __('app.users_statistics') }}</h6>
    </div>
    <div class="widget-body">
        @if(isset($stats))
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span>{{ __('app.total_users') }}</span>
            <span class="badge bg-primary">{{ $stats['total_users'] ?? 0 }}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span>{{ __('app.active_users') }}</span>
            <span class="badge bg-success">{{ $stats['active_users'] ?? 0 }}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span>{{ __('app.inactive_users') }}</span>
            <span class="badge bg-danger">{{ $stats['inactive_users'] ?? 0 }}</span>
        </div>
        @endif
    </div>
</div>

<div class="widget">
    <div class="widget-header">
        <h6>{{ __('app.quick_actions') }}</h6>
    </div>
    <div class="widget-body">
        <div class="d-grid gap-2">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-person-plus" style="margin-inline-end: 0.5rem"></i>{{ __('app.add_user') }}
            </a>
            <a href="{{ route('admin.instructors.index') }}" class="btn btn-warning btn-sm">
                <i class="fa fa-person-badge" style="margin-inline-end: 0.5rem"></i>{{ __('app.manage_instructors') }}
            </a>
            <a href="{{ route('admin.students.index') }}" class="btn btn-info btn-sm">
                <i class="fa fa-mortarboard" style="margin-inline-end: 0.5rem"></i>{{ __('app.manage_students') }}
            </a>
        </div>
    </div>
</div>
@endsection