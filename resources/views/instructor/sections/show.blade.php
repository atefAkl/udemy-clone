@extends('layouts.instructor')

@section('title', $section->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">{{ $section->title }}</h1>
                <div>
                    <a href="{{ route('instructor.courses.sections.index', $section->course_id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('Back to Sections') }}
                    </a>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLessonModal">
                        <i class="fas fa-plus"></i> {{ __('Add Lesson') }}
                    </button>
                </div>
            </div>
            
            @if($section->description)
            <div class="card mb-4">
                <div class="card-body">
                    <p class="mb-0">{{ $section->description }}</p>
                </div>
            </div>
            @endif
            
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Lessons') }}</h6>
                    <div class="d-flex">
                        <div class="input-group input-group-sm" style="max-width: 250px;">
                            <input type="text" id="searchLessonsInput" class="form-control" placeholder="{{ __('Search lessons...') }}">
                            <button class="btn btn-outline-secondary" type="button" id="searchLessonsBtn">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="lessonsList" class="list-group sortable">
                        @if($section->lessons->count() > 0)
                            @foreach($section->lessons->sortBy('sort_order') as $lesson)
                                <div class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $lesson->id }}">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3 handle" style="cursor: move;">
                                            <i class="fas fa-arrows-alt-v text-muted"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">
                                                {{ $lesson->title }}
                                                @if($lesson->is_preview)
                                                    <span class="badge bg-info ms-2">{{ __('Preview') }}</span>
                                                @endif
                                            </h6>
                                            <small class="text-muted">
                                                {{ $lesson->duration ? $lesson->formatted_duration : '--:--' }} • 
                                                {{ ucfirst($lesson->lesson_type) }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ route('instructor.lessons.edit', $lesson->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger delete-lesson-btn" data-id="{{ $lesson->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                                <p class="mb-0">{{ __('No lessons found in this section.') }}</p>
                                <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addLessonModal">
                                    <i class="fas fa-plus"></i> {{ __('Add Your First Lesson') }}
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Lesson Modal -->
<div class="modal fade" id="addLessonModal" tabindex="-1" aria-labelledby="addLessonModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLessonModalLabel">{{ __('Add New Lesson') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addLessonForm">
                @csrf
                <input type="hidden" name="section_id" value="{{ $section->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="lesson_title" class="form-label">{{ __('Lesson Title') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="lesson_title" name="title" required>
                        <div class="invalid-feedback">{{ __('Please enter a lesson title.') }}</div>
                    </div>
                    <div class="mb-3">
                        <label for="lesson_type" class="form-label">{{ __('Lesson Type') }} <span class="text-danger">*</span></label>
                        <select class="form-select" id="lesson_type" name="lesson_type" required>
                            <option value="video">{{ __('Video') }}</option>
                            <option value="article">{{ __('Article') }}</option>
                            <option value="quiz">{{ __('Quiz') }}</option>
                            <option value="assignment">{{ __('Assignment') }}</option>
                        </select>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="is_preview" name="is_preview">
                        <label class="form-check-label" for="is_preview">{{ __('Allow preview (free for all users)') }}</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary" id="saveLessonBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        {{ __('Create Lesson') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteLessonModal" tabindex="-1" aria-labelledby="deleteLessonModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteLessonModalLabel">{{ __('Confirm Deletion') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('Are you sure you want to delete this lesson? This action cannot be undone.') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteLessonBtn">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    {{ __('Delete Lesson') }}
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.css">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
<script>
    $(document).ready(function() {
        const sectionId = '{{ $section->id }}';
        let lessonToDelete = null;
        
        // Initialize sortable
        const sortable = new Sortable(document.getElementById('lessonsList'), {
            handle: '.handle',
            animation: 150,
            onEnd: function() {
                updateLessonsOrder();
            }
        });
        
        // Update lessons order after sorting
        function updateLessonsOrder() {
            const lessonIds = [];
            $('#lessonsList .list-group-item').each(function(index) {
                lessonIds.push($(this).data('id'));
            });
            
            $.ajax({
                url: '/instructor/lessons/reorder',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    lesson_ids: lessonIds
                },
                success: function(response) {
                    showToast('success', '{{ __("Success") }}', '{{ __("Lesson order updated.") }}');
                },
                error: function(xhr) {
                    showToast('error', '{{ __("Error") }}', '{{ __("Failed to update lesson order.") }}');
                }
            });
        }
        
        // Handle add lesson form submission
        $('#addLessonForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const submitBtn = $('#saveLessonBtn');
            const spinner = submitBtn.find('.spinner-border');
            
            // Validate form
            if (!form[0].checkValidity()) {
                form.addClass('was-validated');
                return;
            }
            
            // Show loading state
            submitBtn.prop('disabled', true);
            spinner.removeClass('d-none');
            
            // Submit form via AJAX
            $.ajax({
                url: '{{ route("instructor.lessons.store") }}',
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    // Close modal
                    $('#addLessonModal').modal('hide');
                    
                    // Show success message
                    showToast('success', '{{ __("Success") }}', '{{ __("Lesson created successfully.") }}');
                    
                    // Reset form
                    form[0].reset();
                    form.removeClass('was-validated');
                    
                    // Reload page to show the new lesson
                    window.location.reload();
                },
                error: function(xhr) {
                    showToast('error', '{{ __("Error") }}', xhr.responseJSON?.message || '{{ __("An error occurred while creating the lesson.") }}');
                },
                complete: function() {
                    // Reset button state
                    submitBtn.prop('disabled', false);
                    spinner.addClass('d-none');
                }
            });
        });
        
        // Handle delete button click
        $(document).on('click', '.delete-lesson-btn', function() {
            lessonToDelete = $(this).data('id');
            $('#deleteLessonModal').modal('show');
        });
        
        // Handle delete confirmation
        $('#confirmDeleteLessonBtn').on('click', function() {
            if (!lessonToDelete) return;
            
            const deleteBtn = $(this);
            const spinner = deleteBtn.find('.spinner-border');
            
            // Show loading state
            deleteBtn.prop('disabled', true);
            spinner.removeClass('d-none');
            
            // Delete lesson via AJAX
            $.ajax({
                url: `/instructor/lessons/${lessonToDelete}`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                },
                success: function(response) {
                    // Close modal
                    $('#deleteLessonModal').modal('hide');
                    
                    // Show success message
                    showToast('success', '{{ __("Success") }}', '{{ __("Lesson deleted successfully.") }}');
                    
                    // Remove lesson from the list
                    $(`#lessonsList [data-id="${lessonToDelete}"]`).fadeOut(300, function() {
                        $(this).remove();
                        
                        // Show empty state if no lessons left
                        if ($('#lessonsList .list-group-item').length === 0) {
                            $('#lessonsList').html(`
                                <div class="text-center py-4">
                                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                                    <p class="mb-0">{{ __('No lessons found in this section.') }}</p>
                                    <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addLessonModal">
                                        <i class="fas fa-plus"></i> {{ __('Add Your First Lesson') }}
                                    </button>
                                </div>
                            `);
                        }
                    });
                },
                error: function(xhr) {
                    showToast('error', '{{ __("Error") }}', xhr.responseJSON?.message || '{{ __("An error occurred while deleting the lesson.") }}');
                },
                complete: function() {
                    // Reset button state
                    deleteBtn.prop('disabled', false);
                    spinner.addClass('d-none');
                    lessonToDelete = null;
                }
            });
        });
        
        // Handle search
        $('#searchLessonsBtn').on('click', function() {
            filterLessons($('#searchLessonsInput').val());
        });
        
        $('#searchLessonsInput').on('keyup', function(e) {
            if (e.key === 'Enter') {
                filterLessons($(this).val());
            }
        });
        
        // Function to filter lessons
        function filterLessons(searchTerm) {
            const searchLower = searchTerm.toLowerCase();
            let hasResults = false;
            
            $('#lessonsList .list-group-item').each(function() {
                const lessonText = $(this).text().toLowerCase();
                if (lessonText.includes(searchLower)) {
                    $(this).show();
                    hasResults = true;
                } else {
                    $(this).hide();
                }
            });
            
            // Show no results message if no matches found
            if (!hasResults) {
                $('#lessonsList').append(`
                    <div class="text-center py-4" id="noResultsMessage">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <p class="mb-0">{{ __('No lessons match your search.') }}</p>
                    </div>
                `);
            } else {
                $('#noResultsMessage').remove();
            }
        }
        
        // Helper function to show toast messages
        function showToast(type, title, message) {
            const toast = `
                <div class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <strong>${title}</strong><br>${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;
            
            const toastContainer = $('.toast-container');
            if (toastContainer.length === 0) {
                $('body').append('<div class="toast-container position-fixed bottom-0 end-0 p-3"></div>');
            }
            
            $('.toast-container').append(toast);
            const toastElement = $('.toast').last();
            const bsToast = new bootstrap.Toast(toastElement[0]);
            bsToast.show();
            
            // Remove toast after it's hidden
            toastElement.on('hidden.bs.toast', function() {
                $(this).remove();
                if ($('.toast-container').children().length === 0) {
                    $('.toast-container').remove();
                }
            });
        }
    });
</script>
@endpush
