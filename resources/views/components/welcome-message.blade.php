@php
$user = Auth::user();
$role = $user->role ?? 'user';
$welcomeKey = 'welcome_shown_' . $role;
@endphp
@if(!session($welcomeKey))
@php session([$welcomeKey => true]); @endphp
<div id="welcome-message" class="alert alert-info position-fixed top-0 start-50 translate-middle-x mt-4" style="z-index:9999; min-width:300px; max-width:90vw;">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <strong>{{ __('مرحباً') }} {{ $user->name }}!</strong>
            <span>{{ __('نتمنى لك تجربة تعليمية رائعة على المنصة.') }}</span>
        </div>
        <button type="button" class="btn-close ms-3" aria-label="Dismiss" onclick="dismissWelcome()"></button>
    </div>
</div>
<script>
    function dismissWelcome() {
        document.getElementById('welcome-message').style.display = 'none';
    }
    setTimeout(dismissWelcome, 60000); // Hide after 1 minute
</script>
@endif