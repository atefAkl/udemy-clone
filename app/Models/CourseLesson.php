<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseLesson extends Model
{
    protected $fillable = [
        'course_section_id',
        'title',
        'type',
        'order',
        'duration_minutes',
        'preview_video_url',
        'display_files',
        'downloadable_files',
        'request_student_evaluation',
        'request_assignment',
        'assignment_details',
    ];

    protected $casts = [
        'display_files' => 'array',
        'downloadable_files' => 'array',
        'request_student_evaluation' => 'boolean',
        'request_assignment' => 'boolean',
    ];

    public function section()
    {
        return $this->belongsTo(CourseSection::class, 'course_section_id');
    }
}
