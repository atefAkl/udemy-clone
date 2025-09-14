import './bootstrap';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

// Initialize Bootstrap tooltips
const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});

// Initialize popovers
const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
});

// Auto-initialize CKEditor for textareas with 'ckeditor' class
document.addEventListener('DOMContentLoaded', () => {
    const textareas = document.querySelectorAll('textarea.ckeditor');
    
    textareas.forEach(textarea => {
        const config = {
            language: document.documentElement.lang || 'en',
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo'],
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                    { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' }
                ]
            }
        };

        // Initialize CKEditor
        ClassicEditor
            .create(textarea, config)
            .catch(error => {
                console.error('Error initializing CKEditor:', error);
            });
    });
});
