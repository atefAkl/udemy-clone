@extends('layouts.dashboard')

@section('title', __('app.admin_dashboard'))

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active">{{ __('app.admin_dashboard') }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="welcome-section">
    <h2>{{ __('app.welcome_admin', ['name' => auth()->user()->name]) }}</h2>
    <p>{{ __('app.admin_welcome_message') }}</p>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stats-value text-primary">1,234</div>
                    <div class="stats-label">{{ __('app.total_users') }}</div>
                </div>
                <div class="stats-icon text-primary">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stats-value text-success">89</div>
                    <div class="stats-label">{{ __('app.total_courses') }}</div>
                </div>
                <div class="stats-icon text-success">
                    <i class="bi bi-book"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stats-value text-warning">45</div>
                    <div class="stats-label">{{ __('app.total_instructors') }}</div>
                </div>
                <div class="stats-icon text-warning">
                    <i class="bi bi-person-badge"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stats-value text-info">$12,450</div>
                    <div class="stats-label">{{ __('app.total_revenue') }}</div>
                </div>
                <div class="stats-icon text-info">
                    <i class="bi bi-currency-dollar"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Recent Activity -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ __('app.platform_analytics') }}</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="analyticsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ __('app.recent_activity') }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="recent-activity">
                    <div class="activity-item px-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-plus text-success"></i>
                            <div class="flex-grow-1">
                                <div>{{ __('app.new_user_registered') }}</div>
                                <div class="activity-time">{{ __('app.minutes_ago', ['count' => 5]) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="activity-item px-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-book-half text-primary"></i>
                            <div class="flex-grow-1">
                                <div>{{ __('app.new_course_published') }}</div>
                                <div class="activity-time">{{ __('app.minutes_ago', ['count' => 15]) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="activity-item px-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-star text-warning"></i>
                            <div class="flex-grow-1">
                                <div>{{ __('app.new_review_submitted') }}</div>
                                <div class="activity-time">{{ __('app.minutes_ago', ['count' => 30]) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="activity-item px-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-credit-card text-info"></i>
                            <div class="flex-grow-1">
                                <div>{{ __('app.new_enrollment') }}</div>
                                <div class="activity-time">{{ __('app.hour_ago', ['count' => 1]) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="activity-item px-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-badge text-success"></i>
                            <div class="flex-grow-1">
                                <div>{{ __('app.instructor_application') }}</div>
                                <div class="activity-time">{{ __('app.hours_ago', ['count' => 2]) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('analyticsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['{{ __('
                    app.jan ') }}', '{{ __('
                    app.feb ') }}', '{{ __('
                    app.mar ') }}', '{{ __('
                    app.apr ') }}', '{{ __('
                    app.may ') }}', '{{ __('
                    app.jun ') }}'
                ],
                datasets: [{
                    label: '{{ __('
                    app.new_users ') }}',
                    data: [65, 59, 80, 81, 56, 55],
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.1)',
                    tension: 0.1
                }, {
                    label: '{{ __('
                    app.course_enrollments ') }}',
                    data: [28, 48, 40, 19, 86, 27],
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endsection

@section('widgets')
<div class="widget">
    <div class="widget-header">
        <h6>{{ __('app.quick_actions') }}</h6>
    </div>
    <div class="widget-body">
        <div class="d-grid gap-2">
            <a href="#" class="btn btn-primary btn-sm">
                <i class="bi bi-person-plus"></i>{{ __('app.add_user') }}
            </a>
            <a href="#" class="btn btn-success btn-sm">
                <i class="bi bi-book-half"></i>{{ __('app.review_courses') }}
            </a>
            <a href="#" class="btn btn-info btn-sm">
                <i class="bi bi-graph-up"></i>{{ __('app.view_reports') }}
            </a>
            <a href="#" class="btn btn-warning btn-sm">
                <i class="bi bi-gear"></i>{{ __('app.system_settings') }}
            </a>
        </div>
    </div>
</div>

<div class="widget">
    <div class="widget-header">
        <h6>{{ __('app.system_status') }}</h6>
    </div>
    <div class="widget-body">
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <small>{{ __('app.server_status') }}</small>
                <span class="badge bg-success">{{ __('app.online') }}</span>
            </div>
        </div>
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <small>{{ __('app.database') }}</small>
                <span class="badge bg-success">{{ __('app.connected') }}</span>
            </div>
        </div>
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <small>{{ __('app.storage') }}</small>
                <span class="badge bg-warning">75%</span>
            </div>
            <div class="progress">
                <div class="progress-bar bg-warning" style="width: 75%"></div>
            </div>
        </div>
    </div>
</div>

<div class="widget">
    <div class="widget-header">
        <h6>{{ __('app.pending_approvals') }}</h6>
    </div>
    <div class="widget-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span>{{ __('app.instructor_applications') }}</span>
            <span class="badge bg-primary">3</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span>{{ __('app.course_reviews') }}</span>
            <span class="badge bg-warning">7</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span>{{ __('app.reported_content') }}</span>
            <span class="badge bg-danger">2</span>
        </div>
        <hr>
        <a href="#" class="btn btn-outline-primary btn-sm w-100">
            {{ __('app.view_all') }}
        </a>
    </div>
</div>
@endsection