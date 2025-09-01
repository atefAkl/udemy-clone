@extends('layouts.dashboard')

@section('title', __('app.reviews_management'))

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('app.admin_dashboard') }}</a></li>
        <li class="breadcrumb-item active">{{ __('app.reviews') }}</li>
    </ol>
</nav>
@endsection

@section('content')
@php
$isRTL = session('locale', 'ar') === 'ar';
$marginEnd = $isRTL ? 'ms-2' : 'me-2';
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('app.reviews_management') }}</h2>
</div>

<!-- Search and Filters -->
<div class="card search-filters">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reviews.index') }}">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="search" class="form-label">{{ __('app.search') }}</label>
                        <input type="text" class="form-control" id="search" name="search"
                            value="{{ request('search') }}"
                            placeholder="{{ __('app.search_reviews_placeholder') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="rating" class="form-label">{{ __('app.rating') }}</label>
                        <select class="form-select" id="rating" name="rating">
                            <option value="">{{ __('app.all_ratings') }}</option>
                            <option value="5" {{ request('rating') === '5' ? 'selected' : '' }}>5 ⭐</option>
                            <option value="4" {{ request('rating') === '4' ? 'selected' : '' }}>4 ⭐</option>
                            <option value="3" {{ request('rating') === '3' ? 'selected' : '' }}>3 ⭐</option>
                            <option value="2" {{ request('rating') === '2' ? 'selected' : '' }}>2 ⭐</option>
                            <option value="1" {{ request('rating') === '1' ? 'selected' : '' }}>1 ⭐</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="status" class="form-label">{{ __('app.status') }}</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">{{ __('app.all_statuses') }}</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>{{ __('app.approved') }}</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('app.pending') }}</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>{{ __('app.rejected') }}</option>
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

<!-- Reviews List -->
<div class="card admin-card">
    <div class="card-body">
        <div class="row">
            <!-- Sample Review Card -->
            <div class="col-12 mb-4">
                <div class="card review-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="d-flex align-items-start mb-3">
                                    <img src="https://via.placeholder.com/50x50?text=U"
                                        alt="User" class="user-avatar {{ $marginEnd }}">
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">John Student</h6>
                                                <div class="text-muted small mb-2">
                                                    <span>{{ __('app.course') }}: </span>
                                                    <a href="#" class="text-decoration-none">Complete Web Development Course</a>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <div class="rating mb-1">
                                                    <span class="text-warning">⭐⭐⭐⭐⭐</span>
                                                    <small class="text-muted">(5.0)</small>
                                                </div>
                                                <small class="text-muted">2 days ago</small>
                                            </div>
                                        </div>

                                        <p class="review-text mb-3">
                                            "This course is absolutely amazing! The instructor explains everything clearly and the projects are very practical. I learned so much and feel confident about web development now."
                                        </p>

                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <span class="badge bg-success">{{ __('app.approved') }}</span>
                                                <small class="text-muted {{ $marginEnd }}">
                                                    <i class="bi bi-heart"></i> 12 {{ __('app.helpful') }}
                                                </small>
                                            </div>
                                            <div class="review-actions">
                                                <button class="btn btn-sm btn-outline-success">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-flag"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Another Sample Review -->
            <div class="col-12 mb-4">
                <div class="card review-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="d-flex align-items-start mb-3">
                                    <img src="https://via.placeholder.com/50x50?text=S"
                                        alt="User" class="user-avatar {{ $marginEnd }}">
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">Sarah Designer</h6>
                                                <div class="text-muted small mb-2">
                                                    <span>{{ __('app.course') }}: </span>
                                                    <a href="#" class="text-decoration-none">UI/UX Design Masterclass</a>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <div class="rating mb-1">
                                                    <span class="text-warning">⭐⭐⭐</span>
                                                    <small class="text-muted">(3.0)</small>
                                                </div>
                                                <small class="text-muted">1 week ago</small>
                                            </div>
                                        </div>

                                        <p class="review-text mb-3">
                                            "The course content is good but could use more practical examples. Some sections feel rushed and need more detailed explanations."
                                        </p>

                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <span class="badge bg-warning">{{ __('app.pending') }}</span>
                                                <small class="text-muted {{ $marginEnd }}">
                                                    <i class="bi bi-heart"></i> 3 {{ __('app.helpful') }}
                                                </small>
                                            </div>
                                            <div class="review-actions">
                                                <button class="btn btn-sm btn-outline-success">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-flag"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div class="text-center py-5" style="display: none;">
            <i class="bi bi-star display-1 text-muted"></i>
            <h4 class="mt-3">{{ __('app.no_reviews_found') }}</h4>
            <p class="text-muted">{{ __('app.no_reviews_message') }}</p>
        </div>
    </div>
