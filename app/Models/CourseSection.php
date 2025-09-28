<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseSection extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'order',
    ];

    public function lessons()
    {
        return $this->hasMany(CourseLesson::class)->orderBy('order');
    }
}
