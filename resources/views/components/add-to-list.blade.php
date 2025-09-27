@props([
'title',
'description',
'inputName',
'placeholder' => '',
'maxLength' => 150
])

<div class="add-to-list-component mb-4 border p-3 rounded">
    <h6 class="fw-bold">{{ $title }}</h6>
    <p class="small text-muted">{{ $description }}</p>

    <ul class="item-list list-group mb-3"></ul>

    <div class="input-group">
        <input type="text" class="form-control item-input" placeholder="{{ $placeholder }}" maxlength="{{ $maxLength }}">
        <span class="input-group-text item-counter">{{ $maxLength }}</span>
        <button type="button" class="input-group-text btn btn-outline-secondary submit-item">{{ __('Add To List') }}</button>
    </div>

    {{-- This hidden input will hold the final list of items as a JSON string for the form submission --}}
    <input type="hidden" name="{{ $inputName }}" id="{{ $inputName }}" class="items-json-input">
</div>