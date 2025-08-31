@extends('layouts.app')

@section('title', 'All Courses')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-5">
        <div class="col-12">
            <h1 class="display-5 fw-bold mb-3">Explore Courses</h1>
            <p class="lead text-muted">Discover thousands of courses from expert instructors</p>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form method="GET" action="{{ route('courses.index') }}" class="row g-3">
                        <!-- Search -->
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </span>
                                <input type="text"
                                    class="form-control"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="Search courses...">
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div class="col-md-3">
                            <select name="category" class="form-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Level Filter -->
                        <div class="col-md-2">
                            <select name="level" class="form-select">
                                <option value="">All Levels</option>
                                <option value="beginner" {{ request('level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="intermediate" {{ request('level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="advanced" {{ request('level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                        </div>

                        <!-- Price Filter -->
                        <div class="col-md-2">
                            <select name="price" class="form-select">
                                <option value="">All Prices</option>
                                <option value="free" {{ request('price') == 'free' ? 'selected' : '' }}>Free</option>
                                <option value="paid" {{ request('price') == 'paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </div>

                        <!-- Search Button -->
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa-solid fa-filter"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Info -->
    <div class="row mb-4">
        <div class="col-md-6">
            <p class="text-muted mb-0">
                Showing {{ $courses->firstItem() ?? 0 }} - {{ $courses->lastItem() ?? 0 }}
                of {{ $courses->total() }} courses
            </p>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-sort me-2"></i>Sort by
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}">Newest</a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}">Most Popular</a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'rating']) }}">Highest Rated</a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}">Price: Low to High</a></li>
                    <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}">Price: High to Low</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Courses Grid -->
    <div class="row">
        @forelse($courses as $course)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card course-card h-100 shadow-sm border-0">
                <div class="position-relative">
                    <img src="{{ $course->thumbnail_url }}"
                        class="card-img-top"
                        alt="{{ $course->title }}"
                        style="height: 200px; object-fit: cover;">

                    <!-- Course Level Badge -->
                    <span class="position-absolute top-0 start-0 m-2 badge bg-dark">
                        {{ ucfirst($course->level) }}
                    </span>

                    <!-- Price Badge -->
                    <span class="position-absolute top-0 end-0 m-2 badge {{ $course->price > 0 ? 'bg-primary' : 'bg-success' }}">
                        @if($course->price > 0)
                        ${{ number_format($course->price, 2) }}
                        @else
                        Free
                        @endif
                    </span>
                </div>

                <div class="card-body d-flex flex-column">
                    <!-- Category -->
                    <div class="mb-2">
                        <span class="badge bg-light text-dark">{{ $course->category->name }}</span>
                    </div>

                    <!-- Title -->
                    <h5 class="card-title">
                        <a href="{{ route('courses.show', $course) }}" class="text-decoration-none text-dark">
                            {{ $course->title }}
                        </a>
                    </h5>

                    <!-- Description -->
                    <p class="card-text text-muted flex-grow-1">
                        {{ Str::limit($course->description, 100) }}
                    </p>

                    <!-- Instructor -->
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $course->instructor->avatar_url }}"
                            alt="{{ $course->instructor->name }}"
                            class="rounded-circle me-2"
                            width="30"
                            height="30">
                        <small class="text-muted">{{ $course->instructor->name }}</small>
                    </div>

                    <!-- Rating and Stats -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            @if($course->rating > 0)
                            <div class="d-flex align-items-center">
                                <span class="text-warning me-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <=$course->rating)
                                        <i class="fa-solid fa-star"></i>
                                        @else
                                        <i class="fa-regular fa-star"></i>
                                        @endif
                                        @endfor
                                </span>
                                <small class="text-muted">
                                    {{ number_format($course->rating, 1) }} ({{ $course->reviews_count }})
                                </small>
                            </div>
                            @else
                            <small class="text-muted">No reviews yet</small>
                            @endif
                        </div>
                        <small class="text-muted">
                            <i class="fa-solid fa-users me-1"></i>{{ $course->enrollments_count }} students
                        </small>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-primary flex-grow-1">
                            <i class="fa-solid fa-eye me-1"></i>View Details
                        </a>
                        @auth
                        @if(!auth()->user()->isEnrolledIn($course))
                        <form method="POST" action="{{ route('courses.enroll', $course) }}" class="flex-grow-1">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa-solid fa-plus me-1"></i>Enroll
                            </button>
                        </form>
                        @else
                        <a href="{{ route('courses.learn', $course) }}" class="btn btn-success flex-grow-1">
                            <i class="fa-solid fa-play me-1"></i>Continue
                        </a>
                        @endif
                        @else
                        <a href="{{ route('login') }}" class="btn btn-primary flex-grow-1">
                            <i class="fa-solid fa-plus me-1"></i>Enroll
                        </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fa-solid fa-magnifying-glass display-1 text-muted"></i>
                <h3 class="mt-3 text-muted">No courses found</h3>
                <p class="text-muted">Try adjusting your search criteria or browse all courses.</p>
                <a href="{{ route('courses.index') }}" class="btn btn-primary">
                    <i class="fa-solid fa-arrow-rotate-left me-2"></i>Clear Filters
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($courses->hasPages())
    <div class="row mt-5">
        <div class="col-12 d-flex justify-content-center">
            {{ $courses->appends(request()->query())->links() }}
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .course-card {
        transition: all 0.3s ease;
    }

    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
    }
</style>
@endpush