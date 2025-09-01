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
        'completion_percentage',
        'watch_time',
        'progress_data',
        'completed_at',
        'last_accessed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completion_percentage' => 'integer',
        'watch_time' => 'integer',
        'progress_data' => 'array',
        'completed_at' => 'datetime',
        'last_accessed_at' => 'datetime',
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
            'completion_percentage' => 100,
            'completed_at' => now(),
            'last_accessed_at' => now(),
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
     * Update progress percentage
     */
    public function updateProgress(int $percentage, array $data = []): void
    {
        $this->update([
            'completion_percentage' => min(100, max(0, $percentage)),
            'progress_data' => array_merge($this->progress_data ?? [], $data),
            'is_completed' => $percentage >= 100,
            'completed_at' => $percentage >= 100 ? now() : null,
            'last_accessed_at' => now(),
        ]);

        // Update course progress if lesson is completed
        if ($percentage >= 100) {
            $enrollment = Enrollment::where('user_id', $this->user_id)
                ->where('course_id', $this->lesson->course_id)
                ->first();

            if ($enrollment) {
                $enrollment->updateProgress();
            }
        }
    }

    /**
     * Update watch time
     */
    public function updateWatchTime(int $seconds): void
    {
        $this->update([
            'watch_time' => $seconds,
            'last_accessed_at' => now(),
        ]);
    }

    /**
     * Record quiz attempt
     */
    public function recordQuizAttempt(array $answers, int $score, int $maxScore): void
    {
        $percentage = $maxScore > 0 ? round(($score / $maxScore) * 100) : 0;

        $quizData = [
            'answers' => $answers,
            'score' => $score,
            'max_score' => $maxScore,
            'percentage' => $percentage,
            'attempted_at' => now()->toISOString(),
        ];

        $this->updateProgress($percentage, ['quiz' => $quizData]);
    }

    /**
     * Record assignment submission
     */
    public function recordAssignmentSubmission(string $submissionPath, ?string $notes = null): void
    {
        $assignmentData = [
            'submission_path' => $submissionPath,
            'notes' => $notes,
            'submitted_at' => now()->toISOString(),
            'status' => 'submitted',
        ];

        $this->updateProgress(100, ['assignment' => $assignmentData]);
    }

    /**
     * Record download completion
     */
    public function recordDownloadCompletion(array $downloadedFiles): void
    {
        $downloadData = [
            'downloaded_files' => $downloadedFiles,
            'downloaded_at' => now()->toISOString(),
        ];

        $this->updateProgress(100, ['download' => $downloadData]);
    }

    /**
     * Get progress percentage as formatted string
     */
    public function getFormattedProgressAttribute(): string
    {
        return $this->completion_percentage . '%';
    }

    /**
     * Check if progress is complete
     */
    public function isComplete(): bool
    {
        return $this->is_completed && $this->completion_percentage >= 100;
    }

    /**
     * Get time spent in human readable format
     */
    public function getFormattedWatchTimeAttribute(): string
    {
        if (!$this->watch_time) {
            return '0:00';
        }

        $minutes = floor($this->watch_time / 60);
        $seconds = $this->watch_time % 60;

        return sprintf('%d:%02d', $minutes, $seconds);
    }
}
