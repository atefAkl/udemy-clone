@extends('layouts.app')

@section('title', __('app.home'))

@section('content')
<!-- Hero Section -->
<section class="hero-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">{{ __('app.learn_without_limits') }}</h1>
                <p class="lead mb-4">
                    {{ __('app.hero_description') }}
                </p>
                <div class="d-flex gap-3">
                    <a href="{{ route('courses.index') }}" class="btn btn-light btn-lg">
                        <i class="fa-solid fa-play {{ app()->getLocale() === 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('app.browse_courses') }}
                    </a>
                    @guest
                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">
                        <i class="fa-solid fa-user-plus {{ app()->getLocale() === 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('app.join_now') }}
                    </a>
                    @endguest
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://via.placeholder.com/500x400/667eea/ffffff?text=Online+Learning"
                    alt="{{ __('app.online_learning') }}" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <div class="card border-0 bg-transparent">
                    <div class="card-body">
                        <i class="fa-solid fa-users text-primary display-4 mb-3"></i>
                        <h3 class="fw-bold">{{ number_format($stats['total_students']) }}+</h3>
                        <p class="text-muted">{{ __('app.students') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 bg-transparent">
                    <div class="card-body">
                        <i class="fa-solid fa-book text-success display-4 mb-3"></i>
                        <h3 class="fw-bold">{{ number_format($stats['total_courses']) }}+</h3>
                        <p class="text-muted">{{ __('app.courses') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 bg-transparent">
                    <div class="card-body">
                        <i class="fa-solid fa-chalkboard-user text-info display-4 mb-3"></i>
                        <h3 class="fw-bold">{{ number_format($stats['total_instructors']) }}+</h3>
                        <p class="text-muted">{{ __('app.instructors') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card border-0 bg-transparent">
                    <div class="card-body">
                        <i class="fa-solid fa-award text-warning display-4 mb-3"></i>
                        <h3 class="fw-bold">{{ number_format($stats['total_enrollments']) }}+</h3>
                        <p class="text-muted">{{ __('app.enrollments') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Courses -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="display-5 fw-bold mb-3">{{ __('app.featured_courses') }}</h2>
                <p class="lead text-muted">{{ __('app.discover_popular_courses') }}</p>
            </div>
        </div>

        <div class="row">
            @forelse($featuredCourses as $course)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card course-card h-100 shadow-sm border-0">
                    <img src="{{ $course->thumbnail_url }}" class="card-img-top" alt="{{ $course->title }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                            <span class="badge bg-primary">{{ $course->category->name }}</span>
                            <span class="badge bg-secondary">{{ $course->level }}</span>
                        </div>
                        <h5 class="card-title">{{ $course->title }}</h5>
                        <p class="card-text text-muted flex-grow-1">{{ Str::limit($course->description, 100) }}</p>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <img src="{{ $course->instructor->avatar_url }}" alt="{{ $course->instructor->name }}"
                                    class="rounded-circle {{ app()->getLocale() === 'ar' ? 'ms-2' : 'me-2' }}" width="30" height="30">
                                <small class="text-muted">{{ $course->instructor->name }}</small>
                            </div>
                            <div class="text-end">
                                @if($course->rating > 0)
                                <div class="d-flex align-items-center">
                                    <span class="text-warning {{ app()->getLocale() === 'ar' ? 'ms-1' : 'me-1' }}">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <=$course->rating)
                                            <i class="fa-solid fa-star"></i>
                                            @else
                                            <i class="fa-regular fa-star"></i>
                                            @endif
                                            @endfor
                                    </span>
                                    <small class="text-muted">({{ $course->reviews_count }})</small>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                @if($course->price > 0)
                                <span class="h5 text-primary fw-bold">${{ number_format($course->price, 2) }}</span>
                                @else
                                <span class="h5 text-success fw-bold">{{ __('app.free') }}</span>
                                @endif
                            </div>
                            <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-primary">
                                {{ __('app.view_course') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <div class="py-5">
                    <i class="fa-solid fa-book display-1 text-muted"></i>
                    <h3 class="mt-3 text-muted">{{ __('app.no_courses_available') }}</h3>
                    <p class="text-muted">{{ __('app.check_back_later') }}</p>
                </div>
            </div>
            @endforelse
        </div>

        @if($featuredCourses->count() > 0)
        <div class="text-center mt-4">
            <a href="{{ route('courses.index') }}" class="btn btn-primary btn-lg">
                <i class="fa-solid fa-arrow-right {{ app()->getLocale() === 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('app.view_all_courses') }}
            </a>
        </div>
        @endif
    </div>
</section>

<!-- Categories Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="display-5 fw-bold mb-3">{{ __('app.popular_categories') }}</h2>
                <p class="lead text-muted">{{ __('app.explore_by_category') }}</p>
            </div>
        </div>

        <div class="row">
            @forelse($categories as $category)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <a href="{{ route('courses.index', ['category' => $category->id]) }}" class="text-decoration-none">
                    <div class="card h-100 shadow-sm border-0 text-center category-card">
                        <div class="card-body">
                            <i class="fa-solid fa-{{ $category->icon ?? 'book' }} display-4 text-primary mb-3"></i>
                            <h5 class="card-title">{{ $category->name }}</h5>
                            <p class="text-muted">{{ $category->courses_count }} {{ __('app.courses') }}</p>
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12 text-center">
                <p class="text-muted">{{ __('app.no_categories_available') }}</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- CTA Section -->
@guest
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold mb-4">{{ __('app.ready_to_start') }}</h2>
                <p class="lead mb-4">
                    {{ __('app.join_thousands_description') }}
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                        <i class="fa-solid fa-user-plus {{ app()->getLocale() === 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('app.sign_up_free') }}
                    </a>
                    <a href="{{ route('courses.index') }}" class="btn btn-outline-light btn-lg">
                        <i class="fa-solid fa-search {{ app()->getLocale() === 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('app.browse_courses') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endguest
@endsection

@push('styles')
<style>
    .category-card {
        transition: all 0.3s ease;
    }

    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }
</style>
@endpush