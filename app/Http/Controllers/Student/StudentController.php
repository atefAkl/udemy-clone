<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    //
    public function dashboard()
    {
        // Get student's enrolled courses count
        $enrolledCourses = []; // Replace with actual query
        
        // Get completed courses count
        $completedCourses = []; // Replace with actual query
        
        // Get courses in progress
        $inProgressCourses = []; // Replace with actual query
        
        // Get certificates count
        $certificatesCount = 0; // Replace with actual query
        
        // Get recent activities
        $recentActivities = []; // Replace with actual query
        
        return view('student.dashboard', [
            'enrolledCourses' => $enrolledCourses,
            'completedCourses' => $completedCourses,
            'inProgressCourses' => $inProgressCourses,
            'certificatesCount' => $certificatesCount,
            'recentActivities' => $recentActivities
        ]);
    }
}
