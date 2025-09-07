<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $tab = request('tab') ?? 'profile';
        return view('student.dashboard', compact('tab'));
    }
}
