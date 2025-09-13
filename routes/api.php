<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Instructor\SectionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Instructor API Routes
Route::prefix('instructor')->middleware(['auth:sanctum', 'verified', 'role:instructor'])->group(function () {
    // Course sections management
    Route::apiResource('courses.sections', SectionController::class)
        ->except(['create', 'edit'])
        ->shallow();
        
    // Reorder sections
    Route::post('courses/{course}/sections/reorder', [SectionController::class, 'reorder'])
        ->name('api.instructor.sections.reorder');
});
