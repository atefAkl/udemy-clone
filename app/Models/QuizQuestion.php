<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizQuestion extends Model
{
    protected $fillable = [
        'quiz_id',
        'question_text',
        'question_type',
        'points',
        'image_path',
        'explanation',
        'sort_order',
    ];

    protected $casts = [
        'points' => 'decimal:2',
    ];

    /**
     * Get the quiz that owns the question
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Get the answers for the question
     */
    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class, 'question_id')->orderBy('sort_order');
    }

    /**
     * Get the correct answer(s)
     */
    public function correctAnswers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class, 'question_id')->where('is_correct', true);
    }
}
