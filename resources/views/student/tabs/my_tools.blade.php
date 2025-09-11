{{-- أدواتي - الأدوات والموارد المتاحة للطالب --}}
<div id="myProfileTabs" class="container-fluid mb-3">
    <div class="row">
        <div class="col-12">
            <h3 class="p-3 mb-4">{{ __('student.my_tools') }}</h3>

            {{-- الأدوات الرئيسية --}}
            <div class="row">
                {{-- أداة الملاحظات --}}
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-sticky-note fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">{{ __('student.notes') }}</h5>
                            <p class="card-text">{{ __('student.notes_desc') }}</p>
                            <a href="{{ route('student.notes') }}" class="btn btn-primary">{{ __('student.open_notes') }}</a>
                        </div>
                        <div class="card-footer text-muted">
                            <small>{{ __('student.last_updated') }}: {{ __('student.today') }}</small>
                        </div>
                    </div>
                </div>

                {{-- أداة التقويم --}}
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-calendar-alt fa-3x text-success mb-3"></i>
                            <h5 class="card-title">{{ __('student.calendar') }}</h5>
                            <p class="card-text">{{ __('student.calendar_desc') }}</p>
                            <a href="{{ route('student.calendar') }}" class="btn btn-success">{{ __('student.open_calendar') }}</a>
                        </div>
                        <div class="card-footer text-muted">
                            <small>{{ __('student.upcoming_events') }}: 3</small>
                        </div>
                    </div>
                </div>

                {{-- أداة التقييمات --}}
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-star fa-3x text-warning mb-3"></i>
                            <h5 class="card-title">{{ __('student.assessments') }}</h5>
                            <p class="card-text">{{ __('student.assessments_desc') }}</p>
                            <a href="{{ route('student.assessments') }}" class="btn btn-warning">{{ __('student.view_assessments') }}</a>
                        </div>
                        <div class="card-footer text-muted">
                            <small>{{ __('student.pending_assessments') }}: 2</small>
                        </div>
                    </div>
                </div>

                {{-- أداة المناقشات --}}
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-comments fa-3x text-info mb-3"></i>
                            <h5 class="card-title">{{ __('student.discussions') }}</h5>
                            <p class="card-text">{{ __('student.discussions_desc') }}</p>
                            <a href="{{ route('student.discussions') }}" class="btn btn-info">{{ __('student.join_discussions') }}</a>
                        </div>
                        <div class="card-footer text-muted">
                            <small>{{ __('student.new_messages') }}: 5</small>
                        </div>
                    </div>
                </div>

                {{-- أداة التحميلات --}}
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-download fa-3x text-secondary mb-3"></i>
                            <h5 class="card-title">{{ __('student.downloads') }}</h5>
                            <p class="card-text">{{ __('student.downloads_desc') }}</p>
                            <a href="{{ route('student.downloads') }}" class="btn btn-secondary">{{ __('student.manage_downloads') }}</a>
                        </div>
                        <div class="card-footer text-muted">
                            <small>{{ __('student.total_downloads') }}: 12</small>
                        </div>
                    </div>
                </div>

                {{-- أداة الإحصائيات --}}
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-chart-bar fa-3x text-danger mb-3"></i>
                            <h5 class="card-title">{{ __('student.statistics') }}</h5>
                            <p class="card-text">{{ __('student.statistics_desc') }}</p>
                            <a href="{{ route('student.statistics') }}" class="btn btn-danger">{{ __('student.view_statistics') }}</a>
                        </div>
                        <div class="card-footer text-muted">
                            <small>{{ __('student.learning_hours') }}: 45h</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- الأدوات المتقدمة --}}
            <div class="row mt-5">
                <div class="col-12">
                    <h4 class="mb-3">{{ __('student.advanced_tools') }}</h4>
                </div>

                {{-- أداة إنشاء المجموعات الدراسية --}}
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-2">
                                    <i class="fas fa-users fa-2x text-primary"></i>
                                </div>
                                <div class="col-7">
                                    <h6 class="card-title mb-1">{{ __('student.study_groups') }}</h6>
                                    <p class="card-text text-muted mb-0">{{ __('student.study_groups_desc') }}</p>
                                </div>
                                <div class="col-3">
                                    <a href="{{ route('student.study-groups') }}" class="btn btn-outline-primary btn-sm">{{ __('student.manage') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- أداة المراجعة السريعة --}}
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-2">
                                    <i class="fas fa-bolt fa-2x text-warning"></i>
                                </div>
                                <div class="col-7">
                                    <h6 class="card-title mb-1">{{ __('student.quick_review') }}</h6>
                                    <p class="card-text text-muted mb-0">{{ __('student.quick_review_desc') }}</p>
                                </div>
                                <div class="col-3">
                                    <a href="{{ route('student.quick-review') }}" class="btn btn-outline-warning btn-sm">{{ __('student.start') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- أداة تتبع الأهداف --}}
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-2">
                                    <i class="fas fa-bullseye fa-2x text-success"></i>
                                </div>
                                <div class="col-7">
                                    <h6 class="card-title mb-1">{{ __('student.goal_tracking') }}</h6>
                                    <p class="card-text text-muted mb-0">{{ __('student.goal_tracking_desc') }}</p>
                                </div>
                                <div class="col-3">
                                    <a href="{{ route('student.goals') }}" class="btn btn-outline-success btn-sm">{{ __('student.set_goals') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- أداة التقارير --}}
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-2">
                                    <i class="fas fa-file-alt fa-2x text-info"></i>
                                </div>
                                <div class="col-7">
                                    <h6 class="card-title mb-1">{{ __('student.reports') }}</h6>
                                    <p class="card-text text-muted mb-0">{{ __('student.reports_desc') }}</p>
                                </div>
                                <div class="col-3">
                                    <a href="{{ route('student.reports') }}" class="btn btn-outline-info btn-sm">{{ __('student.generate') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- إحصائيات سريعة --}}
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title">{{ __('student.quick_stats') }}</h5>
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <h4 class="text-primary">15</h4>
                                    <small class="text-muted">{{ __('student.courses_enrolled') }}</small>
                                </div>
                                <div class="col-md-3">
                                    <h4 class="text-success">8</h4>
                                    <small class="text-muted">{{ __('student.courses_completed') }}</small>
                                </div>
                                <div class="col-md-3">
                                    <h4 class="text-warning">120</h4>
                                    <small class="text-muted">{{ __('student.hours_learned') }}</small>
                                </div>
                                <div class="col-md-3">
                                    <h4 class="text-info">5</h4>
                                    <small class="text-muted">{{ __('student.certificates_earned') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>