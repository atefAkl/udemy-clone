<div id="myProfileTabs" class="row">
    <div class="navs  bg-dark">
        <nav class="nav flex-column">
            <a class="nav-link " data-target="#account" aria-current="page" href="#">{{__('student.account')}}</a>
            <a class="nav-link  active" data-target="#profileInfo" href="#">{{__('student.profile_info')}}</a>
            <a class="nav-link " data-target="#credit" href="#">{{__('student.credit')}}</a>
            <a class="nav-link " data-target="#badges" href="#">{{__('student.badges')}}</a>
            <a class="nav-link " data-target="#files" href="">{{__('student.my_files')}}</a>
        </nav>
    </div>
    <div class="col tabs">
        <div class="" id="account">
            <div class="card">
                <div class="card-header">
                    {{__('student.account_info')}}
                </div>
                <div class="card-body">
                    <form action="">
                        @csrf
                        <div class="input-group mb-3">
                            <label for="userName" class="input-group-text">{{__('student.username')}}</label>
                            <input type="button" class="form-control" id="userName" name="name" value="{{auth()->user()->name}}">

                        </div>

                        <div class="input-group mb-3">
                            <label for="email" class="input-group-text">{{__('student.email')}}</label>
                            <input type="button" class="form-control" id="email" name="email" value="{{auth()->user()->email}}">

                        </div>

                        <div class="input-group mb-3">
                            <label for="phone" class="input-group-text">{{__('student.phone')}}</label>
                            <input type="button" class="form-control" id="phone" name="phone" value="{{auth()->user()->phone}}">

                        </div>

                        <div class="input-group mb-3">
                            <label for="role" class="input-group-text">{{__('student.role')}}</label>
                            <input type="button" class="form-control" id="role" name="role" value="{{auth()->user()->role}}">
                        </div>

                        <button class="btn btn-outline-secondary">{{__('student.submit')}}</button>
                    </form>

                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    {{__('student.change_password')}}
                </div>
                <div class="card-body">
                    <form action="">
                        @csrf
                        <div class="input-group mb-3">
                            <label for="userName" class="input-group-text">{{__('student.old_password')}}</label>
                            <input type="button" class="form-control" id="userName" name="name" value="{{auth()->user()->name}}">
                            <label class="input-group-text" onclick="openForEdit('userName')">
                                <i class="fa fa-edit"></i>
                            </label>
                        </div>

                        <div class="input-group mb-3">
                            <label for="email" class="input-group-text">{{__('student.new_password')}}</label>
                            <input type="button" class="form-control" id="email" name="email" value="{{auth()->user()->email}}">
                            <label class="input-group-text" onclick="openForEdit('email')">
                                <i class="fa fa-edit"></i>
                            </label>
                        </div>

                        <div class="input-group mb-3">
                            <label for="phone" class="input-group-text">{{__('student.confirm_password')}}</label>
                            <input type="button" class="form-control" id="phone" name="phone" value="{{auth()->user()->phone}}">
                            <label class="input-group-text" onclick="openForEdit('phone')">
                                <i class="fa fa-edit"></i>
                            </label>
                        </div>

                        <button class="btn btn-outline-secondary">{{__('student.submit')}}</button>
                    </form>
                    <!-- Password strength roles and validation -->
                </div>
            </div>

        </div>
        <div id="profileInfo" class="active">

            <div class="card">
                <div class="card-header">
                    {{__('student.profile_info')}}
                </div>
                <div class="card-body">
                    <form action="">
                        @csrf
                        <div class="input-group mb-3">
                            <!-- Name Info In Engthish -->
                            <label for="first_name" class="input-group-text">{{__('student.full_name')}}</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="{{auth()->user()->first_name}}" placeholder="{{__('student.first_name')}}">
                            <input type="text" class="form-control" id="mid_name" name="mid_name" value="{{auth()->user()->mid_name}}" placeholder="{{__('student.middle_name')}}">
                            <input type="text" class="form-control" id="family" name="family" value="{{auth()->user()->family}}" placeholder="{{__('student.family')}}">
                        </div>

                        <div class="input-group mb-3">
                            <!-- Name Info In othe language -->
                            <label for="first_name" class="input-group-text">{{__('student.full_name_ol')}}</label>
                            <input type="text" class="form-control" id="first_name_ol" name="first_name_ol" value="{{auth()->user()->first_name_ol}}" placeholder="{{__('student.first_name_ol')}}">
                            <input type="text" class="form-control" id="mid_name_ol" name="mid_name_ol" value="{{auth()->user()->mid_name_ol}}" placeholder="{{__('student.middle_name_ol')}}">
                            <input type="text" class="form-control" id="family_ol" name="family_ol" value="{{auth()->user()->family_ol}}" placeholder="{{__('student.family_ol')}}">
                        </div>

                        <div class="input-group mb-3">
                            <label for="phone" class="input-group-text">{{__('student.gender')}}</label>
                            <input type="button" class="form-control" id="phone" name="phone" value="{{auth()->user()->phone}}">
                        </div>

                        <div class="input-group mb-3">
                            <label for="birth_date" class="input-group-text">{{__('student.birth_date')}}</label>
                            <input type="button" class="form-control" id="birth_date" name="birth_date" value="{{auth()->user()->birth_date}}">
                            <!-- Date Joined -->
                            <label for="joined_at" class="input-group-text">{{__('student.joined_at')}}</label>
                            <input type="button" class="form-control" value="{{auth()->user()->createded_at}}">
                        </div>

                        <button class="btn btn-outline-secondary">{{__('student.submit')}}</button>
                    </form>

                </div>
            </div>
        </div>
        <div id="credit">
            <div class="row">
                <div class="col-12">
                    <h4 class="mb-4">{{__('student.credit_and_invoices')}}</h4>
                </div>
            </div>

            <!-- Credit Balance Card -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title mb-1">{{__('student.current_balance')}}</h5>
                                    <h2 class="mb-0">$125.50</h2>
                                </div>
                                <div>
                                    <i class="fas fa-wallet fa-3x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title mb-1">{{__('student.total_spent')}}</h5>
                                    <h2 class="mb-0">$890.25</h2>
                                </div>
                                <div>
                                    <i class="fas fa-chart-line fa-3x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{__('student.recent_transactions')}}</h5>
                    <button class="btn btn-outline-primary btn-sm">{{__('student.view_all')}}</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{__('student.date')}}</th>
                                    <th>{{__('student.description')}}</th>
                                    <th>{{__('student.amount')}}</th>
                                    <th>{{__('student.status')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2024-01-15</td>
                                    <td>{{__('student.course_purchase')}} - Advanced Laravel</td>
                                    <td class="text-danger">-$49.99</td>
                                    <td><span class="badge bg-success">{{__('student.completed')}}</span></td>
                                </tr>
                                <tr>
                                    <td>2024-01-10</td>
                                    <td>{{__('student.wallet_topup')}}</td>
                                    <td class="text-success">+$100.00</td>
                                    <td><span class="badge bg-success">{{__('student.completed')}}</span></td>
                                </tr>
                                <tr>
                                    <td>2024-01-05</td>
                                    <td>{{__('student.course_purchase')}} - React Basics</td>
                                    <td class="text-danger">-$29.99</td>
                                    <td><span class="badge bg-success">{{__('student.completed')}}</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div id="badges">
            <div class="row">
                <div class="col-12">
                    <h4 class="mb-4">{{__('student.my_bages')}}</h4>
                </div>
            </div>

            <!-- Achievement Stats -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="badge-circle bg-warning text-white mb-2">
                            <i class="fas fa-trophy fa-2x"></i>
                        </div>
                        <h5>12</h5>
                        <small class="text-muted">{{__('student.total_badges')}}</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="badge-circle bg-primary text-white mb-2">
                            <i class="fas fa-star fa-2x"></i>
                        </div>
                        <h5>8</h5>
                        <small class="text-muted">{{__('student.skill_badges')}}</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="badge-circle bg-success text-white mb-2">
                            <i class="fas fa-medal fa-2x"></i>
                        </div>
                        <h5>4</h5>
                        <small class="text-muted">{{__('student.completion_badges')}}</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <div class="badge-circle bg-info text-white mb-2">
                            <i class="fas fa-gem fa-2x"></i>
                        </div>
                        <h5>2</h5>
                        <small class="text-muted">{{__('student.special_badges')}}</small>
                    </div>
                </div>
            </div>

            <!-- Badges Grid -->
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card badge-card">
                        <div class="card-body text-center">
                            <div class="badge-icon bg-warning text-white mb-3">
                                <i class="fas fa-graduation-cap fa-2x"></i>
                            </div>
                            <h6 class="card-title">{{__('student.first_course_completed')}}</h6>
                            <p class="card-text text-muted small">{{__('student.completed_first_course')}}</p>
                            <small class="text-success">{{__('student.earned_on')}}: 2024-01-10</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card badge-card">
                        <div class="card-body text-center">
                            <div class="badge-icon bg-primary text-white mb-3">
                                <i class="fas fa-code fa-2x"></i>
                            </div>
                            <h6 class="card-title">{{__('student.coding_master')}}</h6>
                            <p class="card-text text-muted small">{{__('student.completed_5_coding_courses')}}</p>
                            <small class="text-success">{{__('student.earned_on')}}: 2024-01-15</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card badge-card">
                        <div class="card-body text-center">
                            <div class="badge-icon bg-success text-white mb-3">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                            <h6 class="card-title">{{__('student.dedicated_learner')}}</h6>
                            <p class="card-text text-muted small">{{__('student.studied_100_hours')}}</p>
                            <small class="text-success">{{__('student.earned_on')}}: 2024-01-20</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card badge-card locked">
                        <div class="card-body text-center">
                            <div class="badge-icon bg-secondary text-white mb-3">
                                <i class="fas fa-lock fa-2x"></i>
                            </div>
                            <h6 class="card-title text-muted">{{__('student.expert_level')}}</h6>
                            <p class="card-text text-muted small">{{__('student.complete_10_advanced_courses')}}</p>
                            <small class="text-muted">{{__('student.progress')}}: 7/10</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="files">
            <div class="row">
                <div class="col-12">
                    <h4 class="mb-4">{{__('student.my_files')}}</h4>
                </div>
            </div>

            <!-- Storage Info -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="card-title mb-0">{{__('student.storage_usage')}}</h6>
                                <span class="text-muted">2.3 GB / 5 GB</span>
                            </div>
                            <div class="progress mb-2" style="height: 10px;">
                                <div class="progress-bar bg-info" style="width: 46%"></div>
                            </div>
                            <small class="text-muted">{{__('student.storage_remaining')}}: 2.7 GB</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-2"></i>
                            <button class="btn btn-primary btn-sm">{{__('student.upload_file')}}</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- File Categories -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-file-pdf fa-2x text-danger mb-2"></i>
                            <h6>{{__('student.documents')}}</h6>
                            <small class="text-muted">24 {{__('student.files')}}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-image fa-2x text-success mb-2"></i>
                            <h6>{{__('student.images')}}</h6>
                            <small class="text-muted">18 {{__('student.files')}}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-video fa-2x text-primary mb-2"></i>
                            <h6>{{__('student.videos')}}</h6>
                            <small class="text-muted">7 {{__('student.files')}}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-file-archive fa-2x text-warning mb-2"></i>
                            <h6>{{__('student.archives')}}</h6>
                            <small class="text-muted">5 {{__('student.files')}}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Files -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">{{__('student.recent_files')}}</h6>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-secondary active">{{__('student.list_view')}}</button>
                        <button class="btn btn-outline-secondary">{{__('student.grid_view')}}</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-file-pdf text-danger me-3"></i>
                                <div>
                                    <h6 class="mb-0">Laravel Documentation.pdf</h6>
                                    <small class="text-muted">{{__('student.uploaded_on')}}: 2024-01-20 • 2.5 MB</small>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                    {{__('student.actions')}}
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i>{{__('student.download')}}</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-share me-2"></i>{{__('student.share')}}</a></li>
                                    <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash me-2"></i>{{__('student.delete')}}</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-image text-success me-3"></i>
                                <div>
                                    <h6 class="mb-0">project-screenshot.png</h6>
                                    <small class="text-muted">{{__('student.uploaded_on')}}: 2024-01-18 • 1.2 MB</small>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                    {{__('student.actions')}}
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i>{{__('student.download')}}</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-share me-2"></i>{{__('student.share')}}</a></li>
                                    <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash me-2"></i>{{__('student.delete')}}</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-file-code text-primary me-3"></i>
                                <div>
                                    <h6 class="mb-0">assignment-code.zip</h6>
                                    <small class="text-muted">{{__('student.uploaded_on')}}: 2024-01-15 • 856 KB</small>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                    {{__('student.actions')}}
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i>{{__('student.download')}}</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-share me-2"></i>{{__('student.share')}}</a></li>
                                    <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash me-2"></i>{{__('student.delete')}}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all navigation links and tab content divs
        const navLinks = document.querySelectorAll('#myProfileTabs .nav-link');
        const tabContents = document.querySelectorAll('#myProfileTabs .tabs > div');

        // Hide all tabs except the active one initially
        tabContents.forEach(function(tab) {
            if (!tab.classList.contains('active')) {
                tab.style.display = 'none';
            }
        });

        // Add click event listeners to navigation links
        navLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                // Remove active class from all nav links
                navLinks.forEach(function(navLink) {
                    navLink.classList.remove('active');
                });

                // Add active class to clicked link
                this.classList.add('active');

                // Hide all tab contents
                tabContents.forEach(function(tab) {
                    tab.style.display = 'none';
                    tab.classList.remove('active');
                });

                // Show the target tab content
                const targetId = this.getAttribute('data-target');
                const targetTab = document.querySelector(targetId);
                if (targetTab) {
                    targetTab.style.display = 'block';
                    targetTab.classList.add('active');
                }
            });
        });

        // Function to open fields for editing
        window.openForEdit = function(fieldId) {
            const field = document.getElementById(fieldId);
            if (field && field.type === 'button') {
                field.type = 'text';
                field.focus();
                field.select();
            }
        };
    });
