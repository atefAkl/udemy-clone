@php
$isRTL = session('locale', 'ar') === 'ar';
$align = $isRTL ? 'text-end' : 'text-start';
$reverseAlign = $isRTL ? 'text-start' : 'text-end';
$marginEnd = $isRTL ? 'ms-2' : 'me-2';
$marginStart = $isRTL ? 'me-2' : 'ms-2';
@endphp

<div class="nav-section">
    <h6 class="nav-header {{ $align }}">{{ __('app.user_management') }}</h6>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('admin.users.index') }}">
                <i class="bi bi-people {{ $marginEnd }}"></i>{{ __('app.all_users') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.users.index', ['search'=>'', 'role'=>'instructor']) }}">
                <i class="bi bi-person-badge {{ $marginEnd }}"></i>{{ __('app.instructors') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.users.index', ['search'=>'','role'=>'student']) }}">
                <i class="bi bi-mortarboard {{ $marginEnd }}"></i>{{ __('app.students') }}
            </a>
        </li>
    </ul>
</div>

<div class="nav-section">
    <h6 class="nav-header {{ $align }}">{{ __('app.content_management') }}</h6>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.courses.index') }}">
                <i class="bi bi-book {{ $marginEnd }}"></i>{{ __('app.all_courses') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.categories.index') }}">
                <i class="bi bi-tags {{ $marginEnd }}"></i>{{ __('app.categories') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.reviews.index') }}">
                <i class="bi bi-star {{ $marginEnd }}"></i>{{ __('app.reviews') }}
            </a>
        </li>
    </ul>
</div>