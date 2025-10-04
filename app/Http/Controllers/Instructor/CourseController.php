<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CreateCourseRequest;
use App\Http\Requests\UpdateCourseGeneralInfoRequest;
use App\Models\Course;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    use AuthorizesRequests;
    //

    /**
     ** List instructor courses
     */
    public function index()
    {
        $courses = User::find(Auth::id())->courses()
            ->with(['category', 'enrollments'])
            ->withCount(['enrollments', 'lessons'])
            ->latest()
            ->paginate(10);

        // Add average rating to each course
        foreach ($courses as $course) {
            $course->average_rating = $course->reviews()->avg('rating') ?? 0;
        }

        $courses_categories = Category::parents()->get();
        return view('instructor.courses.index', compact('courses', 'courses_categories'));
    }

    /**
     * Show create course form
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('instructor.courses.create', compact('categories'));
    }

    /**
     * Store new course
     */
    public function store(CreateCourseRequest $request)
    {
        $validated = $request->validated();

        $course = Course::create([
            'title'                  => $validated['title'],
            'slug'                   => Str::slug($validated['title']),
            'short_description'      => $validated['short_description'],
            'description'            => $validated['description'],
            'category_id'            => $validated['category_id'],
            'language'               => $validated['language'],
            'target_level'           => $validated['target_level'],
            'price'                  => $validated['price'],
            'instructor_id'          => Auth::id(),
            'launch_date'            => $validated['launch_date'],
            'launch_time'            => $validated['launch_time'],

            'has_certificate'        => $validated['has_certificate'] ?? false,
            'access_duration_type'   => $validated['access_duration_type'] ?? 'unlimited',
            'access_duration_value'  => $validated['access_duration_type'] === 'limited' ? $validated['access_duration_value'] : null,
            'status'                 => Course::STATUS_DRAFT,
        ]);

        // Handle banner upload
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('courses/banners', 'public');
            $course->banner = basename($bannerPath);
        }



        // Handle promo video upload
        if ($request->hasFile('promo_video')) {
            $videoPath = $request->file('promo_video')->store('courses/promo_videos', 'public');
            $course->promo_video = basename($videoPath);
        }

        $course->save();

        return redirect()->route('instructor.courses.show', $course->id)
            ->with('success', 'Course created successfully!');
    }
    /**
     * Update course general information
     * @param Request $request
     * @param Course $course
     * @return RedirectResponse
     */

    public function updateGeneralInfo(UpdateCourseGeneralInfoRequest $request, Course $course)
    {
        $validated = $request->validated();

        // Update basic fields
        $course->update([
            'title'             => $validated['title'],
            'subtitle'          => $validated['subtitle'],
            'slug'              => Str::slug($validated['title']),
            'short_description' => $validated['short_description'],
            'description'       => $validated['description'],
            'category_id'       => $validated['category'], // Note: field name is 'category' in request
            'level'             => $validated['level'],
            'language'          => $validated['language'],
            'price'             => $validated['price'],
        ]);

        // Handle Banner
        if ($request->input('banner_source') === 'upload' && $request->hasFile('banner')) {
            if ($course->banner) {
                Storage::disk('public')->delete('courses/banners/' . $course->banner);
            }
            $bannerPath = $request->file('banner')->store('courses/banners', 'public');
            $course->banner = basename($bannerPath);
            $course->banner_source = 'upload';
            $course->banner_url = null;
        } elseif ($request->input('banner_source') === 'link' && !empty($validated['banner_url'])) {
            if ($course->banner) {
                Storage::disk('public')->delete('courses/banners/' . $course->banner);
            }
            $course->banner = null;
            $course->banner_source = 'link';
            $course->banner_url = $validated['banner_url'];
        }

        // Handle Promo Video
        if ($request->input('video_source') === 'upload' && $request->hasFile('promo_video')) {
            if ($course->promo_video) {
                Storage::disk('public')->delete('courses/promo_videos/' . $course->promo_video);
            }
            $videoPath = $request->file('promo_video')->store('courses/promo_videos', 'public');
            $course->promo_video = basename($videoPath);
            $course->video_source = 'upload';
            $course->video_url = null;
        } elseif ($request->input('video_source') === 'link' && !empty($validated['video_url'])) {
            if ($course->promo_video) {
                Storage::disk('public')->delete('courses/promo_videos/' . $course->promo_video);
            }
            $course->promo_video = null;
            $course->video_source = 'link';
            $course->video_url = $validated['video_url'];
        }

        $course->save();

        return redirect()->back()->with('success', __('courses.general_info_updated'));
    }

    /**
     ** Show course details
     */
    public function show(Course $course)
    {
        $this->authorize('update', $course);

        $course->load(['lessons' => function ($query) {
            $query->orderBy('sort_order');
        }, 'category']);

        return view('instructor.courses.show', compact('course'));
    }

    /**
     ** Show edit course form
     */
    public function edit(Course $course)
    {
        $this->authorize('update', $course);

        $categories = Category::where('is_active', true)->get();
        return view('instructor.courses.edit', compact('course', 'categories'));
    }

    /**
     * Update course
     */
    public function update(Request $request, Course $course)
    {


        // Handle banner upload/removal
        if ($request->hasFile('banner')) {
            // Delete old banner if exists
            if ($course->banner) {
                Storage::disk('public')->delete('courses/banners/' . $course->banner);
            }
            $bannerPath = $request->file('banner')->store('courses/banners', 'public');
            $course->banner = basename($bannerPath);
        } elseif ($request->boolean('remove_banner') && $course->banner) {
            // Remove banner if requested
            Storage::disk('public')->delete('courses/banners/' . $course->banner);
            $course->banner = null;
        }

        // Handle preview video upload/removal
        if ($request->hasFile('preview_video')) {
            // Delete old video if exists
            if ($course->preview_video) {
                Storage::disk('public')->delete('courses/videos/' . $course->preview_video);
            }
            $videoPath = $request->file('preview_video')->store('courses/videos', 'public');
            $course->preview_video = basename($videoPath);
        } elseif ($request->boolean('remove_preview_video') && $course->preview_video) {
            // Remove video if requested
            Storage::disk('public')->delete('courses/videos/' . $course->preview_video);
            $course->preview_video = null;
        }

        $course->save();

        return redirect()->back()
            ->with('success', __('Course updated successfully!'));
    }

    /**
     * Delete course
     */
    public function deleteCourse(Course $course)
    {
        $this->authorize('delete', $course);

        // Delete banner
        if ($course->banner) {
            Storage::disk('public')->delete('courses/' . $course->banner);
        }

        $course->delete();

        return redirect()->route('instructor.courses.index')
            ->with('success', 'Course deleted successfully!');
    }

    /**
     * Publish course
     */
    public function publishCourse(Course $course)
    {
        $this->authorize('update', $course);

        // Check if course has lessons
        if ($course->lessons()->count() === 0) {
            return back()->with('error', 'Cannot publish course without lessons.');
        }

        $course->update(['status' => Course::STATUS_PUBLISHED]);

        return back()->with('success', 'Course published successfully!');
    }
}
