<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonAssignment extends Model
{
    protected $fillable = [
        'lesson_id',
        'title',
        'description',
        'instructions',
        'due_days',
        'max_score',
        'allowed_file_types',
        'max_file_size',
        'is_required',
    ];

    protected $casts = [
        'max_score' => 'decimal:2',
        'is_required' => 'boolean',
    ];

    /**
     * Get the lesson that owns the assignment
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
