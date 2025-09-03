<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories with tree structure
     */
    public function index(Request $request)
    {
        $query = Category::with(['children', 'courses']);

        // Search functionality
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        // Get root categories (parent_id is null)
        $categories = $query->whereNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Get statistics
        $stats = [
            'total_categories' => Category::count(),
            'active_categories' => Category::where('is_active', true)->count(),
            'inactive_categories' => Category::where('is_active', false)->count(),
            'root_categories' => Category::whereNull('parent_id')->count(),
            'subcategories' => Category::whereNotNull('parent_id')->count(),
        ];

        return view('admin.categories.index', compact('categories', 'stats'));
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:100',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', __('categories.validation_failed'));
        }

        // Get the next sort order
        $sortOrder = Category::where('parent_id', $request->parent_id)->max('sort_order') + 1;

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'icon' => $request->icon ?: 'fa fa-folder',
            'parent_id' => $request->parent_id,
            'sort_order' => $sortOrder,
            'is_active' => true,
        ]);

        $message = $request->parent_id
            ? __('categories.subcategory_created_successfully')
            : __('categories.root_category_created_successfully');

        return redirect()->route('admin.categories.index')
            ->with('success', $message);
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:1000',
            'icon' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', __('categories.validation_failed'));
        }

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'icon' => $request->icon ?: $category->icon,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', __('categories.category_updated_successfully'));
    }

    /**
     * Remove the specified category
     */
    public function destroy(Category $category)
    {
        // Check if category has courses
        if ($category->courses()->count() > 0) {
            return redirect()->back()
                ->with('error', __('categories.cannot_delete_category_with_courses'));
        }

        // Check if category has subcategories
        if ($category->children()->count() > 0) {
            return redirect()->back()
                ->with('error', __('categories.cannot_delete_category_with_subcategories'));
        }

        $categoryName = $category->name;
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', __('categories.category_deleted_successfully', ['name' => $categoryName]));
    }

    /**
     * Show courses for a specific category
     */
    public function courses(Category $category)
    {
        $courses = $category->courses()
            ->with(['instructor', 'category'])
            ->paginate(12);

        return view('admin.categories.courses', compact('category', 'courses'));
    }

    /**
     * Toggle category status
     */
    public function toggleStatus(Category $category)
    {
        $category->update([
            'is_active' => !$category->is_active
        ]);

        $status = $category->is_active ? __('categories.activated') : __('categories.deactivated');

        return redirect()->back()
            ->with('success', __('categories.category_status_updated', ['status' => $status]));
    }

    /**
     * Update categories sort order
     */
    public function updateOrder(Request $request)
    {
        $categories = $request->categories;

        foreach ($categories as $index => $categoryId) {
            Category::where('id', $categoryId)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
