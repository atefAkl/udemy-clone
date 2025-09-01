<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'title',
        'slug',
        'description',
        'sort_order',
        'estimated_duration',
        'is_published',
        'objectives',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'sort_order' => 'integer',
        'estimated_duration' => 'integer', // in minutes
        'objectives' => 'array',
    ];

    /**
     * Course relationship
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Lessons relationship
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('sort_order');
    }

    /**
     * Published lessons only
     */
    public function publishedLessons()
    {
        return $this->hasMany(Lesson::class)->where('is_published', true)->orderBy('sort_order');
    }

    /**
     * Check if module is published
     */
    public function isPublished(): bool
    {
        return $this->is_published;
    }

    /**
     * Get total lessons count
     */
    public function getLessonsCountAttribute(): int
    {
        return $this->lessons()->count();
    }

    /**
     * Get published lessons count
     */
    public function getPublishedLessonsCountAttribute(): int
    {
        return $this->publishedLessons()->count();
    }

    /**
     * Get total duration of all lessons in minutes
     */
    public function getTotalDurationAttribute(): int
    {
        return $this->lessons()->sum('video_duration');
    }

    /**
     * Get formatted total duration
     */
    public function getFormattedDurationAttribute(): string
    {
        $totalMinutes = $this->total_duration;
        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        if ($hours > 0) {
            return sprintf('%dh %dm', $hours, $minutes);
        }

        return sprintf('%dm', $minutes);
    }

    /**
     * Get next module in course
     */
    public function getNextModule(): ?Module
    {
        return $this->course->modules()
            ->where('sort_order', '>', $this->sort_order)
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->first();
    }

    /**
     * Get previous module in course
     */
    public function getPreviousModule(): ?Module
    {
        return $this->course->modules()
            ->where('sort_order', '<', $this->sort_order)
            ->where('is_published', true)
            ->orderBy('sort_order', 'desc')
            ->first();
    }

    /**
     * Scope for published modules
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope for modules ordered by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
