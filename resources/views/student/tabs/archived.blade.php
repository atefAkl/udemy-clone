{{-- الدورات المؤرشفة --}}
<div id="myProfileTabs" class="container-fluid mb-3">
    <div class="row">
        <div class="col-12">
            <h3 class="p-3 mb-4">{{ __('student.archived_courses') }}</h3>

            {{-- شريط البحث والفلاتر --}}
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="{{ __('student.search_archived_courses') }}" id="archivedSearch">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="archiveDateFilter">
                                <option value="">{{ __('student.all_dates') }}</option>
                                <option value="last_week">{{ __('student.last_week') }}</option>
                                <option value="last_month">{{ __('student.last_month') }}</option>
                                <option value="last_3_months">{{ __('student.last_3_months') }}</option>
                                <option value="last_year">{{ __('student.last_year') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary w-100">{{ __('student.filter') }}</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- قائمة الدورات المؤرشفة --}}
            @if(isset($archivedCourses) && $archivedCourses->count())
            <div class="row" id="archivedCoursesContainer">
                @foreach($archivedCourses as $enrollment)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm archived-course-card">
                        @if($enrollment->course->thumbnail)
                        <div class="position-relative">
                            <img src="{{ asset('storage/' . $enrollment->course->thumbnail) }}" class="card-img-top" alt="{{ $enrollment->course->title }}" style="height: 200px; object-fit: cover; filter: grayscale(50%);">
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-secondary">{{ __('student.archived') }}</span>
                            </div>
                        </div>
                        @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center position-relative" style="height: 200px; filter: grayscale(50%);">
                            <i class="fas fa-archive fa-3x text-muted"></i>
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-secondary">{{ __('student.archived') }}</span>
                            </div>
                        </div>
                        @endif

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-muted">{{ $enrollment->course->title }}</h5>
                            <p class="card-text text-muted flex-grow-1">{{ Str::limit($enrollment->course->description, 100) }}</p>

                            {{-- شريط التقدم النهائي --}}
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">{{ __('student.final_progress') }}</small>
                                    <small class="text-muted">{{ $enrollment->progress ?? 0 }}%</small>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-secondary" role="progressbar" style="width: @php echo $enrollment->progress ?? 0 . '%' @endphp"></div>
                                </div>
                            </div>

                            {{-- معلومات الأرشفة --}}
                            <div class="row text-center mb-3">
                                <div class="col-6">
                                    <small class="text-muted d-block">{{ __('student.archived_on') }}</small>
                                    <strong>{{ $enrollment->archived_at ? $enrollment->archived_at->format('Y-m-d') : '-' }}</strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">{{ __('student.completion_rate') }}</small>
                                    <strong>{{ $enrollment->progress ?? 0 }}%</strong>
                                </div>
                            </div>

                            {{-- أزرار العمل --}}
                            <div class="mt-auto">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-success" onclick="restoreCourse({{ $enrollment->course->id }})">
                                        {{ __('student.restore_course') }}
                                    </button>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('courses.show', $enrollment->course) }}" class="btn btn-outline-secondary btn-sm">{{ __('student.view_details') }}</a>
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                                            <span class="visually-hidden">{{ __('student.more_options') }}</span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" data-id="{{ $enrollment->course->id }}" href="#" onclick="downloadProgress(this.dataset.id)">{{ __('student.download_progress') }}</a></li>
                                            <li><a class="dropdown-item" data-id="{{ $enrollment->course->id }}" href="#" onclick="exportNotes(this.dataset.id)">{{ __('student.export_notes') }}</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item text-danger" data-id="{{ $enrollment->course->id }}" href="#" onclick="permanentDelete(this.dataset.id)">{{ __('student.delete_permanently') }}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- تاريخ التسجيل الأصلي --}}
                        <div class="card-footer text-muted">
                            <small>{{ __('student.originally_enrolled') }}: {{ $enrollment->created_at->format('Y-m-d') }}</small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- التصفح --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $archivedCourses->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-archive fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">{{ __('student.no_archived_courses') }}</h4>
                <p class="text-muted">{{ __('student.no_archived_courses_desc') }}</p>
                <a href="{{ route('student.dashboard', ['tab' => 'my_courses']) }}" class="btn btn-primary">{{ __('student.view_active_courses') }}</a>
            </div>
            @endif

            {{-- إحصائيات الأرشيف --}}
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title">{{ __('student.archive_statistics') }}</h5>
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <h4 class="text-secondary">{{ $archivedCourses->count() ?? 0 }}</h4>
                                    <small class="text-muted">{{ __('student.total_archived') }}</small>
                                </div>
                                <div class="col-md-3">
                                    <h4 class="text-success">{{ $completedArchived ?? 0 }}</h4>
                                    <small class="text-muted">{{ __('student.completed_archived') }}</small>
                                </div>
                                <div class="col-md-3">
                                    <h4 class="text-warning">{{ $partialArchived ?? 0 }}</h4>
                                    <small class="text-muted">{{ __('student.partially_completed') }}</small>
                                </div>
                                <div class="col-md-3">
                                    <h4 class="text-info">{{ $totalArchivedHours ?? 0 }}h</h4>
                                    <small class="text-muted">{{ __('student.total_archived_hours') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    // البحث في الدورات المؤرشفة
    document.getElementById('archivedSearch').addEventListener('input', function() {
        filterArchivedCourses();
    });

    document.getElementById('archiveDateFilter').addEventListener('change', function() {
        filterArchivedCourses();
    });

    function filterArchivedCourses() {
        const searchTerm = document.getElementById('archivedSearch').value.toLowerCase();
        const dateFilter = document.getElementById('archiveDateFilter').value;

        const courseCards = document.querySelectorAll('.archived-course-card');

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

    function restoreCourse(courseId) {
        if (confirm('{{ __("student.confirm_restore") }}')) {
            // استعادة الدورة
            fetch(`/student/courses/${courseId}/restore`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('{{ __("student.restore_failed") }}');
                    }
                });
        }
    }

    function downloadProgress(courseId) {
        // تحميل تقرير التقدم
        window.open(`/student/courses/${courseId}/progress-report`, '_blank');
    }

    function exportNotes(courseId) {
        // تصدير الملاحظات
        window.open(`/student/courses/${courseId}/export-notes`, '_blank');
    }

    function permanentDelete(courseId) {
        if (confirm('{{ __("student.confirm_permanent_delete") }}')) {
            // حذف نهائي
            fetch(`/student/courses/${courseId}/permanent-delete`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('{{ __("student.delete_failed") }}');
                    }
                });
        }
    }
</script>