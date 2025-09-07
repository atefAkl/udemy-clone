<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    //
    public function all()
    {
        $vars = [
            'tab' => 'courses'
        ];
        return view('student.dashboard', $vars);
    }
    public function lists()
    {
        $vars = [
            'tab' => 'courses'
        ];
        return view('student.dashboard', $vars);
    }
}
