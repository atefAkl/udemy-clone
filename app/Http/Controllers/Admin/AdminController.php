<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Category;
use App\Models\Review;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = $this->getDashboardStats();

        // Additional specific stats for admin dashboard
        $recentUsers = User::latest()->take(5)->get();
        $recentCourses = Course::with('instructor')->latest()->take(5)->get();
        $recentReviews = Review::with(['user', 'course'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentCourses', 'recentReviews'));
    }

    // User Management
    public function users(Request $request)
    {
        $query = User::query();

        // Apply common filters using base controller method
        $this->applyFilters($query, $request, ['name', 'email']);

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Active status filter
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Status filter (deleted/active)
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNull('deleted_at');
            } elseif ($request->status === 'inactive') {
                $query->whereNotNull('deleted_at');
            }
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'inactive_users' => User::where('is_active', false)->count(),
            'instructors' => User::where('role', User::ROLE_INSTRUCTOR)->count(),
            'students' => User::where('role', User::ROLE_STUDENT)->count(),
            'admins' => User::where('role', User::ROLE_ADMIN)->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function getDashboardStats()
    {
        return [
            'users_count' => \App\Models\User::count(),
            'active_users' => \App\Models\User::where('is_active', true)->count(),
            'courses_count' => \App\Models\Course::count(),
            'published_courses' => \App\Models\Course::where('status', 'published')->count(),
            'categories_count' => \App\Models\Category::count(),
            'reviews_count' => \App\Models\Review::count(),
            'enrollments_count' => \App\Models\Enrollment::count(),
        ];
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:student,instructor,admin',
            'bio' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:20',
            'preferred_language' => 'required|in:ar,en',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'bio' => $request->bio,
            'phone' => $request->phone,
            'preferred_language' => $request->preferred_language,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', __('app.user_created_successfully'));
    }

    public function showUser(User $user)
    {
        $user->load(['enrollments.course', 'reviews', 'courses']);

        $stats = [
            'total_enrollments' => $user->enrollments->count(),
            'total_reviews' => $user->reviews->count(),
            'total_courses' => $user->courses->count(),
            'avg_rating' => $user->reviews->avg('rating'),
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:student,instructor,admin',
            'bio' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:20',
            'preferred_language' => 'required|in:ar,en',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'bio' => $request->bio,
            'phone' => $request->phone,
            'preferred_language' => $request->preferred_language,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', __('app.user_updated_successfully'));
    }

    public function deleteUser(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', __('app.user_deleted_successfully'));
    }

    public function restoreUser($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('admin.users.index')
            ->with('success', __('app.user_restored_successfully'));
    }

    // Instructors Management
    public function instructors(Request $request)
    {
        $query = User::where('role', 'instructor');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $instructors = $query->withCount(['courses', 'enrollments'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.instructors.index', compact('instructors'));
    }

    // Students Management
    public function students(Request $request)
    {
        $query = User::where('role', 'student');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $query->withCount(['enrollments', 'reviews'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.students.index', compact('students'));
    }

    // Course Management
    public function courses(Request $request)
    {
        $query = Course::with(['instructor', 'category']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $courses = $query->withCount(['enrollments', 'reviews'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $categories = Category::all();

        return view('admin.courses.index', compact('courses', 'categories'));
    }

    public function showCourse(Course $course)
    {
        $course->load(['instructor', 'category', 'enrollments.user', 'reviews.user', 'lessons']);

        $stats = [
            'total_enrollments' => $course->enrollments->count(),
            'total_reviews' => $course->reviews->count(),
            'total_lessons' => $course->lessons->count(),
            'avg_rating' => $course->reviews->avg('rating'),
        ];

        return view('admin.courses.show', compact('course', 'stats'));
    }

    public function updateCourseStatus(Request $request, Course $course)
    {
        $request->validate([
            'status' => 'required|in:draft,published,archived'
        ]);

        $course->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', __('app.course_status_updated'));
    }

    // Categories Management
    public function categories(Request $request)
    {
        $query = Category::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categories = $query->withCount('courses')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    // Reviews Management
    public function reviews(Request $request)
    {
        $query = Review::with(['user', 'course']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('course', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            })->orWhereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function deleteReview(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', __('app.review_deleted_successfully'));
    }
}
