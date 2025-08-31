<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lesson_id',
        'is_completed',
        'watch_time',
        'completed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'watch_time' => 'integer',
        'completed_at' => 'datetime',
    ];

    /**
     * User relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Lesson relationship
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Mark as completed
     */
    public function markCompleted(): void
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
        ]);

        // Update course progress
        $enrollment = Enrollment::where('user_id', $this->user_id)
            ->where('course_id', $this->lesson->course_id)
            ->first();

        if ($enrollment) {
            $enrollment->updateProgress();
        }
    }

    /**
     * Update watch time
     */
    public function updateWatchTime(int $seconds): void
    {
        $this->update(['watch_time' => $seconds]);
    }
}
