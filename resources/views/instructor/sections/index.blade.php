@extends('layouts.instructor')

@section('title', __('Sections Management'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Sections for: ') }} {{ $course->title }}</h6>
                    <a href="{{ route('instructor.courses.edit', $course) }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> {{ __('instructor.back_to_course') }}
                    </a>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-4">
                        <div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSectionModal">
                                <i class="fas fa-plus"></i> {{ __('instructor.add_new_section') }}
                            </button>
                        </div>
                        <div class="d-flex">
                            <div class="input-group" style="max-width: 300px;">
                                <input type="text" id="searchInput" class="form-control" placeholder="{{ __('instructor.search_sections') }}">
                                <button class="btn btn-outline-secondary" type="button" id="searchButton">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="sectionsTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">#</th>
                                    <th>{{ __('instructor.section_title') }}</th>
                                    <th>{{ __('instructor.section_description') }}</th>
                                    <th>{{ __('instructor.lessons') }}</th>
                                    <th>{{ __('instructor.course_status') }}</th>
                                    <th width="150">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody id="sectionsTableBody">
                                <!-- Sections will be loaded here via AJAX -->
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <div class="d-flex justify-content-center">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">{{ __('Loading...') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <div class="d-flex justify-content-center">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">{{ __('Loading...') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Section Modal -->
<div class="modal fade" id="createSectionModal" tabindex="-1" aria-labelledby="createSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createSectionModalLabel">{{ __('instructor.add_new_section') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
            </div>
            <form id="createSectionForm">
                @csrf
                <input type="hidden" name="course_id" value="{{ $course->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="sectionTitle" class="form-label">{{ __('instructor.section_title') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="sectionTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="sectionDescription" class="form-label">{{ __('instructor.section_description') }}</label>
                        <textarea class="form-control" id="sectionDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="isPublished" name="is_published" value="1" checked>
                        <label class="form-check-label" for="isPublished">
                            {{ __('instructor.publish_section') }}
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('instructor.save_changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Section Modal -->
<div class="modal fade" id="editSectionModal" tabindex="-1" aria-labelledby="editSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSectionModalLabel">{{ __('instructor.edit_section') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
            </div>
            <form id="editSectionForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="section_id" id="editSectionId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editSectionTitle" class="form-label">{{ __('instructor.section_title') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editSectionTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="editSectionDescription" class="form-label">{{ __('instructor.section_description') }}</label>
                        <textarea class="form-control" id="editSectionDescription" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="editIsPublished" name="is_published" value="1">
                        <label class="form-check-label" for="editIsPublished">
                            {{ __('instructor.publish_section') }}
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('instructor.update_section') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteSectionModal" tabindex="-1" aria-labelledby="deleteSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteSectionModalLabel">{{ __('instructor.delete_section') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('instructor.confirm_delete_section') }}</p>
                <p class="text-danger">{{ __('instructor.section_delete_warning') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    {{ __('instructor.delete') }}
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        const courseId = '{{ $course->id }}';
        let sectionToDelete = null;
        
        // Load sections on page load
        loadSections();
        
        // Handle create section form submission
        $('#createSectionForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const submitBtn = $('#saveSectionBtn');
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
                url: `/instructor/courses/${courseId}/sections`,
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    // Close modal
                    $('#createSectionModal').modal('hide');
                    
                    // Show success message
                    showToast('success', '{{ __("Success") }}', '{{ __("Section created successfully.") }}');
                    loadSections();
                },
                error: function(xhr) {
                    showToast('error', xhr.responseJSON?.message || '{{ __("An error occurred") }}');
                }
            });
        });

        // Edit section form submission
        $('#editSectionForm').on('submit', function(e) {
            e.preventDefault();
            const sectionId = $('#editSectionId').val();
            const formData = $(this).serialize();
            
            $.ajax({
                url: `/instructor/sections/${sectionId}`,
                method: 'POST',
                data: formData,
                success: function(response) {
                    $('#editSectionModal').modal('hide');
                    showToast('success', '{{ __("instructor.section_updated") }}');
                    loadSections();
                },
                error: function(xhr) {
                    showToast('error', xhr.responseJSON?.message || '{{ __("An error occurred") }}');
                }
            });
        });

        // Delete section confirmation
        $(document).on('click', '.delete-section-btn', function() {
            sectionToDelete = $(this).data('id');
            $('#deleteSectionModal').modal('show');
        });

        // Confirm delete section
        $('#confirmDeleteBtn').on('click', function() {
            if (!sectionToDelete) return;
            
            const deleteBtn = $(this);
            const spinner = deleteBtn.find('.spinner-border');
            const btnText = deleteBtn.find('.btn-text');
            
            // Show loading state
            spinner.removeClass('d-none');
            btnText.text('{{ __("Deleting...") }}');
            
            $.ajax({
                url: `/instructor/sections/${sectionToDelete}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#deleteSectionModal').modal('hide');
                    showToast('success', '{{ __("instructor.section_deleted") }}');
                    loadSections();
                },
                error: function(xhr) {
                    showToast('error', xhr.responseJSON?.message || '{{ __("An error occurred") }}');
                },
                complete: function() {
                    // Reset button state
                    spinner.addClass('d-none');
                    btnText.text('{{ __("instructor.delete") }}');
                    sectionToDelete = null;
                }
            });
        });
        
        $('#searchButton').on('click', function() {
            loadSections($('#searchInput').val());
        });
        
        $('#searchInput').on('keyup', function(e) {
            if (e.key === 'Enter') {
                loadSections($(this).val());
            }
        });
        
        // Load sections function
        function loadSections(search = '') {
            $('#sectionsTableBody').html(`
                <tr>
                    <td colspan="6" class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">{{ __('Loading...') }}</span>
                        </div>
                    </td>
                </tr>
            `);
            
            $.ajax({
                url: '{{ route("instructor.sections.index", $course) }}',
                method: 'GET',
                data: { search },
                success: function(response) {
                    if (response.sections.length === 0) {
                        $('#sectionsTableBody').html(`
                            <tr>
                                <td colspan="6" class="text-center">
                                    {{ __("instructor.no_sections_found") }}
                                </td>
                            </tr>
                        `);
                        return;
                    }
                    
                    let html = '';
                    response.sections.forEach((section, index) => {
                        const statusBadge = section.is_published 
                            ? '<span class="badge bg-success">{{ __("instructor.section_status_published") }}</span>'
                            : '<span class="badge bg-secondary">{{ __("instructor.section_status_draft") }}</span>';
                            
                        html += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${section.title}</td>
                                <td>${section.description || '-'}</td>
                                <td>${section.lessons_count || 0}</td>
                                <td>${statusBadge}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="${section.show_url}" class="btn btn-info" data-bs-toggle="tooltip" title="{{ __('instructor.view_lessons') }}">
                                            <i class="fas fa-book-open"></i>
                                        </a>
                                        <button class="btn btn-primary edit-section-btn" 
                                                data-id="${section.id}" 
                                                data-title="${section.title}" 
                                                data-description="${section.description || ''}" 
                                                data-is-published="${section.is_published ? 1 : 0}"
                                                data-bs-toggle="tooltip" 
                                                title="{{ __('instructor.edit_section') }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger delete-section-btn" 
                                                data-id="${section.id}"
                                                data-bs-toggle="tooltip" 
                                                title="{{ __('instructor.delete_section') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                    
                    $('#sectionsTableBody').html(html);
                    $('[data-bs-toggle="tooltip"]').tooltip();
                },
                error: function() {
                    $('#sectionsTableBody').html(`
                        <tr>
                            <td colspan="6" class="text-center text-danger">
                                {{ __("Failed to load sections. Please try again.") }}
                            </td>
                        </tr>
                    `);
                }
            });
        }
        
        // Handle edit button click
        $(document).on('click', '.edit-section-btn', function() {
            const sectionId = $(this).data('id');
            const title = $(this).data('title');
            const description = $(this).data('description');
            const isPublished = $(this).data('is-published') === '1';
            const isFreePreview = $(this).data('is-free-preview') === '1';
            
            // Set form values
            $('#editSectionId').val(sectionId);
            $('#editSectionTitle').val(title);
            $('#editSectionDescription').val(description);
            $('#editIsPublished').prop('checked', isPublished);
            
            // Show modal
            $('#editSectionModal').modal('show');
        });
        
        // Handle delete button click
        $(document).on('click', '.delete-section-btn', function() {
            sectionToDelete = $(this).data('id');
            $('#deleteSectionModal').modal('show');
        });
        
        // Reset form when modal is hidden
        $('#createSectionModal').on('hidden.bs.modal', function() {
            $('#createSectionForm')[0].reset();
            $('#createSectionForm').removeClass('was-validated');
        });
        
        // Helper function to show toast messages
        function showToast(type, message) {
            // Create toast container if it doesn't exist
            if ($('.toast-container').length === 0) {
                $('body').append('<div class="toast-container position-fixed bottom-0 end-0 p-3"></div>');
            }
            
            // Create unique ID for the toast
            const toastId = 'toast-' + Date.now();
            const toast = `
                <div id="${toastId}" class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="{{ __('Close') }}"></button>
                    </div>
                </div>
            `;
            
            // Add toast to container
            $('.toast-container').append(toast);
            
            // Initialize and show toast
            const toastElement = $(`#${toastId}`);
            const toastInstance = new bootstrap.Toast(toastElement, { autohide: true, delay: 5000 });
            toastInstance.show();
            
            // Handle toast removal after it's hidden
            toastElement.on('hidden.bs.toast', function() {
                $(this).remove();
                // Remove container if no more toasts
                if ($('.toast-container').children().length === 0) {
                    $('.toast-container').remove();
                }
            });
        }
    });
</script>
@endpush
