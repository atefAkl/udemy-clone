<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Courses\VideoLessonRequest;
use App\Http\Requests\Courses\ArticleLessonRequest;
use App\Http\Requests\Courses\AssetsLessonRequest;
use App\Models\Section;
use App\Models\Lesson;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    //

    public function StoreVideo(VideoLessonRequest $request, Section $section)
    {
        try {
            $validated = $request->validated();

            // Prepare lesson data
            $lessonData = [
                'section_id'        => $section->id,
                'title'             => $validated['title'],
                'course_id'         => $section->course_id,
                'description'       => $validated['description'] ?? null,
                'lesson_type'       => 'video',
                'video_source'      => $validated['video_source'],
                'sort_order'        => $section->lessons()->max('sort_order') + 1,
            ];

            // Handle video upload or URL
            if ($validated['video_source'] === 'upload' && $request->hasFile('video_file')) {
                // Upload file and save path
                $file = $request->file('video_file');
                $path = $file->store('lessons/videos', 'public');
                $lessonData['video_file'] = $path;
                $lessonData['video_url'] = null;
            } elseif ($validated['video_source'] === 'url' && !empty($validated['video_url'])) {
                // Save video URL
                $lessonData['video_url'] = $validated['video_url'];
                $lessonData['video_file'] = null;
            }

            // Create lesson
            $lesson = Lesson::create($lessonData);

            return redirect()->back()->with('success', 'Video lesson created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lesson creation failed: ' . $e->getMessage());
        }
    }

    public function storeArticle(ArticleLessonRequest $request, Section $section)
    {
        try {
            $validated = $request->validated();
            
            // Auto-extract description from article body if not provided
            $description = $validated['description'] ?? null;
            if (empty($description)) {
                // Strip HTML tags and limit to 150 characters
                $plainText = strip_tags($validated['article_body']);
                $description = \Illuminate\Support\Str::limit($plainText, 150, '...');
            }
            
            // Prepare lesson data
            $lessonData = [
                'section_id' => $section->id,
                'course_id' => $section->course_id,
                'title' => $validated['title'],
                'description' => $description,
                'lesson_type' => 'article',
                'article_body' => $validated['article_body'],
                'poster_source' => $validated['poster_source'],
                'sort_order' => $section->lessons()->max('sort_order') + 1,
            ];

            // Handle poster upload or URL
            if ($validated['poster_source'] === 'upload' && $request->hasFile('poster_file')) {
                // Upload poster image and save path
                $file = $request->file('poster_file');
                $path = $file->store('lessons/posters', 'public');
                $lessonData['poster_file'] = $path;
                $lessonData['poster_url'] = null;
            } elseif ($validated['poster_source'] === 'url' && !empty($validated['poster_url'])) {
                // Save poster URL
                $lessonData['poster_url'] = $validated['poster_url'];
                $lessonData['poster_file'] = null;
            }

            // Create lesson
            $lesson = Lesson::create($lessonData);

            return redirect()->back()->with('success', 'Article lesson created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Article creation failed: ' . $e->getMessage());
        }
    }

    public function storeAssets(AssetsLessonRequest $request, Section $section)
    {
        try {
            $validated = $request->validated();
            
            // Prepare lesson data
            $lessonData = [
                'section_id' => $section->id,
                'course_id' => $section->course_id,
                'title' => $validated['title'],
                'description' => $validated['description'] ?? 'Downloadable resources for this lesson',
                'lesson_type' => 'assets',
                'sort_order' => $section->lessons()->max('sort_order') + 1,
            ];

            // Create lesson (without files yet)
            $lesson = Lesson::create($lessonData);

            // Handle multiple file uploads via Queue
            if ($request->hasFile('assets_files')) {
                $filesData = [];
                
                // Store files temporarily
                foreach ($request->file('assets_files') as $file) {
                    $tempPath = $file->store('temp/assets', 'public');
                    
                    $filesData[] = [
                        'original_name' => $file->getClientOriginalName(),
                        'temp_path' => $tempPath,
                        'size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                    ];
                }
                
                // Generate unique upload key for progress tracking
                $uploadKey = 'assets_upload_' . $lesson->id . '_' . uniqid();
                
                // Dispatch job to queue
                \App\Jobs\ProcessAssetsUpload::dispatch($lesson->id, $filesData, $uploadKey);
                
                // Store upload key in session for progress tracking
                session(['upload_key' => $uploadKey]);
                
                return redirect()->back()->with([
                    'success' => 'Assets lesson created! Files are being uploaded in the background.',
                    'upload_key' => $uploadKey,
                    'lesson_id' => $lesson->id,
                    'files_count' => count($filesData),
                    'show_progress' => true
                ]);
            }

            return redirect()->back()->with('success', 'Assets lesson created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Assets lesson creation failed: ' . $e->getMessage());
        }
    }

    public function checkUploadProgress($uploadKey)
    {
        $progress = \Illuminate\Support\Facades\Cache::get($uploadKey);
        
        if (!$progress) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'Upload progress not found'
            ], 404);
        }
        
        return response()->json($progress);
    }

    public function destroy(Lesson $lesson)
    {
        try {
            DB::transaction(function () use ($lesson) {
                // Get lessons with higher sort_order from the SAME SECTION
                $affectedLessons = $lesson->section->lessons()
                    ->where('sort_order', '>', $lesson->sort_order)
                    ->get();

                // Delete video file if exists
                if ($lesson->video_source === 'upload' && $lesson->video_file) {
                    Storage::disk('public')->delete($lesson->video_file);
                }

                // Delete poster file if exists
                if ($lesson->poster_source === 'upload' && $lesson->poster_file) {
                    Storage::disk('public')->delete($lesson->poster_file);
                }

                // Delete assets files if exists
                if ($lesson->lesson_type === 'assets' && $lesson->lecture_file) {
                    $assetsFiles = json_decode($lesson->lecture_file, true);
                    if (is_array($assetsFiles)) {
                        foreach ($assetsFiles as $file) {
                            if (isset($file['path'])) {
                                Storage::disk('public')->delete($file['path']);
                            }
                        }
                    }
                }

                // Delete the lesson
                $lesson->delete();

                // Decrement sort_order for lessons that came after this one
                foreach ($affectedLessons as $affectedLesson) {
                    $affectedLesson->decrement('sort_order');
                }
            });

            return redirect()->back()->with('success', 'Lesson deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lesson deletion failed: ' . $e->getMessage());
        }
    }
}
