{{-- الشهادات --}}
<div id="myProfileTabs" class="container-fluid mb-3">
    <div class="row">
        <div class="col-12">
            <h3 class="p-3 mb-4">{{ __('student.certificates') }}</h3>

            {{-- شريط البحث والفلاتر --}}
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="{{ __('student.search_certificates') }}" id="certificateSearch">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="certificateTypeFilter">
                                <option value="">{{ __('student.all_types') }}</option>
                                <option value="completion">{{ __('student.completion_certificate') }}</option>
                                <option value="achievement">{{ __('student.achievement_certificate') }}</option>
                                <option value="participation">{{ __('student.participation_certificate') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="certificateDateFilter">
                                <option value="">{{ __('student.all_dates') }}</option>
                                <option value="last_month">{{ __('student.last_month') }}</option>
                                <option value="last_3_months">{{ __('student.last_3_months') }}</option>
                                <option value="last_year">{{ __('student.last_year') }}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">{{ __('student.filter') }}</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- قائمة الشهادات --}}
            @if(isset($certificates) && $certificates->count())
            <div class="row" id="certificatesContainer">
                @foreach($certificates as $certificate)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm certificate-card">
                        <div class="card-header bg-gradient text-white text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="fas fa-certificate fa-2x mb-2"></i>
                            <h6 class="mb-0">{{ __('student.certificate_of_' . $certificate->type) }}</h6>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $certificate->course->title }}</h5>
                            <p class="card-text text-muted flex-grow-1">{{ Str::limit($certificate->course->description, 80) }}</p>

                            {{-- معلومات الشهادة --}}
                            <div class="row text-center mb-3">
                                <div class="col-6">
                                    <small class="text-muted d-block">{{ __('student.issued_date') }}</small>
                                    <strong>{{ $certificate->issued_at->format('Y-m-d') }}</strong>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">{{ __('student.certificate_id') }}</small>
                                    <strong class="text-primary">#{{ $certificate->certificate_number }}</strong>
                                </div>
                            </div>

                            {{-- تقييم الدورة --}}
                            @if($certificate->course_rating)
                            <div class="text-center mb-3">
                                <small class="text-muted d-block">{{ __('student.final_score') }}</small>
                                <div class="d-flex justify-content-center align-items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <=$certificate->course_rating)
                                        <i class="fas fa-star text-warning"></i>
                                        @else
                                        <i class="far fa-star text-muted"></i>
                                        @endif
                                        @endfor
                                        <span class="ms-2 fw-bold">{{ $certificate->course_rating }}/5</span>
                                </div>
                            </div>
                            @endif

                            {{-- أزرار العمل --}}
                            <div class="mt-auto">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('student.certificate.download', $certificate) }}" class="btn btn-success">
                                        <i class="fas fa-download me-2"></i>{{ __('student.download_certificate') }}
                                    </a>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('student.certificate.view', $certificate) }}" class="btn btn-outline-primary btn-sm" target="_blank">{{ __('student.view_certificate') }}</a>
                                        <button class="btn btn-outline-primary btn-sm dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                                            <span class="visually-hidden">{{ __('student.more_options') }}</span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" onclick="shareCertificate({{ $certificate->id }})">{{ __('student.share_certificate') }}</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="verifyCertificate('{{ $certificate->certificate_number }}')">{{ __('student.verify_certificate') }}</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="addToLinkedIn({{ $certificate->id }})">{{ __('student.add_to_linkedin') }}</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="{{ route('courses.show', $certificate->course) }}">{{ __('student.view_course') }}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- حالة التحقق --}}
                        <div class="card-footer">
                            @if($certificate->is_verified)
                            <span class="badge bg-success me-2">
                                <i class="fas fa-check-circle me-1"></i>{{ __('student.verified') }}
                            </span>
                            @else
                            <span class="badge bg-warning me-2">
                                <i class="fas fa-clock me-1"></i>{{ __('student.pending_verification') }}
                            </span>
                            @endif
                            <small class="text-muted">{{ __('student.instructor') }}: {{ $certificate->course->instructor->name }}</small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- التصفح --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $certificates->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-certificate fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">{{ __('student.no_certificates') }}</h4>
                <p class="text-muted">{{ __('student.no_certificates_desc') }}</p>
                <a href="{{ route('student.dashboard', ['tab' => 'my_courses']) }}" class="btn btn-primary">{{ __('student.complete_courses') }}</a>
            </div>
            @endif

            {{-- إحصائيات الشهادات --}}
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="card-body">
                            <h5 class="card-title text-white">{{ __('student.certificate_statistics') }}</h5>
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <h4 class="text-white">{{ $certificates->count() ?? 0 }}</h4>
                                    <small class="text-white-50">{{ __('student.total_certificates') }}</small>
                                </div>
                                <div class="col-md-3">
                                    <h4 class="text-white">{{ $verifiedCertificates ?? 0 }}</h4>
                                    <small class="text-white-50">{{ __('student.verified_certificates') }}</small>
                                </div>
                                <div class="col-md-3">
                                    <h4 class="text-white">{{ $averageRating ?? 0 }}/5</h4>
                                    <small class="text-white-50">{{ __('student.average_rating') }}</small>
                                </div>
                                <div class="col-md-3">
                                    <h4 class="text-white">{{ $totalDownloads ?? 0 }}</h4>
                                    <small class="text-white-50">{{ __('student.total_downloads') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- نصائح للحصول على المزيد من الشهادات --}}
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-primary">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>{{ __('student.certificate_tips') }}</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('student.tip_complete_courses') }}</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('student.tip_pass_assessments') }}</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>{{ __('student.tip_participate_actively') }}</li>
                                <li class="mb-0"><i class="fas fa-check text-success me-2"></i>{{ __('student.tip_meet_requirements') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // البحث في الشهادات
    document.getElementById('certificateSearch').addEventListener('input', function() {
        filterCertificates();
    });

    document.getElementById('certificateTypeFilter').addEventListener('change', function() {
        filterCertificates();
    });

    document.getElementById('certificateDateFilter').addEventListener('change', function() {
        filterCertificates();
    });

    function filterCertificates() {
        const searchTerm = document.getElementById('certificateSearch').value.toLowerCase();
        const typeFilter = document.getElementById('certificateTypeFilter').value;
        const dateFilter = document.getElementById('certificateDateFilter').value;

        const certificateCards = document.querySelectorAll('.certificate-card');

        certificateCards.forEach(card => {
            const title = card.querySelector('.card-title').textContent.toLowerCase();

            const matchesSearch = title.includes(searchTerm);

            if (matchesSearch) {
                card.closest('.col-lg-4').style.display = 'block';
            } else {
                card.closest('.col-lg-4').style.display = 'none';
            }
        });
    }

    function shareCertificate(certificateId) {
        // مشاركة الشهادة
        const shareUrl = `${window.location.origin}/certificates/verify/${certificateId}`;

        if (navigator.share) {
            navigator.share({
                title: '{{ __("student.my_certificate") }}',
                url: shareUrl
            });
        } else {
            // نسخ الرابط للحافظة
            navigator.clipboard.writeText(shareUrl).then(() => {
                alert('{{ __("student.link_copied") }}');
            });
        }
    }

    function verifyCertificate(certificateNumber) {
        // التحقق من الشهادة
        window.open(`/certificates/verify/${certificateNumber}`, '_blank');
    }

    function addToLinkedIn(certificateId) {
        // إضافة للينكد إن
        const linkedInUrl = `https://www.linkedin.com/profile/add?startTask=CERTIFICATION_NAME&name=Certificate&organizationName=Udemy Clone&issueYear=${new Date().getFullYear()}&issueMonth=${new Date().getMonth() + 1}&certUrl=${window.location.origin}/certificates/${certificateId}`;
        window.open(linkedInUrl, '_blank');
    }
</script>