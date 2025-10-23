# Article Lesson System Setup Guide

## Overview
This guide explains the complete setup for the Article Lesson system with TinyMCE rich text editor and poster image support.

## Database Migration

Run the migration to add article fields to the lessons table:

```bash
php artisan migrate
```

This will add the following columns:
- `article_body` (longText) - HTML formatted article content
- `poster_source` (string) - 'upload' or 'url'
- `poster_file` (string) - Path to uploaded poster image
- `poster_url` (string) - External poster image URL

## Features

### 1. Rich Text Editor (TinyMCE)
- Full WYSIWYG editor with formatting options
- Image upload support (base64 embedded)
- Link insertion
- Lists, headings, alignment
- Code view
- Word count

### 2. Poster Image Options
- **Upload**: Upload poster from local machine (max 5MB)
  - Supported formats: JPEG, JPG, PNG, GIF, WEBP
  - Stored in: `storage/app/public/lessons/posters/`
- **URL**: Link to external image

### 3. Validation Rules
- Title: Required, 4-100 characters
- Description: Optional, 4-255 characters
- Article Body: Required, minimum 50 characters
- Poster Source: Required (upload or url)
- Poster File: Required if source is upload
- Poster URL: Required if source is url

## Usage

### Creating an Article Lesson

1. Navigate to Course → Edit → Curriculum
2. Click on "Add Lesson" dropdown in a section
3. Select "Article/Text"
4. Fill in the form:
   - **Title**: Lesson title
   - **Description**: Short summary
   - **Article Content**: Full article with TinyMCE editor
   - **Poster Source**: Choose upload or URL
   - **Poster Image**: Upload file or enter URL

### Article Display

Article lessons are displayed in lesson cards with:
- Poster image preview (16:9 aspect ratio)
- Title and description
- Order badge
- Source badge (Upload/URL)
- Action buttons (View, Edit, Delete)

## Files Modified/Created

### Controllers
- `app/Http/Controllers/Instructor/LessonController.php`
  - Added `storeArticle()` method
  - Updated `destroy()` to delete poster files

### Requests
- `app/Http/Requests/Courses/ArticleLessonRequest.php` (NEW)

### Views
- `resources/views/components/curriculum-builder.blade.php`
  - Added Article Lesson Modal with TinyMCE
  - Added JavaScript for modal handling and poster toggle

### Models
- `app/Models/Lesson.php`
  - Added new fillable fields

### Migrations
- `database/migrations/2025_01_23_000001_add_article_fields_to_lessons_table.php` (NEW)

### Translations
- `lang/en/courses.php` - Added article lesson translations
- `lang/en/validation.php` - Added validation messages

### Routes
- `routes/web.php` - Added `lessons.store.article` route

## TinyMCE Configuration

The editor is configured with:
- Height: 400px
- Plugins: advlist, autolink, lists, link, image, charmap, preview, anchor, searchreplace, visualblocks, code, fullscreen, insertdatetime, media, table, help, wordcount
- Toolbar: undo redo | blocks | bold italic forecolor | alignment | lists | removeformat | image link | help
- Image upload: Converts to base64 for inline storage

## CDN Used
- TinyMCE 6: `https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js`

**Note**: For production, consider getting a TinyMCE API key or self-hosting the library.

## Storage Link

Ensure the storage link is created:

```bash
php artisan storage:link
```

This creates a symbolic link from `public/storage` to `storage/app/public`.

## Security Notes

- All article content is validated
- Images uploaded are restricted to image types only
- File size limits enforced (5MB for posters)
- XSS protection through Laravel's blade escaping
- HTML content is stored in database as-is but should be sanitized on display

## Future Enhancements

- Image optimization on upload
- Server-side image upload handling for TinyMCE
- Article preview mode
- Draft saving
- Revision history
- Content templates
