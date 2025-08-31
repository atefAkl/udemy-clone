<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
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
     * Store new course
     */
    public function storeCourse(Request $request)
    {
        //return $request->all();
        $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'level' => 'required|in:beginner,intermediate,advanced',
            //'language' => 'required|string|max:10',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'requirements' => 'nullable|array',
            'what_you_learn' => 'nullable|array',
        ]);

        // default value for language
        $request->language = $request->language ?? 'en';

        $course = new Course();
        $course->title = $request->title;
        $course->slug = Str::slug($request->title);
        $course->short_description = $request->short_description;
        $course->description = $request->description;
        $course->category_id = $request->category_id;
        $course->level = $request->level;
        $course->language = $request->language;
        $course->price = $request->price;
        $course->discount_price = $request->discount_price;
        $course->instructor_id = Auth::id();
        $course->requirements = $request->requirements ?? [];
        $course->what_you_learn = $request->what_you_learn ?? [];

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('courses', 'public');
            $course->thumbnail = basename($thumbnailPath);
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

        $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'level' => 'required|in:beginner,intermediate,advanced',
            'language' => 'required|string|max:10',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'requirements' => 'nullable|array',
            'what_you_learn' => 'nullable|array',
        ]);

        $course->title = $request->title;
        $course->slug = Str::slug($request->title);
        $course->short_description = $request->short_description;
        $course->description = $request->description;
        $course->category_id = $request->category_id;
        $course->level = $request->level;
        $course->language = $request->language;
        $course->price = $request->price;
        $course->discount_price = $request->discount_price;
        $course->requirements = $request->requirements ?? [];
        $course->what_you_learn = $request->what_you_learn ?? [];

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($course->thumbnail) {
                Storage::disk('public')->delete('courses/' . $course->thumbnail);
            }

            $thumbnailPath = $request->file('thumbnail')->store('courses', 'public');
            $course->thumbnail = basename($thumbnailPath);
        }

        $course->save();

        return redirect()->route('instructor.courses.show', $course->id)
            ->with('success', 'Course updated successfully!');
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
