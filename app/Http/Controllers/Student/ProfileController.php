<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Certificate;
use App\Models\Wishlist;
use App\Models\UserList;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $tab = request('tab') ?? 'profile';
        return view('student.dashboard', compact('tab'));
    }

    public function dashboard($tab = 'profile')
    {
        $user = Auth::user();
        $data = ['tab' => $tab];

        // Load data based on the active tab
        switch ($tab) {
            case 'my_courses':
                $data['enrolledCourses'] = Enrollment::with('course')
                    ->where('user_id', $user->id)
                    ->where('status', 'active')
                    ->paginate(12);
                break;

            case 'archived':
                $data['archivedCourses'] = Enrollment::with('course')
                    ->where('user_id', $user->id)
                    ->where('status', 'archived')
                    ->paginate(12);
                
                // Statistics for archived courses
                $data['completedArchived'] = Enrollment::where('user_id', $user->id)
                    ->where('status', 'archived')
                    ->where('progress', 100)
                    ->count();
                
                $data['partialArchived'] = Enrollment::where('user_id', $user->id)
                    ->where('status', 'archived')
                    ->where('progress', '<', 100)
                    ->where('progress', '>', 0)
                    ->count();
                
                $data['totalArchivedHours'] = Enrollment::with('course')
                    ->where('user_id', $user->id)
                    ->where('status', 'archived')
                    ->get()
                    ->sum(function($enrollment) {
                        return $enrollment->course->duration ?? 0;
                    });
                break;

            case 'certificates':
                $data['certificates'] = Certificate::with('course')
                    ->where('user_id', $user->id)
                    ->paginate(12);
                
                // Statistics for certificates
                $data['verifiedCertificates'] = Certificate::where('user_id', $user->id)
                    ->where('is_verified', true)
                    ->count();
                
                $data['averageRating'] = Certificate::where('user_id', $user->id)
                    ->whereNotNull('course_rating')
                    ->avg('course_rating');
                
                $data['totalDownloads'] = Certificate::where('user_id', $user->id)
                    ->sum('download_count');
                break;

            case 'wishlists':
                $data['wishlistCourses'] = Course::whereHas('wishlists', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->paginate(12);
                break;

            case 'my_lists':
                $data['userLists'] = UserList::with(['courses' => function($query) {
                    $query->take(3);
                }])
                ->where('user_id', $user->id)
                ->withCount('courses')
                ->get();
                break;

            case 'my_tools':
                // Tools don't need specific data loading
                break;

            case 'profile':
            default:
                // Profile data is already available through Auth::user()
                break;
        }

        return view('student.dashboard', $data);
    }
}
