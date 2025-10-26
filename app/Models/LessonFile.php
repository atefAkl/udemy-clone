<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class LessonFile extends Model
{
    protected $fillable = [
        'lesson_id',
        'file_name',
        'file_path',
        'original_name',
        'file_size',
        'file_type',
        'sort_order',
        'download_count',
    ];

    /**
     * Get the lesson that owns the file
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Get human-readable file size
     */
    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get file download URL
     */
    public function getDownloadUrlAttribute(): string
    {
        return route('lesson.file.download', $this->id);
    }
}
