@extends('layouts.app')

@section('title', 'All Courses')

@section('content')
<style>
    h6.filter-section-title {
        border-block: 1px solid #ccc;
        background-color: #dedede;
        padding: 0.3rem 1rem;
        font-weight: 600;
    }
</style>
<div class="container py-3">
    <!-- Page Header -->
    <div class="row mb-2">
        <div class="col-12">
            <h4 class="fw-bold mb-1 p-0">{{__('courses.explore_courses_title')}}</h4>
            <p class="lead text-muted m-0">{{__('courses.explore_courses_description')}}</p>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="row mb-4">
        <div class="col col-12">
            <form action="">
                @csrf

                <div class="input-group mb-2">
                    <label for="search" class="input-group-text">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </label>
                    <input type="text"
                        class="form-control"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search courses...">

                    <button type="submit" class="input-group-text btn btn-outline-primary">
                        <i class="fa fa-search" style="margin-inline-end: 0.5rem;"></i>{{ __('admin.search') }}
                    </button>
                </div>
            </form>
        </div>
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
        <div class="col-3">

            <div class="shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('courses.index') }}" class="">
                        @csrf
                        @php $languages = ['en'=>'english', 'es'=>'spanish', 'fr'=>'french', 'de'=>'german', 'it'=>'italian', 'pt'=>'portuguese', 'ru'=>'russian', 'zh'=>'chinese', 'ja'=>'japanese', 'ko'=>'korean']; @endphp
                        <!-- Language Filter Section -->
                        <h6 class="filter-section-title">Language:</h6>
                        <div class="px-3 mb-2">
                            @foreach($languages as $in => $language)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{$in}}" id="{{$language}}">
                                <label class="form-check-label" for="{{$language}}">
                                    {{ucfirst($language)}}
                                </label>
                            </div>
                            @endforeach
                        </div>

                        <!-- Level Filter -->

                        <h6 class="filter-section-title">Level:</h6>
                        @php $levels = ['beginner'=>'beginner', 'intermediate'=>'intermediate', 'advanced'=>'advanced']; @endphp
                        <div class="px-3 mb-2">
                            @foreach($levels as $in => $level)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{$in}}" id="{{$level}}">
                                <label class="form-check-label" for="{{$level}}">
                                    {{ucfirst($level)}}
                                </label>
                            </div>
                            @endforeach
                        </div>
                        <!-- Level Filter -->

                        <h6 class="filter-section-title">Payment type:</h6>
                        <div class="px-3 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{$in}}" id="{{$level}}">
                                <label class="form-check-label" for="{{$level}}">
                                    {{'Paid'}}
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{$in}}" id="{{$level}}">
                                <label class="form-check-label" for="{{$level}}">
                                    {{'Free'}}
                                </label>
                            </div>

                            <!-- Price Range Filter -->

                            <div class="px-3 mb-4">
                                <div id="price-range" class="mb-3" style="padding: 20px 10px;"></div>
                                <div class="d-flex justify-content-between">
                                    <div class="input-group" style="width: 45%;">
                                        <span class="input-group-text">Min</span>
                                        <input type="number" class="form-control" id="price-min" value="0" min="0" max="1000">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <div class="input-group" style="width: 45%;">
                                        <span class="input-group-text">Max</span>
                                        <input type="number" class="form-control" id="price-max" value="1000" min="0" max="1000">
                                        <span class="input-group-text">$</span>
                                    </div>
                                </div>
                            </div>

                        </div>



                        <!-- Search Button -->
                        <div class="">
                            <button type="submit" class="btn btn-primary w-100 rounded-0">
                                <i class="fa-solid fa-filter"></i> Apply Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col col-9">
            <!-- Results Info -->
            <div class="row mb-4">
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
    </div>

    <!-- Courses Grid -->
    <div class="row">

    </div>
</div>


</div>
@endsection

@push('styles')
<!-- noUiSlider CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css" />
<style>
    .course-card {
        transition: all 0.3s ease;
    }

    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
    }

    /* noUiSlider Custom Styling */
    #price-range {
        margin: 30px 0;
    }

    .noUi-connect {
        background: #0d6efd;
    }

    .noUi-handle {
        border-radius: 50%;
        width: 18px;
        height: 18px;
        right: -9px !important;
        top: -7px;
        background: #0d6efd;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .noUi-handle:before,
    .noUi-handle:after {
        display: none;
    }
</style>
@endpush

@push('scripts')
<!-- noUiSlider JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/wnumb/1.2.0/wNumb.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the price range elements
        const priceRange = document.getElementById('price-range');
        const priceMin = document.getElementById('price-min');
        const priceMax = document.getElementById('price-max');

        // Initialize the slider
        noUiSlider.create(priceRange, {
            start: [0, 1000],
            connect: true,
            range: {
                'min': 0,
                'max': 1000
            },
            step: 10,
            format: wNumb({
                decimals: 0,
                thousand: ',',
                prefix: '$',
            })
        });

        // Update the input fields when slider values change
        priceRange.noUiSlider.on('update', function(values, handle) {
            const value = values[handle];
            if (handle) {
                priceMax.value = value.replace(/[^0-9.]/g, '');
            } else {
                priceMin.value = value.replace(/[^0-9.]/g, '');
            }
        });

        // Update the slider when input values change
        function updateSlider() {
            priceRange.noUiSlider.set([priceMin.value, priceMax.value]);
        }

        // Debounce function to prevent too many updates
        function debounce(func, wait) {
            let timeout;
            return function() {
                const context = this,
                    args = arguments;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), wait);
            };
        }

        // Add event listeners to input fields
        priceMin.addEventListener('change', updateSlider);
        priceMax.addEventListener('change', updateSlider);

        // Also update on input but debounced
        priceMin.addEventListener('input', debounce(updateSlider, 500));
        priceMax.addEventListener('input', debounce(updateSlider, 500));

        // Update form submission to include price range
        const form = document.querySelector('form[method="GET"]');
        if (form) {
            form.addEventListener('submit', function(e) {
                const minPrice = document.createElement('input');
                minPrice.type = 'hidden';
                minPrice.name = 'min_price';
                minPrice.value = priceMin.value;

                const maxPrice = document.createElement('input');
                maxPrice.type = 'hidden';
                maxPrice.name = 'max_price';
                maxPrice.value = priceMax.value;

                form.appendChild(minPrice);
                form.appendChild(maxPrice);
            });
        }
    });
</script>
@endpush