@extends('layouts.instructor-wide')

@section('title', __('instructor.instructor_dashboard'))

@push('styles')
<style>
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: none;
        border-radius: 0.5rem;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    }

    .bg-soft-primary {
        background-color: rgba(78, 115, 223, 0.1) !important;
    }

    .bg-soft-success {
        background-color: rgba(28, 200, 138, 0.1) !important;
    }

    .bg-soft-info {
        background-color: rgba(54, 185, 204, 0.1) !important;
    }

    .bg-soft-warning {
        background-color: rgba(246, 194, 62, 0.1) !important;
    }

    .bg-soft-danger {
        background-color: rgba(231, 74, 59, 0.1) !important;
    }

    .text-soft-primary {
        color: rgba(78, 115, 223, 0.8) !important;
    }
</style>
@endpush


@section('content')
<div class="container mt-5 pt-3 px-4">
    <!-- Welcome Header -->

    <x-page-title
        title="{{ __('instructor.welcome_back') }}, {{ Auth::user()->name }}"
        description="{{  __('instructor.dashboard_overview') }}"
        icon="fa fa-plus-circle"
        btn_url="{{  route('instructor.courses.create') }}"
        btn_text="{{ __('instructor.create_course') }}" />

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Courses -->
        <div class="col-md-6 col-xl-3 ">
            <div class="dashboard-card p-4 drop-shadow-on-hover">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="card-icon primary">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="mt-3">
                            <div class="card-value">{{ $stats['total_courses'] ?? 0 }}</div>
                            <div class="card-label">{{ __('instructor.total_courses') }}</div>
                        </div>
                    </div>
                    <div class="card-trend text-success">
                        <i class="fas fa-arrow-up me-1"></i> 12%
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top">
                    <span class="text-muted small">
                        <i class="fas fa-check-circle me-1"></i>
                        {{ $stats['published_courses'] ?? 0 }} {{ __('instructor.published') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Total Students -->
        <div class="col-md-6 col-xl-3">
            <div class="dashboard-card p-4 drop-shadow-on-hover">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="card-icon success">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="mt-3">
                            <div class="card-value">{{ $stats['total_students'] ?? 0 }}</div>
                            <div class="card-label">{{ __('instructor.total_students') }}</div>
                        </div>
                    </div>
                    <div class="card-trend text-success">
                        <i class="fas fa-arrow-up me-1"></i> 8.2%
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top">
                    <span class="text-muted small">
                        <i class="fas fa-user-plus me-1"></i> 24 {{ __('instructor.new_this_month') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Total Earnings -->
        <div class="col-md-6 col-xl-3">
            <div class="dashboard-card p-4 drop-shadow-on-hover">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="card-icon warning">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="mt-3">
                            <div class="card-value">${{ number_format($stats['total_revenue'] ?? 0, 0) }}</div>
                            <div class="card-label">{{ __('instructor.total_earnings') }}</div>
                        </div>
                    </div>
                    <div class="card-trend text-success">
                        <i class="fas fa-arrow-up me-1"></i> 15.3%
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top">
                    <span class="text-muted small">
                        <i class="fas fa-calendar-alt me-1"></i> {{ __('instructor.this_month') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Average Rating -->
        <div class="col-md-6 col-xl-3">
            <div class="dashboard-card p-4 drop-shadow-on-hover">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="card-icon danger">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="mt-3">
                            <div class="card-value">4.8 <small class="text-muted">/ 5.0</small></div>
                            <div class="card-label">{{ __('instructor.avg_rating') }}</div>
                        </div>
                    </div>
                    <div class="card-trend text-success">
                        <i class="fas fa-arrow-up me-1"></i> 2.1%
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top">
                    <div class="text-success">

                        <small class="text-muted ms-2">(1,024 {{ __('instructor.ratings') }})</small>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="instructor-courses" class="card drop-shadow-on-hover rounded">
        <div class=" card-header px-3 bg-white d-flex justify-content-between align-items-center py-3">
            <h6 class="mb-0 fw-bold">{{ __('instructor.recent_courses') }}</h6>
            <a href="{{ route('instructor.courses.index') }}" class="btn btn-sm btn-outline-primary">
                {{ __('instructor.view_all') }}
            </a>
        </div>
        <div class="card-body">
            <table class="table mb-0" style="overflow: show;">
                <thead class="bg-light">
                    <tr style="text-align: center">
                        <th>{{ __('instructor.course') }}</th>
                        <th>{{ __('courses.enrollments') }}</th>
                        <th>{{ __('courses.rating') }}</th>
                        <th>{{ __('instructor.status') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="" style="overflow: show;">
                    @foreach($recentCourses ?? [] as $course)
                    <tr>
                        <td>
                            @php $basURL = $course->base_url ? asset($course->base_url) : asset('storage/courses/backgrounds/OIP.webp') @endphp
                            <div class="d-flex align-items-center">
                                <img src="{{ $basURL }}"
                                    alt="{{ $course->title }}"
                                    class="rounded me-3"
                                    width="40" height="40" style="object-fit: cover;">
                                <a href="{{ route('instructor.courses.show', [$course->id]) }}">
                                    <h6 class="mb-0">{{ $course->title }}</h6>
                                    <small class="text-muted">{{ $course->category->name ?? 'Uncategorized' }}</small>
                                </a>
                            </div>
                        </td>
                        <td>{{ $course->students->count() ?? 0 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="text-warning me-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <=($course['rating'] ?? 0))
                                        <i class="fas fa-star"></i>
                                        @elseif($i - 0.5 <= ($course['rating'] ?? 0))
                                            <i class="fas fa-star-half-alt"></i>
                                            @else
                                            <i class="far fa-star"></i>
                                            @endif
                                            @endfor
                                </div>
                                <small class="text-muted">[{{ $course->average_rating }}-{{ $course->reviews_count ?? 0 }}]</small>
                            </div>
                        </td>
                        <td>
                            @php
                            $statusClass = [
                            'published' => 'success',
                            'draft' => 'secondary',
                            'pending' => 'warning',
                            'rejected' => 'danger'
                            ][$course['status'] ?? 'draft'] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">
                                {{ ucfirst($course['status'] ?? 'draft') }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-link text-muted" type="button"
                                    id="courseDropdown{{ $loop->index }}"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end"
                                    aria-labelledby="courseDropdown{{ $loop->index }}">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-eye me-2"></i>{{ __('instructor.view') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('instructor.courses.edit', $course->id) }}">
                                            <i class="fas fa-edit me-2"></i>{{ __('instructor.edit') }}
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#">
                                            <i class="fas fa-trash-alt me-2"></i>{{ __('instructor.delete') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


</div>

<div id="chart-data"
    data-labels='{{ json_encode(session('locale', 'ar') === 'ar' ? ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'] : ['January', 'February', 'March', 'April', 'May', 'June']) }}'
    data-enrollments-label="{{ __('app.new_enrollments') }}"
    data-revenue-label="{{ __('app.revenue') }} ($)"
    data-is-rtl="{{ session('locale', 'ar') === 'ar' ? 'true' : 'false' }}">
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartDataEl = document.getElementById('chart-data');
        if (!chartDataEl) return;

        const labels = JSON.parse(chartDataEl.dataset.labels);
        const enrollmentsLabel = chartDataEl.dataset.enrollmentsLabel;
        const revenueLabel = chartDataEl.dataset.revenueLabel;
        const isRtl = chartDataEl.dataset.isRtl === 'true';

        const ctx = document.getElementById('performanceChart');
        if (!ctx) return;

        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: enrollmentsLabel,
                    data: [12, 19, 3, 5, 2, 3],
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    tension: 0.4
                }, {
                    label: revenueLabel,
                    data: [300, 450, 200, 400, 150, 300],
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        rtl: isRtl,
                        textDirection: isRtl ? 'rtl' : 'ltr'
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
@include('components.welcome-message')
@endsection