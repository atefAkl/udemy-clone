<!-- قائمة الأمنيات -->
<div id="myProfileTabs" class="container-fluid mb-3">
    <div class="row">
        <div class="col-12">
            <h3 class="p-3 mb-4">{{ __('student.wishlists') }}</h3>

            {{-- شريط البحث --}}
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="{{ __('student.search_wishlist') }}" id="wishlistSearch">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="categoryFilter">
                                <option value="">{{ __('student.all_categories') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary w-100">{{ __('student.filter') }}</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- قائمة الدورات المفضلة --}}
            @if(isset($wishlistCourses) && $wishlistCourses->count())
            <div class="row" id="wishlistContainer">
                @foreach($wishlistCourses as $course)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm wishlist-card">
                        @if($course->thumbnail)
                        <img src="{{ asset('storage/' . $course->thumbnail) }}" class="card-img-top" alt="{{ $course->title }}" style="height: 200px; object-fit: cover;">
                        @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-heart fa-3x text-danger"></i>
                        </div>
                        @endif

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $course->title }}</h5>
                            <p class="card-text text-muted flex-grow-1">{{ Str::limit($course->description, 100) }}</p>

                            {{-- معلومات السعر --}}
                            <div class="mb-3">
                                @if($course->price > 0)
                                <h5 class="text-primary">${{ $course->price }}</h5>
                                @if($course->discount_price)
                                <small class="text-muted"><del>${{ $course->original_price }}</del></small>
                                @endif
                                @else
                                <h5 class="text-success">{{ __('student.free') }}</h5>
                                @endif
                            </div>

                            {{-- أزرار العمل --}}
                            <div class="mt-auto">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('courses.show', $course) }}" class="btn btn-primary">{{ __('student.view_course') }}</a>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-success btn-sm" data-id="{{ $course->id }}" onclick="enrollCourse(this.dataset.id)">{{ __('student.enroll_now') }}</button>
                                        <button class="btn btn-outline-danger btn-sm" data-id="{{ $course->id }}" onclick="removeFromWishlist(this.dataset.id)">
                                            <i class="fas fa-heart-broken"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-heart fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">{{ __('student.no_wishlist_courses') }}</h4>
                <p class="text-muted">{{ __('student.no_wishlist_desc') }}</p>
                <a href="{{ route('courses.index') }}" class="btn btn-primary">{{ __('student.browse_courses') }}</a>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    function removeFromWishlist(courseId) {
        if (confirm('{{ __("student.confirm_remove_wishlist") }}')) {
            fetch(`/student/wishlist/${courseId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            }).then(() => location.reload());
        }
    }

    function enrollCourse(courseId) {
        window.location.href = `/courses/${courseId}/enroll`;
    }
</script>