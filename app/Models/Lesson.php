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
        'section_id',
        'title',
        'slug',
        'description',
        'content_type',
        'content',
        'video_url',
        'video_file',
        'duration',
        'article_content',
        'downloadable_resources',
        'sort_order',
        'is_free_preview',
        'is_published',
        'learning_objectives',
        'transcript',
        'notes',
        'thumbnail',
        'attachment',
        'external_url',
        'extra_data',
    ];

    protected $casts = [
        'is_free_preview' => 'boolean',
        'is_published' => 'boolean',
        'duration' => 'integer',
        'sort_order' => 'integer',
        'downloadable_resources' => 'array',
        'learning_objectives' => 'array',
        'extra_data' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'formatted_duration',
    ];

    // Content Type Constants
    const TYPE_VIDEO = 'video';
    const TYPE_ARTICLE = 'article';
    const TYPE_QUIZ = 'quiz';
    const TYPE_ASSIGNMENT = 'assignment';
    const TYPE_DOWNLOAD = 'download';
    const TYPE_LIVE_SESSION = 'live_session';

    /**
     * Course relationship
     */
    /**
     * Get the course that owns the lesson.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the section that owns the lesson.
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Module relationship
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * User progress for this lesson
     */
    public function progress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    /**
     * Quiz questions (if lesson is a quiz)
     */
    public function quizQuestions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    /**
     * Assignment submissions (if lesson is an assignment)
     */
    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    /**
     * Lesson resources relationship
     */
    public function resources()
    {
        return $this->hasMany(LessonResource::class)->orderBy('sort_order');
    }

    /**
     * Downloadable resources only
     */
    public function downloadableResources()
    {
        return $this->hasMany(LessonResource::class)->where('is_downloadable', true)->orderBy('sort_order');
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
     * Check if lesson is a video
     */
    public function isVideo(): bool
    {
        return $this->content_type === self::TYPE_VIDEO;
    }

    /**
     * Check if lesson is an article
     */
    public function isArticle(): bool
    {
        return $this->content_type === self::TYPE_ARTICLE;
    }

    /**
     * Check if lesson is a quiz
     */
    public function isQuiz(): bool
    {
        return $this->content_type === self::TYPE_QUIZ;
    }

    /**
     * Check if lesson is an assignment
     */
    public function isAssignment(): bool
    {
        return $this->content_type === self::TYPE_ASSIGNMENT;
    }

    /**
     * Check if lesson is a download
     */
    public function isDownload(): bool
    {
        return $this->content_type === self::TYPE_DOWNLOAD;
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
        return asset('storage/lessons/videos/' . $value);
    }

    /**
     * Get video file URL
     */
    public function getVideoFileUrlAttribute(): ?string
    {
        if (!$this->video_file) {
            return null;
        }

        return asset('storage/lessons/videos/' . $this->video_file);
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
        $duration = $this->video_duration ?: $this->estimated_duration;

        if (!$duration) {
            return '0:00';
        }

        $minutes = floor($duration / 60);
        $seconds = $duration % 60;

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    /**
     * Get content type icon
     */
    public function getContentTypeIconAttribute(): string
    {
        return match ($this->content_type) {
            self::TYPE_VIDEO => 'fas fa-play-circle',
            self::TYPE_ARTICLE => 'fas fa-file-alt',
            self::TYPE_QUIZ => 'fas fa-question-circle',
            self::TYPE_ASSIGNMENT => 'fas fa-tasks',
            self::TYPE_DOWNLOAD => 'fas fa-download',
            self::TYPE_LIVE_SESSION => 'fas fa-video',
            default => 'fas fa-file'
        };
    }

    /**
     * Get content type label
     */
    public function getContentTypeLabelAttribute(): string
    {
        return match ($this->content_type) {
            self::TYPE_VIDEO => __('app.video'),
            self::TYPE_ARTICLE => __('app.article'),
            self::TYPE_QUIZ => __('app.quiz'),
            self::TYPE_ASSIGNMENT => __('app.assignment'),
            self::TYPE_DOWNLOAD => __('app.download'),
            self::TYPE_LIVE_SESSION => __('app.live_session'),
            default => __('app.content')
        };
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
                'completion_percentage' => 100,
            ]
        );
    }

    /**
     * Update lesson progress for user
     */
    public function updateProgress(User $user, int $percentage, array $data = []): void
    {
        LessonProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $this->id,
            ],
            [
                'completion_percentage' => $percentage,
                'progress_data' => $data,
                'is_completed' => $percentage >= 100,
                'completed_at' => $percentage >= 100 ? now() : null,
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
     * Get user progress percentage
     */
    public function getProgressPercentage(User $user): int
    {
        $progress = $this->progress()
            ->where('user_id', $user->id)
            ->first();

        return $progress ? $progress->completion_percentage : 0;
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

    /**
     * Scope by content type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('content_type', $type);
    }
}
