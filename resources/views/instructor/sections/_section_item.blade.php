@props(['section', 'course'])

<div class="card mb-3 section-item" data-id="{{ $section->id }}">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <div class="me-3 handle" style="cursor: move;">
                <i class="fas fa-arrows-alt-v text-muted"></i>
            </div>
            <h5 class="mb-0">
                {{ $section->title }}
                @if(!$section->is_published)
                    <span class="badge bg-warning text-dark ms-2">{{ __('Draft') }}</span>
                @endif
                @if($section->is_free_preview)
                    <span class="badge bg-info ms-1">{{ __('Free Preview') }}</span>
                @endif
            </h5>
        </div>
        <div class="btn-group">
            <a href="{{ route('instructor.courses.sections.show', ['course' => $course->id, 'section' => $section->id]) }}" 
               class="btn btn-sm btn-outline-primary" 
               title="{{ __('Manage Lessons') }}">
                <i class="fas fa-list"></i> {{ __('Lessons') }} ({{ $section->lessons_count }})
            </a>
            <button class="btn btn-sm btn-outline-secondary edit-section-btn" 
                    data-id="{{ $section->id }}"
                    data-title="{{ $section->title }}"
                    data-description="{{ $section->description }}"
                    data-is-published="{{ $section->is_published ? '1' : '0' }}"
                    data-is-free-preview="{{ $section->is_free_preview ? '1' : '0' }}"
                    title="{{ __('Edit') }}">
                <i class="fas fa-edit"></i>
            </button>
            <button class="btn btn-sm btn-outline-danger delete-section-btn" 
                    data-id="{{ $section->id }}"
                    title="{{ __('Delete') }}">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>
    @if($section->description)
    <div class="card-body">
        <p class="mb-0 text-muted">{{ $section->description }}</p>
    </div>
    @endif
</div>
