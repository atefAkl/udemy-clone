@extends('layouts.app')

@section('title', __('app.register'))

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0">
                <div class="card-body px-4">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-user text-primary fa-3x p-3"></i>
                        <div>
                            <span class="fs-4 fw-bold p-0 m-0">{{ __('app.create_account') }}</span>
                            <p class="text-muted p-0 m-0">{{ __('app.join_community') }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Name -->
                        <div class="mb-1">
                            <div class="input-group">
                                <label for="name" class="input-group-text">
                                    <i class="fa fa-user {{ app()->getLocale() === 'ar' ? 'ms-3' : 'me-3' }}"></i>{{ __('app.full_name') }}</label>

                                <input type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    autocomplete="name"
                                    autofocus
                                    placeholder="{{ __('app.enter_full_name') }}">
                            </div>
                            @error('name')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-1">
                            <div class="input-group">
                                <label for="email" class="input-group-text">
                                    <i class="fa fa-envelope {{ app()->getLocale() === 'ar' ? 'ms-3' : 'me-3' }}"></i>
                                    {{ __('app.email') }}
                                </label>
                                <input type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autocomplete="email"
                                    placeholder="{{ __('app.enter_email') }}">
                            </div>
                            @error('email')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- User Role -->
                        <div class="mb-1">
                            <label class="form-label">{{ __('app.i_want_to') }}:</label>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <input type="radio" class="btn-check" name="role" id="student" value="student"
                                        {{ old('role', 'student') == 'student' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary w-100 p-3" for="student">
                                        <i class="fa fa-book d-block mb-2" style="font-size: 1.5rem;"></i>
                                        <strong>{{ __('app.learn') }}</strong>
                                        <small class="d-block">{{ __('app.take_courses') }}</small>
                                    </label>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="radio" class="btn-check" name="role" id="instructor" value="instructor"
                                        {{ old('role') == 'instructor' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-success w-100 p-3" for="instructor">
                                        <i class="fa fa-user-tie d-block mb-2" style="font-size: 1.5rem;"></i>
                                        <strong>{{ __('app.teach') }}</strong>
                                        <small class="d-block">{{ __('app.create_sell_courses') }}</small>
                                    </label>
                                </div>
                            </div>
                            @error('role')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-1">
                            <div class="input-group">
                                <label for="password" class="input-group-text">
                                    <i class="fa fa-lock {{ app()->getLocale() === 'ar' ? 'ms-3' : 'me-3' }}"></i>
                                    {{ __('app.password') }}
                                </label>
                                <input type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    id="password"
                                    name="password"
                                    required
                                    autocomplete="new-password"
                                    placeholder="{{ __('app.create_password') }}">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-1">
                            <div class="input-group">
                                <label for="password_confirmation" class="input-group-text">
                                    <i class="fa fa-lock {{ app()->getLocale() === 'ar' ? 'ms-3' : 'me-3' }}"></i>
                                    {{ __('app.confirm_password') }}
                                </label>
                                <input type="password"
                                    class="form-control"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    required
                                    autocomplete="new-password"
                                    placeholder="{{ __('app.confirm_your_password') }}">
                                <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <small class="text-muted">{{ __('app.password_requirements') }}</small>

                        <!-- Terms and Conditions -->
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input @error('terms') is-invalid @enderror"
                                id="terms" name="terms" required {{ old('terms') ? 'checked' : '' }}>
                            <label class="form-check-label" for="terms">
                                {{ __('app.agree_to') }} <a href="#" class="text-decoration-none">{{ __('app.terms_of_service') }}</a>
                                {{ __('app.and') }} <a href="#" class="text-decoration-none">{{ __('app.privacy_policy') }}</a>
                            </label>
                            @error('terms')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa fa-user-plus {{ app()->getLocale() === 'ar' ? 'ms-2' : 'me-2' }}"></i>{{ __('app.create_account') }}
                            </button>
                        </div>

                        <!-- Divider -->
                        <hr class="my-4">

                        <!-- Login Link -->
                        <div class="text-center">
                            <p class="mb-0">{{ __('app.already_have_account') }}
                                <a href="{{ route('login') }}" class="text-decoration-none fw-bold">
                                    {{ __('app.sign_in_here') }}
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = this.querySelector('i');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    });

    document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
        const passwordInput = document.getElementById('password_confirmation');
        const toggleIcon = this.querySelector('i');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    });

    // Password confirmation validation
    document.getElementById('password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmPassword = this.value;

        if (confirmPassword && password !== confirmPassword) {
            this.classList.add('is-invalid');
            if (!this.nextElementSibling || !this.nextElementSibling.classList.contains('invalid-feedback')) {
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.textContent = '{{ __('
                app.passwords_not_match ') }}';
                this.parentNode.insertAdjacentElement('afterend', feedback);
            }
        } else {
            this.classList.remove('is-invalid');
            const feedback = this.parentNode.nextElementSibling;
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.remove();
            }
        }
    });
</script>
@endpush