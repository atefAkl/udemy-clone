<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'progress',
        'enrolled_at',
        'completed_at',
        'certificate_issued_at',
    ];

    protected $casts = [
        'progress' => 'integer',
        'enrolled_at' => 'datetime',
        'completed_at' => 'datetime',
        'certificate_issued_at' => 'datetime',
    ];

    /**
     * User relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Course relationship
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Check if course is completed
     */
    public function isCompleted(): bool
    {
        return !is_null($this->completed_at);
    }

    /**
     * Mark course as completed
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'completed_at' => now(),
            'progress' => 100,
        ]);
    }

    /**
     * Calculate and update progress
     */
    public function updateProgress(): void
    {
        $totalLessons = $this->course->lessons()->published()->count();

        if ($totalLessons === 0) {
            $this->update(['progress' => 0]);
            return;
        }

        $completedLessons = LessonProgress::where('user_id', $this->user_id)
            ->whereIn('lesson_id', $this->course->lessons()->published()->pluck('id'))
            ->where('is_completed', true)
            ->count();

        $progress = round(($completedLessons / $totalLessons) * 100);

        $this->update(['progress' => $progress]);

        // Auto-complete if 100%
        if ($progress >= 100 && !$this->isCompleted()) {
            $this->markAsCompleted();
        }
    }

    /**
     * Get progress percentage
     */
    public function getProgressPercentageAttribute(): int
    {
        return $this->progress ?? 0;
    }

    /**
     * Issue certificate
     */
    public function issueCertificate(): void
    {
        if ($this->isCompleted() && !$this->certificate_issued_at) {
            $this->update(['certificate_issued_at' => now()]);
        }
    }

    /**
     * Check if certificate is issued
     */
    public function hasCertificate(): bool
    {
        return !is_null($this->certificate_issued_at);
    }
}
