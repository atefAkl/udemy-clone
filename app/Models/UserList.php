<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserList extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'is_public',
        'sort_order'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * Get the user that owns the list
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the courses in this list
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'user_list_courses')
                    ->withPivot('added_at', 'sort_order')
                    ->withTimestamps()
                    ->orderBy('pivot_sort_order');
    }

    /**
     * Add a course to this list
     */
    public function addCourse(Course $course, $sortOrder = null)
    {
        if (!$this->courses()->where('course_id', $course->id)->exists()) {
            $sortOrder = $sortOrder ?? ($this->courses()->count() + 1);
            $this->courses()->attach($course->id, [
                'added_at' => now(),
                'sort_order' => $sortOrder
            ]);
        }
    }

    /**
     * Remove a course from this list
     */
    public function removeCourse(Course $course)
    {
        $this->courses()->detach($course->id);
    }

    /**
     * Check if a course is in this list
     */
    public function hasCourse(Course $course)
    {
        return $this->courses()->where('course_id', $course->id)->exists();
    }

    /**
     * Scope for public lists
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope for private lists
     */
    public function scopePrivate($query)
    {
        return $query->where('is_public', false);
    }

    /**
     * Scope for user's lists
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
