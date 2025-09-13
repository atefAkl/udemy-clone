<div class="row">
    <div class="col-md-12 mb-3">
        <label for="title" class="form-label">{{ __('courses.course_title') }} <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('title') is-invalid @enderror"
            id="title" name="title" value="{{ old('title') }}" maxlength="100"
            placeholder="{{ __('courses.enter_course_title') }}" required>
        <div class="form-text">{{ __('courses.title_max_100_chars') }}</div>
        @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 mb-3">
        <label for="short_description" class="form-label">{{ __('courses.short_description') }} <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('short_description') is-invalid @enderror"
            id="short_description" name="short_description" value="{{ old('short_description') }}" maxlength="160"
            placeholder="{{ __('courses.enter_short_description') }}" required>
        <div class="form-text">{{ __('courses.short_description_max_160_chars') }}</div>
        @error('short_description')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 mb-3">
        <label for="description" class="form-label">{{ __('courses.course_description') }} <span class="text-danger">*</span></label>
        <textarea class="form-control @error('description') is-invalid @enderror"
            id="description" name="description" rows="4" maxlength="500"
            placeholder="{{ __('app.enter_course_description') }}" required>{{ old('description') }}</textarea>
        <div class="form-text">{{ __('app.description_max_500_chars') }}</div>
        @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="category_id" class="form-label">{{ __('app.category') }} <span class="text-danger">*</span></label>
        <select class="form-select @error('category_id') is-invalid @enderror"
            id="category_id" name="category_id" required>
            <option value="">{{ __('app.select_category') }}</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
            @endforeach
        </select>
        @error('category_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="language" class="form-label">{{ __('app.language') }} <span class="text-danger">*</span></label>
        <select class="form-select @error('language') is-invalid @enderror"
            id="language" name="language" required>
            <option value="">{{ __('app.select_language') }}</option>
            <option value="ar" {{ old('language') == 'ar' ? 'selected' : '' }}>{{ __('app.arabic') }}</option>
            <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>{{ __('app.english') }}</option>
        </select>
        @error('language')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="target_level" class="form-label">{{ __('app.target_level') }} <span class="text-danger">*</span></label>
        <select class="form-select @error('target_level') is-invalid @enderror"
            id="target_level" name="target_level" required>
            <option value="">{{ __('app.select_target_level') }}</option>
            <option value="beginner" {{ old('target_level') == 'beginner' ? 'selected' : '' }}>{{ __('app.beginner') }}</option>
            <option value="intermediate" {{ old('target_level') == 'intermediate' ? 'selected' : '' }}>{{ __('app.intermediate') }}</option>
            <option value="advanced" {{ old('target_level') == 'advanced' ? 'selected' : '' }}>{{ __('app.advanced') }}</option>
            <option value="professional" {{ old('target_level') == 'professional' ? 'selected' : '' }}>{{ __('app.professional') }}</option>
        </select>
        @error('target_level')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="price" class="form-label">{{ __('app.price') }} <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text">$</span>
            <input type="number" class="form-control @error('price') is-invalid @enderror"
                id="price" name="price" value="{{ old('price', 0) }}" min="0" step="0.01" required>
        </div>
        <div class="form-text">{{ __('app.set_zero_for_free_course') }}</div>
        @error('price')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 mb-3">
        <label for="thumbnail" class="form-label">{{ __('app.course_thumbnail') }} <span class="text-danger">*</span></label>
        <input type="file" class="form-control @error('thumbnail') is-invalid @enderror"
            id="thumbnail" name="thumbnail" accept="image/*" required>
        <div class="form-text">{{ __('app.recommended_size') }}: 1280x720px (16:9)</div>
        @error('thumbnail')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="mt-2">
            <img id="thumbnail-preview" src="#" alt="Thumbnail Preview" class="img-thumbnail d-none" style="max-width: 300px; max-height: 169px;">
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Thumbnail preview
    document.getElementById('thumbnail').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            const preview = document.getElementById('thumbnail-preview');
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            }
            
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