</script>

<style>
    #myProfileTabs .navs {
        width: 200px;
        min-height: 400px;
    }

    #myProfileTabs .nav-link {
        color: #fff;
        padding: 12px 20px;
        margin-bottom: 5px;
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    #myProfileTabs .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    #myProfileTabs .nav-link.active {
        background-color: #007bff;
        color: #fff;
    }

    #myProfileTabs .tabs {
        flex: 1;
        padding: 20px;
    }

    #myProfileTabs .tabs>div {
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Badge Styles */
    .badge-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    .badge-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    .badge-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid #e9ecef;
    }

    .badge-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .badge-card.locked {
        opacity: 0.6;
    }

    .badge-card.locked:hover {
        transform: none;
        box-shadow: none;
    }

    /* File Management Styles */
    .list-group-item {
        border-left: none;
        border-right: none;
        border-top: none;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    /* Credit Cards */
    .card.bg-primary,
    .card.bg-success {
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .opacity-75 {
        opacity: 0.75;
    }

    /* Progress Bar Enhancement */
    .progress {
        border-radius: 10px;
        background-color: #e9ecef;
    }

    .progress-bar {
        border-radius: 10px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .badge-circle {
            width: 50px;
            height: 50px;
        }

        .badge-icon {
            width: 60px;
            height: 60px;
        }

        .badge-icon i {
            font-size: 1.5rem !important;
        }
    }
</style>