<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'section_id',
        'title',
        'description',
        'quiz_type',
        'duration_minutes',
        'pass_percentage',
        'randomize_questions',
        'show_results',
        'max_attempts',
        'sort_order',
        'is_published',
    ];

    protected $casts = [
        'randomize_questions' => 'boolean',
        'show_results' => 'boolean',
        'is_published' => 'boolean',
        'pass_percentage' => 'decimal:2',
    ];

    /**
     * Get the section that owns the quiz
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the questions for the quiz
     */
    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('sort_order');
    }

    /**
     * Get total points for the quiz
     */
    public function getTotalPointsAttribute(): float
    {
        return $this->questions()->sum('points');
    }
}
