<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Add course to wishlist
     */
    public function add(Course $course)
    {
        $user = Auth::user();
        
        $wishlistItem = Wishlist::addToWishlist($user->id, $course->id);
        
        return response()->json([
            'success' => true,
            'message' => __('student.course_added_to_wishlist'),
            'in_wishlist' => true
        ]);
    }

    /**
     * Remove course from wishlist
     */
    public function remove(Course $course)
    {
        $user = Auth::user();
        
        Wishlist::removeFromWishlist($user->id, $course->id);
        
        return response()->json([
            'success' => true,
            'message' => __('student.course_removed_from_wishlist'),
            'in_wishlist' => false
        ]);
    }
}