</div>
@endsection

@section('widgets')
<div class="widget">
    <div class="widget-header">
        <h6>{{ __('app.reviews_statistics') }}</h6>
    </div>
    <div class="widget-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span>{{ __('app.total_reviews') }}</span>
            <span class="badge bg-primary">456</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span>{{ __('app.approved_reviews') }}</span>
            <span class="badge bg-success">398</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span>{{ __('app.pending_reviews') }}</span>
            <span class="badge bg-warning">45</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span>{{ __('app.rejected_reviews') }}</span>
            <span class="badge bg-danger">13</span>
        </div>
    </div>
</div>

<div class="widget">
    <div class="widget-header">
        <h6>{{ __('app.average_ratings') }}</h6>
    </div>
    <div class="widget-body">
        <div class="text-center mb-3">
            <div class="h3 text-warning">4.2 ⭐</div>
            <small class="text-muted">{{ __('app.overall_rating') }}</small>
        </div>

        <div class="rating-breakdown">
            <div class="d-flex align-items-center mb-2">
                <span class="small">5⭐</span>
                <div class="progress flex-grow-1 mx-2" style="height: 8px;">
                    <div class="progress-bar bg-success" style="width: 65%"></div>
                </div>
                <span class="small text-muted">65%</span>
            </div>
            <div class="d-flex align-items-center mb-2">
                <span class="small">4⭐</span>
                <div class="progress flex-grow-1 mx-2" style="height: 8px;">
                    <div class="progress-bar bg-info" style="width: 20%"></div>
                </div>
                <span class="small text-muted">20%</span>
            </div>
            <div class="d-flex align-items-center mb-2">
                <span class="small">3⭐</span>
                <div class="progress flex-grow-1 mx-2" style="height: 8px;">
                    <div class="progress-bar bg-warning" style="width: 10%"></div>
                </div>
                <span class="small text-muted">10%</span>
            </div>
            <div class="d-flex align-items-center mb-2">
                <span class="small">2⭐</span>
                <div class="progress flex-grow-1 mx-2" style="height: 8px;">
                    <div class="progress-bar bg-orange" style="width: 3%"></div>
                </div>
                <span class="small text-muted">3%</span>
            </div>
            <div class="d-flex align-items-center">
                <span class="small">1⭐</span>
                <div class="progress flex-grow-1 mx-2" style="height: 8px;">
                    <div class="progress-bar bg-danger" style="width: 2%"></div>
                </div>
                <span class="small text-muted">2%</span>
            </div>
        </div>
    </div>
</div>

<div class="widget">
    <div class="widget-header">
        <h6>{{ __('app.quick_actions') }}</h6>
    </div>
    <div class="widget-body">
        <div class="d-grid gap-2">
            <a href="#" class="btn btn-warning btn-sm">
                <i class="bi bi-clock {{ $marginEnd }}"></i>{{ __('app.pending_approval') }}
            </a>
            <a href="#" class="btn btn-danger btn-sm">
                <i class="bi bi-flag {{ $marginEnd }}"></i>{{ __('app.flagged_reviews') }}
            </a>
            <a href="{{ route('admin.courses.index') }}" class="btn btn-info btn-sm">
                <i class="bi bi-book {{ $marginEnd }}"></i>{{ __('app.manage_courses') }}
            </a>
        </div>
    </div>
</div>
@endsection