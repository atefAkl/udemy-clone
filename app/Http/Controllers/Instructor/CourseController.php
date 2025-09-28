<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CreateCourseRequest;
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

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('courses/thumbnails', 'public');
            $course->thumbnail = basename($thumbnailPath);
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
        $this->authorize('update', $course);

        $isPatch = $request->isMethod('patch') || $request->expectsJson();

        // Validation rules
        $rules = [
            'title' => ($isPatch ? 'sometimes' : 'required') . '|string|max:255',
            'subtitle' => 'sometimes|nullable|string|max:120',
            'short_description' => ($isPatch ? 'sometimes' : 'required') . '|string|max:160',
            'description' => ($isPatch ? 'sometimes' : 'required') . '|string',
            'category_id' => ($isPatch ? 'sometimes' : 'required') . '|exists:categories,id',
            'level' => ($isPatch ? 'sometimes' : 'required') . '|in:beginner,intermediate,advanced,all_levels',
            'language' => ($isPatch ? 'sometimes' : 'required') . '|string|in:ar,en',
            'price' => ($isPatch ? 'sometimes' : 'required') . '|numeric|min:0',
            'duration' => ($isPatch ? 'sometimes' : 'required') . '|numeric|min:0.5',
            'access_duration_value' => ($isPatch ? 'sometimes' : 'required') . '|integer|min:1',
            'access_duration_unit' => ($isPatch ? 'sometimes' : 'required') . '|in:days,weeks,months,years',
            'launch_date' => 'sometimes|nullable|date',
            'launch_time' => 'sometimes|nullable|date_format:H:i',
            'thumbnail' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
            'preview_video' => 'sometimes|nullable|mimes:mp4,mov,avi|max:102400', // 100MB max
            'requirements' => 'sometimes|nullable|array',
            'requirements.*' => 'string|max:255',
            'what_you_learn' => 'sometimes|nullable|array',
            'what_you_learn.*' => 'string|max:255',
            'has_certificate' => 'sometimes|boolean',
            'remove_thumbnail' => 'sometimes|boolean',
            'remove_preview_video' => 'sometimes|boolean',
        ];

        $validated = $request->validate($rules);

        // If PATCH/JSON: update only provided fields
        if ($isPatch) {
            $updates = [];

            if ($request->has('title')) {
                $updates['title'] = $validated['title'];
                $updates['slug'] = Str::slug($validated['title']);
            }
            foreach (
                [
                    'subtitle',
                    'short_description',
                    'description',
                    'category_id',
                    'level',
                    'language',
                    'price',
                    'duration',
                    'access_duration_value',
                    'access_duration_unit',
                    'launch_date',
                    'launch_time',
                    'requirements',
                    'what_you_learn'
                ] as $field
            ) {
                if ($request->has($field)) {
                    $updates[$field] = $validated[$field] ?? ($request->input($field) ?? null);
                }
            }
            if ($request->has('has_certificate')) {
                $updates['has_certificate'] = (bool) $validated['has_certificate'];
            }

            // Derive access_duration_type if access_duration_value provided
            if (array_key_exists('access_duration_value', $updates)) {
                $value = (int) $updates['access_duration_value'];
                $updates['access_duration_type'] = $value > 0 ? 'limited' : 'unlimited';
            }

            if (!empty($updates)) {
                $course->fill($updates);
            }

            // No file operations in autosave (frontend excludes files)
            $course->save();

            return response()->json([
                'success' => true,
                'course_id' => $course->id,
                'updated' => array_keys($updates),
            ]);
        }

        // PUT: full update flow
        // Update basic course info
        $course->fill([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'slug' => Str::slug($validated['title']),
            'short_description' => $validated['short_description'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'level' => $validated['level'],
            'language' => $validated['language'],
            'price' => $validated['price'],
            'duration' => $validated['duration'],
            'access_duration_type' => $validated['access_duration_value'] > 0 ? 'limited' : 'unlimited',
            'access_duration_value' => $validated['access_duration_value'],
            'access_duration_unit' => $validated['access_duration_unit'],
            'launch_date' => $validated['launch_date'] ?? null,
            'launch_time' => $validated['launch_time'] ?? null,
            'requirements' => $validated['requirements'] ?? [],
            'what_you_learn' => $validated['what_you_learn'] ?? [],
            'has_certificate' => $validated['has_certificate'] ?? false,
        ]);

        // Handle thumbnail upload/removal
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($course->thumbnail) {
                Storage::disk('public')->delete('courses/thumbnails/' . $course->thumbnail);
            }
            $thumbnailPath = $request->file('thumbnail')->store('courses/thumbnails', 'public');
            $course->thumbnail = basename($thumbnailPath);
        } elseif ($request->boolean('remove_thumbnail') && $course->thumbnail) {
            // Remove thumbnail if requested
            Storage::disk('public')->delete('courses/thumbnails/' . $course->thumbnail);
            $course->thumbnail = null;
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

        return redirect()->route('instructor.courses.show', $course->id)
            ->with('success', __('Course updated successfully!'));
    }

    /**
     * Delete course
     */
    public function deleteCourse(Course $course)
    {
        $this->authorize('delete', $course);

        // Delete thumbnail
        if ($course->thumbnail) {
            Storage::disk('public')->delete('courses/' . $course->thumbnail);
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
