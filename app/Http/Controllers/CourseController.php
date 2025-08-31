<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    /**
     * Display courses listing
     */
    public function index(Request $request)
    {
        $query = Course::with(['instructor', 'category'])
            ->published();

        // Search functionality
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Level filter
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Price filter
        if ($request->filled('price_range')) {
            switch ($request->price_range) {
                case 'free':
                    $query->where('price', 0);
                    break;
                case 'paid':
                    $query->where('price', '>', 0);
                    break;
                case 'under_50':
                    $query->where('price', '<=', 50);
                    break;
                case 'under_100':
                    $query->where('price', '<=', 100);
                    break;
            }
        }

        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');

        switch ($sortBy) {
            case 'price':
                $query->orderBy('price', $sortOrder);
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')
                    ->orderBy('reviews_avg_rating', $sortOrder);
                break;
            case 'students':
                $query->withCount('enrollments')
                    ->orderBy('enrollments_count', $sortOrder);
                break;
            default:
                $query->orderBy('created_at', $sortOrder);
        }

        $vars = [
            'courses' => $query->paginate(12),
            'courses_categories' => Category::parents()->get(),
        ];



        return view('courses.index', $vars);
    }

    /**
     * Display course details
     */
    public function show(Course $course)
    {
        $course->load([
            'instructor',
            'category',
            'lessons' => function ($query) {
                $query->published()->orderBy('sort_order');
            },
            'reviews' => function ($query) {
                $query->approved()->with('user')->latest();
            }
        ]);

        $isEnrolled = false;
        $enrollment = null;

        if (auth()->check()) {
            $enrollment = Enrollment::where('user_id', auth()->id())
                ->where('course_id', $course->id)
                ->first();
            $isEnrolled = !is_null($enrollment);
        }

        $relatedCourses = Course::where('category_id', $course->category_id)
            ->where('id', '!=', $course->id)
            ->published()
            ->limit(4)
            ->get();

        return view('courses.show', compact('course', 'isEnrolled', 'enrollment', 'relatedCourses'));
    }

    /**
     * Enroll in course
     */
    public function enroll(Course $course)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Check if already enrolled
        $existingEnrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existingEnrollment) {
            return redirect()->route('courses.show', $course->slug)
                ->with('info', 'You are already enrolled in this course.');
        }

        // Create enrollment
        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
        ]);

        return redirect()->route('courses.learn', $course->slug)
            ->with('success', 'Successfully enrolled in the course!');
    }

    /**
     * Course learning interface
     */
    public function learn(Course $course)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $enrollment = Enrollment::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'You need to enroll in this course first.');
        }

        $course->load([
            'lessons' => function ($query) {
                $query->published()->orderBy('sort_order');
            }
        ]);

        $currentLesson = $course->lessons->first();

        return view('courses.learn', compact('course', 'enrollment', 'currentLesson'));
    }

    /**
     * Search courses
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        if (empty($query)) {
            return redirect()->route('courses.index');
        }

        $courses = Course::with(['instructor', 'category'])
            ->published()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                    ->orWhere('description', 'like', '%' . $query . '%')
                    ->orWhere('short_description', 'like', '%' . $query . '%');
            })
            ->paginate(12);

        return view('courses.search', compact('courses', 'query'));
    }
}
