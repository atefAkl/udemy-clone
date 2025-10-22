<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Courses\VideoLessonRequest;
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
