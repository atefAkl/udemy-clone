<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'added_at'
    ];

    protected $casts = [
        'added_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user that owns the wishlist item
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the course in the wishlist
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Scope for user's wishlist
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Check if a course is in user's wishlist
     */
    public static function isInWishlist($userId, $courseId)
    {
        return self::where('user_id', $userId)
                   ->where('course_id', $courseId)
                   ->exists();
    }

    /**
     * Add course to wishlist
     */
    public static function addToWishlist($userId, $courseId)
    {
        return self::firstOrCreate([
            'user_id' => $userId,
            'course_id' => $courseId
        ], [
            'added_at' => now()
        ]);
    }

    /**
     * Remove course from wishlist
     */
    public static function removeFromWishlist($userId, $courseId)
    {
        return self::where('user_id', $userId)
                   ->where('course_id', $courseId)
                   ->delete();
    }

    /**
     * Get wishlist courses with enrollment status
     */
    public static function getWishlistWithStatus($userId)
    {
        return self::with(['course', 'course.enrollments' => function($query) use ($userId) {
            $query->where('user_id', $userId);
        }])
        ->where('user_id', $userId)
        ->orderBy('added_at', 'desc')
        ->get()
        ->map(function($wishlistItem) {
            $course = $wishlistItem->course;
            $course->is_enrolled = $course->enrollments->isNotEmpty();
            $course->enrollment_status = $course->enrollments->first()->status ?? null;
            return $course;
        });
    }
}
