<div class="row">
    <div class="col-md-6 mb-3">
        <label for="launch_date" class="form-label">{{ __('app.launch_date') }}</label>
        <input type="date" class="form-control @error('launch_date') is-invalid @enderror"
            id="launch_date" name="launch_date" value="{{ old('launch_date') }}">
        @error('launch_date')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="launch_time" class="form-label">{{ __('app.launch_time') }}</label>
        <input type="time" class="form-control @error('launch_time') is-invalid @enderror"
            id="launch_time" name="launch_time" value="{{ old('launch_time') }}">
        @error('launch_time')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12 mb-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="has_certificate"
                name="has_certificate" value="1" {{ old('has_certificate') ? 'checked' : '' }}>
            <label class="form-check-label" for="has_certificate">
                {{ __('app.course_includes_certificate') }}
            </label>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <label for="access_duration_type" class="form-label">{{ __('app.content_access') }}</label>
        <select class="form-select @error('access_duration_type') is-invalid @enderror"
            id="access_duration_type" name="access_duration_type">
            <option value="unlimited" {{ old('access_duration_type', 'unlimited') == 'unlimited' ? 'selected' : '' }}>
                {{ __('app.unlimited_access') }}
            </option>
            <option value="limited" {{ old('access_duration_type') == 'limited' ? 'selected' : '' }}>
                {{ __('app.limited_access') }}
            </option>
        </select>
        @error('access_duration_type')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3" id="access_duration_field" style="display: none;">
        <label for="access_duration_value" class="form-label">{{ __('app.access_duration_days') }}</label>
        <div class="input-group">
            <input type="number" class="form-control @error('access_duration_value') is-invalid @enderror"
                id="access_duration_value" name="access_duration_value" value="{{ old('access_duration_value') }}" min="1">
            <span class="input-group-text">{{ __('app.days') }}</span>
        </div>
        <div class="form-text">{{ __('app.number_of_days_after_enrollment') }}</div>
        @error('access_duration_value')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
