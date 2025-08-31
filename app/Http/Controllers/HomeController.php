<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page
     */
    public function index()
    {
        // Featured courses
        $featuredCourses = Course::with(['instructor', 'category'])
            ->published()
            ->featured()
            ->limit(8)
            ->get();

        // Popular categories
        $categories = Category::withCount(['courses' => function ($query) {
            $query->published();
        }])
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('courses_count', 'desc')
            ->limit(8)
            ->get();

        // Latest courses
        $latestCourses = Course::with(['instructor', 'category'])
            ->published()
            ->latest()
            ->limit(6)
            ->get();

        // Statistics
        $stats = [
            'total_courses' => Course::published()->count(),
            'total_students' => \App\Models\Enrollment::distinct('user_id')->count(),
            'total_instructors' => \App\Models\User::where('role', 'instructor')->count(),
            'total_enrollments' => \App\Models\Enrollment::count(),
        ];

        return view('home', compact('featuredCourses', 'categories', 'latestCourses', 'stats'));
    }
}
