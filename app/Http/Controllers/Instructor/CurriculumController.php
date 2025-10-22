<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Section;
use App\Models\Lesson;
use App\Models\LessonResource;
use App\Http\Requests\Courses\SectionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CurriculumController extends Controller
{
    // ==========================================
    // Section Management
    // ==========================================

    /**
     * Store a new section
     */
    public function storeSection(SectionRequest $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            $section = Section::create([
                'course_id' => $course->id,
                'title' => $request->title,
                'description' => $request->description,
                'sort_order' => $course->sections()->count() + 1,
            ]);

            return response()->json([
                'success' => true,
                'message' => __('courses.section_saved_successfully'),
                'section' => $section
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('courses.error_saving_section' . $e->getMessage())
            ], 500);
        }
    }

    /**
     * Update a section
     */
    public function updateSection(Request $request, Section $section)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            $section->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => true,
                'message' => __('courses.section_updated_successfully'),
                'section' => $section
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('courses.error_updating_section')
            ], 500);
        }
    }

    /**
     * Delete a section
     */
    public function deleteSection(Section $section)
    {
        try {
            // Delete all lessons and their files
            foreach ($section->lessons as $lesson) {
                $this->deleteLessonFiles($lesson);
            }

            $section->delete();

            return response()->json([
                'success' => true,
                'message' => __('courses.section_deleted_successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('courses.error_deleting_section')
            ], 500);
        }
    }

    // ==========================================
    // Lesson Management
    // ==========================================

    /**
     * Store a new lesson
     */
    public function storeLesson(Request $request, Section $section)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,article,file',
            'description' => 'nullable|string|max:255',

            // Video validation
            'video' => 'nullable|file|mimes:mp4,avi,mov,wmv,flv,mkv|max:512000', // max 500MB

            // Article validation
            'image' => 'nullable|file|mimes:jpeg,jpg,png,gif,webp|max:5120', // max 5MB
            'content' => 'nullable|string',

            // Files validation
            'files.*' => 'nullable|file|max:20480', // max 20MB per file
            'downloadable' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $lesson = Lesson::create([
                'section_id'    => $section->id,
                'course_id'     => $section->course_id,
                'title'         => $request->title,
                'content_type'  => $request->type,  // ✅ تصحيح: content_type بدلاً من type
                'description'   => $request->description ?? 'No description',
                'sort_order'    => $section->lessons()->count() + 1,
            ]);

            // Handle type-specific files
            if ($request->type === 'video') {
                $this->handleVideoUpload($lesson, $request);
            } elseif ($request->type === 'article') {
                $this->handleArticleUpload($lesson, $request);
            } elseif ($request->type === 'file') {
                $this->handleFilesUpload($lesson, $request);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('courses.lesson_saved_successfully'),
                'lesson' => $lesson->load('resources')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => __('courses.error_saving_lesson') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a lesson
     */
    public function updateLesson(Request $request, Lesson $lesson)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,article,file',
            'description' => 'nullable|string|max:255',

            // Video validation
            'video' => 'nullable|file|mimes:mp4,avi,mov,wmv,flv,mkv|max:512000', // max 500MB

            // Article validation
            'image' => 'nullable|file|mimes:jpeg,jpg,png,gif,webp|max:5120', // max 5MB
            'content' => 'nullable|string',

            // Files validation
            'files.*' => 'nullable|file|max:20480', // max 20MB per file
            'downloadable' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $lesson->update([
                'title' => $request->title,
                'content_type' => $request->type,  // ✅ تصحيح: content_type بدلاً من type
                'description' => $request->description,
            ]);

            // Handle type-specific files
            if ($request->type === 'video') {
                $this->handleVideoUpload($lesson, $request);
            } elseif ($request->type === 'article') {
                $this->handleArticleUpload($lesson, $request);
            } elseif ($request->type === 'file') {
                $this->handleFilesUpload($lesson, $request);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('courses.lesson_updated_successfully'),
                'lesson' => $lesson->load('resources')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => __('courses.error_updating_lesson')
            ], 500);
        }
    }

    /**
     * Delete a lesson
     */
    public function deleteLesson(Lesson $lesson)
    {
        try {
            $this->deleteLessonFiles($lesson);
            $lesson->delete();

            return response()->json([
                'success' => true,
                'message' => __('courses.lesson_deleted_successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('courses.error_deleting_lesson')
            ], 500);
        }
    }

    /**
     * Get lesson data for editing
     */
    public function showLesson(Lesson $lesson)
    {
        try {
            $lessonData = [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'description' => $lesson->description,
                'content_type' => $lesson->content_type,
            ];

            // Add type-specific data
            if ($lesson->content_type === 'video') {
                $lessonData['video_url'] = $lesson->video_file
                    ? asset('storage/lessons/videos/' . $lesson->video_file)
                    : null;
                $lessonData['duration'] = $lesson->duration;
            } elseif ($lesson->content_type === 'article') {
                $lessonData['image_url'] = $lesson->thumbnail
                    ? asset('storage/lessons/articles/' . $lesson->thumbnail)
                    : null;
                $lessonData['content'] = $lesson->article_content;
            } elseif ($lesson->content_type === 'download') {
                // Get downloadable status from first resource (all should have same value)
                $lessonData['downloadable'] = $lesson->resources->first()?->is_downloadable ?? true;
                $lessonData['resources'] = $lesson->resources->map(function ($resource) {
                    return [
                        'id' => $resource->id,
                        'filename' => $resource->original_name ?? $resource->file_name,
                        'url' => asset('storage/' . $resource->file_path),
                        'size' => $resource->file_size,
                    ];
                });
            }

            return response()->json([
                'success' => true,
                'lesson' => $lessonData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching lesson data'
            ], 500);
        }
    }

    // ==========================================
    // File Upload Handlers
    // ==========================================

    /**
     * Handle video upload
     */
    private function handleVideoUpload(Lesson $lesson, Request $request)
    {
        if ($request->hasFile('video')) {
            // Delete old video if exists
            if ($lesson->video_file) {
                Storage::disk('public')->delete('lessons/videos/' . $lesson->video_file);
            }

            $video = $request->file('video');
            $fileName = time() . '_' . Str::random(10) . '.' . $video->getClientOriginalExtension();
            $video->storeAs('lessons/videos', $fileName, 'public');

            // Get video duration (requires FFmpeg)
            $duration = $this->getVideoDuration($video);

            $lesson->update([
                'video_file' => $fileName,
                'duration' => $duration,
            ]);
        }
    }

    /**
     * Handle article upload
     */
    private function handleArticleUpload(Lesson $lesson, Request $request)
    {
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($lesson->thumbnail) {
                Storage::disk('public')->delete('lessons/articles/' . $lesson->thumbnail);
            }

            $image = $request->file('image');
            $fileName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('lessons/articles', $fileName, 'public');

            $lesson->update([
                'thumbnail' => $fileName,
            ]);
        }

        if ($request->has('content')) {
            $lesson->update([
                'article_content' => $request->content,
            ]);
        }
    }

    /**
     * Handle multiple files upload
     */
    private function handleFilesUpload(Lesson $lesson, Request $request)
    {
        if ($request->hasFile('files')) {
            $downloadable = $request->input('downloadable', true);

            foreach ($request->file('files') as $file) {
                $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('lessons/resources', $fileName, 'private');

                LessonResource::create([
                    'lesson_id' => $lesson->id,
                    'file_name' => $fileName,
                    'original_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'resource_type' => $this->getResourceType($file->getClientOriginalExtension()),
                    'is_downloadable' => $downloadable,
                ]);
            }
        }
    }

    // ==========================================
    // Helper Methods
    // ==========================================

    /**
     * Get video duration
     */
    private function getVideoDuration($videoFile)
    {
        try {
            // This requires FFmpeg - install: composer require pbmedia/laravel-ffmpeg
            // For now, return null - implement later if FFmpeg is available
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get resource type from extension
     */
    private function getResourceType($extension)
    {
        $types = [
            'pdf' => 'document',
            'doc' => 'document',
            'docx' => 'document',
            'ppt' => 'presentation',
            'pptx' => 'presentation',
            'xls' => 'spreadsheet',
            'xlsx' => 'spreadsheet',
            'zip' => 'archive',
            'rar' => 'archive',
            'jpg' => 'image',
            'jpeg' => 'image',
            'png' => 'image',
            'gif' => 'image',
        ];

        return $types[strtolower($extension)] ?? 'other';
    }

    /**
     * Delete lesson files
     */
    private function deleteLessonFiles(Lesson $lesson)
    {
        // Delete video
        if ($lesson->video_file) {
            Storage::disk('public')->delete('lessons/videos/' . $lesson->video_file);
        }

        // Delete article image
        if ($lesson->thumbnail) {
            Storage::disk('public')->delete('lessons/articles/' . $lesson->thumbnail);
        }

        // Delete resources
        foreach ($lesson->resources as $resource) {
            Storage::disk('private')->delete($resource->file_path);
            $resource->delete();
        }
    }
}
