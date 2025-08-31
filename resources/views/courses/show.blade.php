@extends('layouts.app')

@section('title', $course->title)

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Course Content -->
        <div class="col-lg-8">
            <!-- Course Header -->
            <div class="mb-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">Courses</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('courses.index', ['category' => $course->category->id]) }}">{{ $course->category->name }}</a></li>
                        <li class="breadcrumb-item active">{{ $course->title }}</li>
                    </ol>
                </nav>

                <h1 class="display-6 fw-bold mb-3">{{ $course->title }}</h1>
                <p class="lead text-muted mb-4">{{ $course->short_description }}</p>

                <!-- Course Meta -->
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <span class="badge bg-primary fs-6">{{ $course->category->name }}</span>
                    <span class="badge bg-secondary fs-6">{{ ucfirst($course->level) }}</span>
                    <span class="badge bg-info fs-6">
                        <i class="fa-solid fa-clock me-1"></i>{{ $course->duration }} hours
                    </span>
                    <span class="badge bg-success fs-6">
                        <i class="fa-solid fa-users me-1"></i>{{ $course->enrollments_count }} students
                    </span>
                </div>

                <!-- Rating -->
                @if($course->rating > 0)
                <div class="d-flex align-items-center mb-4">
                    <div class="text-warning me-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <=$course->rating)
                            <i class="fa-solid fa-star"></i>
                            @else
                            <i class="fa-regular fa-star"></i>
                            @endif
                            @endfor
                    </div>
                    <span class="fw-bold me-2">{{ number_format($course->rating, 1) }}</span>
                    <span class="text-muted">({{ $course->reviews_count }} reviews)</span>
                </div>
                @endif

                <!-- Instructor -->
                <div class="d-flex align-items-center mb-4">
                    <img src="{{ $course->instructor->avatar_url }}"
                        alt="{{ $course->instructor->name }}"
                        class="rounded-circle me-3"
                        width="50"
                        height="50">
                    <div>
                        <h6 class="mb-0">{{ $course->instructor->name }}</h6>
                        <small class="text-muted">{{ $course->instructor->title ?? 'Instructor' }}</small>
                    </div>
                </div>
            </div>

            <!-- Course Image -->
            <div class="mb-5">
                <img src="{{ $course->thumbnail_url }}"
                    alt="{{ $course->title }}"
                    class="img-fluid rounded shadow">
            </div>

            <!-- Course Description -->
            <div class="mb-5">
                <h3 class="fw-bold mb-3">About This Course</h3>
                <div class="course-description">
                    {!! nl2br(e($course->description)) !!}
                </div>
            </div>

            <!-- What You'll Learn -->
            @if($course->learning_objectives)
            <div class="mb-5">
                <h3 class="fw-bold mb-3">What You'll Learn</h3>
                <div class="row">
                    @foreach(json_decode($course->learning_objectives, true) as $objective)
                    <div class="col-md-6 mb-2">
                        <div class="d-flex align-items-start">
                            <i class="fa-solid fa-check-circle text-success me-2 mt-1"></i>
                            <span>{{ $objective }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Course Curriculum -->
            @if($course->lessons->count() > 0)
            <div class="mb-5">
                <h3 class="fw-bold mb-3">Course Curriculum</h3>
                <div class="accordion" id="curriculumAccordion">
                    @foreach($course->lessons as $index => $lesson)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#lesson{{ $lesson->id }}">
                                <div class="d-flex justify-content-between w-100 me-3">
                                    <span>{{ $lesson->title }}</span>
                                    <span class="text-muted">
                                        <i class="fa-solid fa-clock me-1"></i>{{ $lesson->duration }} min
                                    </span>
                                </div>
                            </button>
                        </h2>
                        <div id="lesson{{ $lesson->id }}"
                            class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                            data-bs-parent="#curriculumAccordion">
                            <div class="accordion-body">
                                <p class="text-muted mb-2">{{ $lesson->description }}</p>
                                @if($lesson->video_url)
                                <small class="text-success">
                                    <i class="fa-solid fa-play me-1"></i>Video Content
                                </small>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Requirements -->
            @if($course->requirements)
            <div class="mb-5">
                <h3 class="fw-bold mb-3">Requirements</h3>
                <ul class="list-unstyled">
                    @foreach(json_decode($course->requirements, true) as $requirement)
                    <li class="mb-2">
                        <i class="fa-solid fa-circle text-primary"></i>{{ $requirement }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Reviews -->
            @if($course->reviews->count() > 0)
            <div class="mb-5">
                <h3 class="fw-bold mb-4">Student Reviews</h3>

                <!-- Rating Summary -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="display-4 fw-bold text-primary">{{ number_format($course->rating, 1) }}</div>
                            <div class="text-warning mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <=$course->rating)
                                    <i class="fa-solid fa-star"></i>
                                    @else
                                    <i class="fa-regular fa-star"></i>
                                    @endif
                                    @endfor
                            </div>
                            <small class="text-muted">{{ $course->reviews_count }} reviews</small>
                        </div>
                    </div>
                    <div class="col-md-8">
                        @for($i = 5; $i >= 1; $i--)
                        @php
                        $count = $course->reviews()->where('rating', $i)->count();
                        $percentage = $course->reviews_count > 0 ? ($count / $course->reviews_count) * 100 : 0;
                        @endphp
                        <div class="d-flex align-items-center mb-2">
                            <span class="me-2">{{ $i }} star</span>
                            <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                <div class="progress-bar bg-warning" style="width: {{ $percentage }}%"></div>
                            </div>
                            <small class="text-muted">{{ $count }}</small>
                        </div>
                        @endfor
                    </div>
                </div>

                <!-- Individual Reviews -->
                <div class="reviews-list">
                    @foreach($course->reviews()->latest()->take(5)->get() as $review)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $review->user->avatar_url }}"
                                        alt="{{ $review->user->name }}"
                                        class="rounded-circle me-2"
                                        width="40"
                                        height="40">
                                    <div>
                                        <h6 class="mb-0">{{ $review->user->name }}</h6>
                                        <div class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <=$review->rating)
                                                <i class="fa-solid fa-star"></i>
                                                @else
                                                <i class="fa-regular fa-star"></i>
                                                @endif
                                                @endfor
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-0">{{ $review->comment }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($course->reviews_count > 5)
                <div class="text-center">
                    <button class="btn btn-outline-primary" onclick="loadMoreReviews()">
                        Load More Reviews
                    </button>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 2rem;">
                <!-- Enrollment Card -->
                <div class="card shadow-lg border-0 mb-4">
                    <div class="card-body p-4">
                        <!-- Price -->
                        <div class="text-center mb-4">
                            @if($course->price > 0)
                            <div class="display-5 fw-bold text-primary">${{ number_format($course->price, 2) }}</div>
                            @else
                            <div class="display-5 fw-bold text-success">Free</div>
                            @endif
                        </div>

                        <!-- Enrollment Button -->
                        @auth
                        @if(auth()->user()->isEnrolledIn($course))
                        <div class="d-grid mb-3">
                            <a href="{{ route('courses.learn', $course) }}" class="btn btn-success btn-lg">
                                <i class="fa-solid fa-play me-2"></i>Continue Learning
                            </a>
                        </div>
                        <div class="text-center">
                            <small class="text-success">
                                <i class="fa-solid fa-check-circle me-1"></i>You're enrolled in this course
                            </small>
                        </div>
                        @else
                        <div class="d-grid mb-3">
                            <form method="POST" action="{{ route('courses.enroll', $course) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fa-solid fa-plus me-2"></i>Enroll Now
                                </button>
                            </form>
                        </div>
                        @endif
                        @else
                        <div class="d-grid mb-3">
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                                <i class="fa-solid fa-right-to-bracket me-2"></i>Login to Enroll
                            </a>
                        </div>
                        @endauth

                        <!-- Course Features -->
                        <hr>
                        <div class="course-features">
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="fa-solid fa-clock me-2"></i>Duration</span>
                                <span>{{ $course->duration }} hours</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="fa-solid fa-play me-2"></i>Lessons</span>
                                <span>{{ $course->lessons_count }} lessons</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="fa-solid fa-signal me-2"></i>Level</span>
                                <span>{{ ucfirst($course->level) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="fa-solid fa-award me-2"></i>Certificate</span>
                                <span>Yes</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span><i class="fa-solid fa-infinity me-2"></i>Access</span>
                                <span>Lifetime</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Share Course -->
                <div class="card border-0 bg-light">
                    <div class="card-body text-center">
                        <h6 class="fw-bold mb-3">Share this course</h6>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="fa-brands fa-facebook"></i>
                            </a>
                            <a href="#" class="btn btn-outline-info btn-sm">
                                <i class="fa-brands fa-twitter"></i>
                            </a>
                            <a href="#" class="btn btn-outline-success btn-sm">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>
                            <a href="#" class="btn btn-outline-secondary btn-sm">
                                <i class="fa-solid fa-link"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function loadMoreReviews() {
        // Implementation for loading more reviews via AJAX
        console.log('Loading more reviews...');
    }
</script>
@endpush