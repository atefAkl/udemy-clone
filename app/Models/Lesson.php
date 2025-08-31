<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'title',
        'slug',
        'description',
        'video_url',
        'video_duration',
        'sort_order',
        'is_free',
        'is_published',
        'resources',
        'transcript',
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'is_published' => 'boolean',
        'video_duration' => 'integer',
        'sort_order' => 'integer',
        'resources' => 'array',
    ];

    /**
     * Course relationship
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * User progress for this lesson
     */
    public function progress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    /**
     * Check if lesson is free
     */
    public function isFree(): bool
    {
        return $this->is_free;
    }

    /**
     * Check if lesson is published
     */
    public function isPublished(): bool
    {
        return $this->is_published;
    }

    /**
     * Get lesson video URL
     */
    public function getVideoUrlAttribute($value): ?string
    {
        if (!$value) {
            return null;
        }

        // If it's already a full URL, return as is
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // Otherwise, assume it's a storage path
        return asset('storage/lessons/' . $value);
    }

    /**
     * Get lesson URL
     */
    public function getUrlAttribute(): string
    {
        return route('courses.lessons.show', [
            'course' => $this->course->slug,
            'lesson' => $this->slug
        ]);
    }

    /**
     * Format duration in human readable format
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->video_duration) {
            return '0:00';
        }

        $minutes = floor($this->video_duration / 60);
        $seconds = $this->video_duration % 60;

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    /**
     * Get next lesson in course
     */
    public function getNextLesson(): ?Lesson
    {
        return $this->course->lessons()
            ->where('sort_order', '>', $this->sort_order)
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->first();
    }

    /**
     * Get previous lesson in course
     */
    public function getPreviousLesson(): ?Lesson
    {
        return $this->course->lessons()
            ->where('sort_order', '<', $this->sort_order)
            ->where('is_published', true)
            ->orderBy('sort_order', 'desc')
            ->first();
    }

    /**
     * Check if user can access this lesson
     */
    public function canAccess(User $user): bool
    {
        // Free lessons are accessible to everyone
        if ($this->is_free) {
            return true;
        }

        // Check if user is enrolled in the course
        return $this->course->students()->where('user_id', $user->id)->exists();
    }

    /**
     * Mark lesson as completed for user
     */
    public function markAsCompleted(User $user): void
    {
        LessonProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $this->id,
            ],
            [
                'is_completed' => true,
                'completed_at' => now(),
            ]
        );
    }

    /**
     * Check if lesson is completed by user
     */
    public function isCompletedBy(User $user): bool
    {
        return $this->progress()
            ->where('user_id', $user->id)
            ->where('is_completed', true)
            ->exists();
    }

    /**
     * Scope for published lessons
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope for free lessons
     */
    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }
}
