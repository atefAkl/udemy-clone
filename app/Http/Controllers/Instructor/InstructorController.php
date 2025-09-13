<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCourseRequest;
use App\Models\Course;
use App\Models\Category;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InstructorController extends Controller
{
    use AuthorizesRequests;

    /**
     * Instructor dashboard
     */
    public function dashboard()
    {
        $instructor = Auth::user();

        $stats = [
            'total_courses' => $instructor->courses()->count(),
            'published_courses' => $instructor->courses()->published()->count(),
            'total_students' => $instructor->courses()->withCount('enrollments')->get()->sum('enrollments_count'),
            'total_revenue' => 0, // Will be calculated later with payment system
        ];

        $recentCourses = $instructor->courses()
            ->with(['category', 'enrollments'])
            ->latest()
            ->limit(5)
            ->get();

        $courses_categories = Category::parents()->get();

        return view('instructor.dashboard', compact('stats', 'recentCourses', 'courses_categories'));
    }

    /**
     * List instructor courses
     */
    public function courses()
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
    public function createCourse()
    {
        $categories = Category::where('is_active', true)->get();
        return view('instructor.courses.create', compact('categories'));
    }

    /**
     * Show create course form (Wide Layout)
     */
    public function createCourseWide()
    {
        $categories = Category::where('is_active', true)->get();
        return view('instructor.courses.create-wide', compact('categories'));
    }

    /**
     * Store new course
     */
    public function storeCourse(CreateCourseRequest $request)
    {
        //return $request->all();
        $course = new Course();
        $course->title = $request->title;
        $course->slug = Str::slug($request->title);
        $course->short_description = $request->short_description;
        $course->description = $request->description;
        $course->category_id = $request->category_id;
        $course->language = $request->language;
        $course->target_level = $request->target_level;
        $course->price = $request->price;
        $course->instructor_id = Auth::id();
        $course->launch_date = $request->launch_date;
        $course->launch_time = $request->launch_time;
        //$course->requirements = $request->requirements ?? 'No requirements';
        //$course->objectives = $request->objectives ?? 'No objectives';
        $course->has_certificate = $request->boolean('has_certificate') ?? false;
        $course->access_duration_type = $request->access_duration_type ?? 'unlimited';
        $course->access_duration_value = $request->access_duration_type === 'limited' ? $request->access_duration_value : null;
        $course->status = Course::STATUS_DRAFT;

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('courses/thumbnails', 'public');
            $course->thumbnail = basename($thumbnailPath);
        }

        // Handle preview video upload
        if ($request->hasFile('preview_video')) {
            $videoPath = $request->file('preview_video')->store('courses/videos', 'public');
            $course->preview_video = basename($videoPath);
        }

        $course->save();

        return redirect()->route('instructor.courses.show', $course->id)
            ->with('success', 'Course created successfully!');
    }

    /**
     * Show course details
     */
    public function showCourse(Course $course)
    {
        $this->authorize('update', $course);

        $course->load(['lessons' => function ($query) {
            $query->orderBy('sort_order');
        }, 'category']);

        return view('instructor.courses.show', compact('course'));
    }

    /**
     * Show edit course form
     */
    public function editCourse(Course $course)
    {
        $this->authorize('update', $course);

        $categories = Category::where('is_active', true)->get();
        return view('instructor.courses.edit', compact('course', 'categories'));
    }

    /**
     * Update course
     */
    public function updateCourse(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:120',
            'short_description' => 'required|string|max:160',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'level' => 'required|in:beginner,intermediate,advanced,all_levels',
            'language' => 'required|string|in:ar,en',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|numeric|min:0.5',
            'access_duration_value' => 'required|integer|min:1',
            'access_duration_unit' => 'required|in:days,weeks,months,years',
            'launch_date' => 'nullable|date',
            'launch_time' => 'nullable|date_format:H:i',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
            'preview_video' => 'nullable|mimes:mp4,mov,avi|max:102400', // 100MB max
            'requirements' => 'nullable|array',
            'requirements.*' => 'string|max:255',
            'what_you_learn' => 'nullable|array',
            'what_you_learn.*' => 'string|max:255',
            'has_certificate' => 'boolean',
            'remove_thumbnail' => 'boolean',
            'remove_preview_video' => 'boolean'
        ]);

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

    /**
     * List instructor categories
     */
    public function categories()
    {
        $instructor = Auth::user();

        $categories = Category::with(['parent', 'children'])
            ->withCount('courses')
            ->latest()
            ->paginate(15);

        $stats = [
            'total_categories' => Category::count(),
            'active_categories' => Category::where('status', 'active')->count(),
            'parent_categories' => Category::whereNull('parent_id')->count(),
            'subcategories' => Category::whereNotNull('parent_id')->count(),
        ];

        return view('instructor.categories.index', compact('categories', 'stats'));
    }

    /**
     * Show create category form
     */
    public function createCategory()
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $recentCategories = Category::latest()
            ->limit(5)
            ->get();

        $stats = [
            'total_categories' => Category::count(),
            'parent_categories' => Category::whereNull('parent_id')->count(),
            'subcategories' => Category::whereNotNull('parent_id')->count(),
        ];

        return view('instructor.categories.create', compact('parentCategories', 'recentCategories', 'stats'));
    }

    /**
     * Store new category
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:active,inactive',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:7',
            'sort_order' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = $request->slug ?: Str::slug($request->name);
        $category->description = $request->description;
        $category->parent_id = $request->parent_id;
        $category->status = $request->status;
        $category->icon = $request->icon;
        $category->color = $request->color ?? '#007bff';
        $category->sort_order = $request->sort_order ?? 0;
        $category->is_featured = $request->boolean('is_featured');
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;

        $category->save();

        return redirect()->route('instructor.categories.index')
            ->with('success', __('app.category_created'));
    }

    /**
     * Show category details
     */
    public function showCategory(Category $category)
    {
        $category->load(['parent', 'children', 'courses' => function ($query) {
            $query->where('instructor_id', Auth::id())->latest();
        }]);

        $stats = [
            'total_courses' => $category->courses()->count(),
            'published_courses' => $category->courses()->where('status', 'published')->count(),
            'draft_courses' => $category->courses()->where('status', 'draft')->count(),
        ];

        return view('instructor.categories.show', compact('category', 'stats'));
    }

    /**
     * Show edit category form
     */
    public function editCategory(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('status', 'active')
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('instructor.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update category
     */
    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'status' => 'required|in:active,inactive',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:7',
            'sort_order' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        // Prevent category from being its own parent
        if ($request->parent_id == $category->id) {
            return back()->withErrors(['parent_id' => 'Category cannot be its own parent.']);
        }

        $category->name = $request->name;
        $category->slug = $request->slug ?: Str::slug($request->name);
        $category->description = $request->description;
        $category->parent_id = $request->parent_id;
        $category->status = $request->status;
        $category->icon = $request->icon;
        $category->color = $request->color ?? '#007bff';
        $category->sort_order = $request->sort_order ?? 0;
        $category->is_featured = $request->boolean('is_featured');
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;

        $category->save();

        return redirect()->route('instructor.categories.index')
            ->with('success', __('app.category_updated'));
    }

    /**
     * Delete category
     */
    public function deleteCategory(Category $category)
    {
        // Check if category has courses
        if ($category->courses()->count() > 0) {
            return back()->with('error', 'Cannot delete category with existing courses.');
        }

        // Check if category has subcategories
        if ($category->children()->count() > 0) {
            return back()->with('error', 'Cannot delete category with subcategories.');
        }

        $category->delete();

        return redirect()->route('instructor.categories.index')
            ->with('success', __('app.category_deleted'));
    }
}
