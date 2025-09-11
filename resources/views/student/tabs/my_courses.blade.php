{{-- دوراتي - الدورات المسجل بها الطالب --}}
<div id="myProfileTabs" class="container-fluid mb-3">
    <div class="row">
        <div class="col-12">
            <h3 class="p-3 mb-4">{{ __('student.my_courses') }}</h3>

            {{-- فلاتر البحث والتصنيف --}}
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="{{ __('student.search_courses') }}" id="courseSearch">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="progressFilter">
                                <option value="">{{ __('student.all_progress') }}</option>
                                <option value="not_started">{{ __('student.not_started') }}</option>
                                <option value="in_progress">{{ __('student.in_progress') }}</option>
                                <option value="completed">{{ __('student.completed') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="categoryFilter">
                                <option value="">{{ __('student.all_categories') }}</option>
                                {{-- سيتم إضافة الفئات ديناميكياً --}}
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">{{ __('student.filter') }}</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- قائمة الدورات --}}
            @if(isset($enrolledCourses) && $enrolledCourses->count())
            <div class="row" id="coursesContainer">
                @foreach($enrolledCourses as $enrollment)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm course-card">
                        @if($enrollment->course->thumbnail)
                        <img src="{{ asset('storage/' . $enrollment->course->thumbnail) }}" class="card-img-top" alt="{{ $enrollment->course->title }}" style="height: 200px; object-fit: cover;">
                        @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-play-circle fa-3x text-muted"></i>
                        </div>
                        @endif

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $enrollment->course->title }}</h5>
                            <p class="card-text text-muted flex-grow-1">{{ Str::limit($enrollment->course->description, 100) }}</p>

                            {{-- شريط التقدم --}}
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">{{ __('student.progress') }}</small>
                                    <small class="text-muted">{{ $enrollment->progress ?? 0 }}%</small>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $enrollment->progress ?? 0 }}%;" aria-valuenow="{{ $enrollment->progress ?? 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>

                            {{-- معلومات إضافية --}}
                            <div class="row text-center mb-3">
                                <div class="col-4">
                                    <small class="text-muted d-block">{{ __('student.lessons') }}</small>
                                    <strong>{{ $enrollment->course->lessons_count ?? 0 }}</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">{{ __('student.duration') }}</small>
                                    <strong>{{ $enrollment->course->duration ?? '0h' }}</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">{{ __('student.level') }}</small>
                                    <strong>{{ __('student.' . ($enrollment->course->level ?? 'beginner')) }}</strong>
                                </div>
                            </div>

                            {{-- أزرار العمل --}}
                            <div class="mt-auto">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('student.course.continue', $enrollment->course) }}" class="btn btn-primary">
                                        @if($enrollment->progress > 0)
                                        {{ __('student.continue_learning') }}
                                        @else
                                        {{ __('student.start_learning') }}
                                        @endif
                                    </a>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('courses.show', $enrollment->course) }}" class="btn btn-outline-secondary btn-sm">{{ __('student.course_details') }}</a>
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                                            <span class="visually-hidden">{{ __('student.more_options') }}</span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" data-action="add-to-wishlist" data-course-id="{{ $enrollment->course->id }}">{{ __('student.add_to_wishlist') }}</a></li>
                                            <li><a class="dropdown-item" href="#" data-action="download-certificate" data-course-id="{{ $enrollment->course->id }}">{{ __('student.download_certificate') }}</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item text-danger" href="#" data-action="unenroll-course" data-course-id="{{ $enrollment->course->id }}">{{ __('student.unenroll') }}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- تاريخ التسجيل --}}
                        <div class="card-footer text-muted">
                            <small>{{ __('student.enrolled_on') }}: {{ $enrollment->created_at->format('Y-m-d') }}</small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- التصفح --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $enrolledCourses->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-graduation-cap fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">{{ __('student.no_enrolled_courses') }}</h4>
                <p class="text-muted">{{ __('student.no_enrolled_courses_desc') }}</p>
                <a href="{{ route('courses.index') }}" class="btn btn-primary">{{ __('student.browse_courses') }}</a>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    // البحث في الدورات
    document.getElementById('courseSearch').addEventListener('input', function() {
        filterCourses();
    });

    document.getElementById('progressFilter').addEventListener('change', function() {
        filterCourses();
    });

    document.getElementById('categoryFilter').addEventListener('change', function() {
        filterCourses();
    });

    function filterCourses() {
        const searchTerm = document.getElementById('courseSearch').value.toLowerCase();
        const progressFilter = document.getElementById('progressFilter').value;
        const categoryFilter = document.getElementById('categoryFilter').value;

        const courseCards = document.querySelectorAll('.course-card');

        courseCards.forEach(card => {
            const title = card.querySelector('.card-title').textContent.toLowerCase();
            const description = card.querySelector('.card-text').textContent.toLowerCase();

            const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);

            if (matchesSearch) {
                card.closest('.col-lg-4').style.display = 'block';
            } else {
                card.closest('.col-lg-4').style.display = 'none';
            }
        });
    }

    function addToWishlist(courseId) {
        // إضافة للمفضلة
        console.log('Adding course to wishlist:', courseId);
    }

    function downloadCertificate(courseId) {
        // تحميل الشهادة
        console.log('Downloading certificate for course:', courseId);
    }

    function unenrollCourse(courseId) {
        if (confirm('{{ __("student.confirm_unenroll") }}')) {
            // إلغاء التسجيل
            console.log('Unenrolling from course:', courseId);
        }
    }
</script>