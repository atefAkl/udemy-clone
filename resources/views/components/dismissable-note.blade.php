@props([
'paragraph_text',
'btn_text',
])

<div class="alert alert-secondary dismissable-note mb-4 border p-3 rounded">
    <p class="small text-muted">{{ $paragraph_text }}</p>
    <button type="button" data-bs-dismiss="alert" class="btn btn-outline-secondary">{{ $btn_text }}</button>
</div>