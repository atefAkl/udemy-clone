<?php

use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\Instructor\InstructorController;
use App\Http\Controllers\Instructor\SectionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Student\ToolsController as StudentToolsController;
use App\Http\Controllers\Student\CertificateController as StudentCertificateController;
use App\Http\Controllers\Student\WishlistController as StudentWishlistController;
use App\Http\Controllers\Student\ListController as StudentListController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Language switching
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, array_keys(config('app.supported_locales')))) {
        session(['locale' => $locale]);
        $user = User::find(Auth::id());
        // Update user preference if logged in
        if ($user) {
            $user->preferred_language = $locale;
            $user->save();
        }
    }

    return redirect()->back();
})->name('lang.switch');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Password Reset Routes
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');

    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

    Route::get('/reset-password/{token}', function ($token) {
        return view('auth.reset-password', ['token' => $token]);
    })->name('password.reset');

    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Course Routes (Public)
Route::prefix('courses')->name('courses.')->group(function () {
    Route::get('/', [CourseController::class, 'index'])->name('index');
    Route::get('/search', [CourseController::class, 'search'])->name('courses.search');
    Route::get('/{course:slug}', [CourseController::class, 'show'])->name('show');

    // Protected course routes
    Route::middleware('auth')->group(function () {
        Route::post('/{course:slug}/enroll', [CourseController::class, 'enroll'])->name('enroll');
        Route::get('/{course:slug}/learn', [CourseController::class, 'learn'])->name('learn');
    });
});

// Lesson Resource Routes
Route::middleware('auth')->group(function () {
    Route::prefix('lessons/{lesson}/resources')->name('lessons.resources.')->group(function () {
        Route::get('/', [ResourceController::class, 'index'])->name('index');
        Route::post('/', [ResourceController::class, 'upload'])->name('upload');
        Route::get('/{resource}/download', [ResourceController::class, 'download'])->name('download');
        Route::put('/{resource}', [ResourceController::class, 'update'])->name('update');
        Route::delete('/{resource}', [ResourceController::class, 'destroy'])->name('destroy');
        Route::post('/reorder', [ResourceController::class, 'reorder'])->name('reorder');
    });
});

// Global Search Route
Route::get('/search', [CourseController::class, 'search'])->name('search');

// Student Dashboard
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        // Redirect based on user role
        if (Auth::user()->role === 'instructor') {
            return redirect()->route('instructor.dashboard');
        } elseif (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::user()->role === 'student') {
            return redirect()->route('student.dashboard');
        }
    })->name('dashboard');

    // Mark welcome message as shown
    Route::post('/dashboard/welcome-shown', function () {
        session(['welcome_shown' => true]);
        return response()->json(['success' => true]);
    })->name('dashboard.welcome.shown');
});

// Student Routes
Route::prefix('student')->name('student.')->middleware('auth', 'role:student')->group(function () {
    Route::get('/dashboard/{tab?}', [StudentProfileController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('/profile', [StudentProfileController::class, 'index'])
        ->name('profile');

    Route::get('/courses/lists', [StudentCourseController::class, 'lists'])
        ->name('courses.lists');

    Route::get('/courses/all', [StudentCourseController::class, 'all'])
        ->name('courses.all');

    Route::get('/courses/wishlist', [StudentCourseController::class, 'wishlist'])
        ->name('courses.wishlist');

    // Student Tools Routes
    Route::get('/notes', [StudentToolsController::class, 'notes'])
        ->name('notes');

    Route::get('/calendar', [StudentToolsController::class, 'calendar'])
        ->name('calendar');

    Route::get('/assessments', [StudentToolsController::class, 'assessments'])
        ->name('assessments');

    Route::get('/progress-tracker', [StudentToolsController::class, 'progressTracker'])
        ->name('progress.tracker');

    Route::get('/study-planner', [StudentToolsController::class, 'studyPlanner'])
        ->name('study.planner');

    Route::get('/discussion-forums', [StudentToolsController::class, 'discussionForums'])
        ->name('discussion.forums');

    Route::get('/discussions', [StudentToolsController::class, 'discussions'])
        ->name('discussions');

    Route::get('/downloads', [StudentToolsController::class, 'downloads'])
        ->name('downloads');

    Route::get('/statistics', [StudentToolsController::class, 'statistics'])
        ->name('statistics');

    Route::get('/study-groups', [StudentToolsController::class, 'studyGroups'])
        ->name('study-groups');

    Route::get('/quick-review', [StudentToolsController::class, 'quickReview'])
        ->name('quick-review');

    Route::get('/goals', [StudentToolsController::class, 'goals'])
        ->name('goals');

    Route::get('/reports', [StudentToolsController::class, 'reports'])
        ->name('reports');

    // Certificate Routes
    Route::get('/certificate/{certificate}/download', [StudentCertificateController::class, 'download'])
        ->name('certificate.download');

    Route::get('/certificate/{certificate}/share', [StudentCertificateController::class, 'share'])
        ->name('certificate.share');

    // Wishlist Routes
    Route::post('/wishlist/add/{course}', [StudentWishlistController::class, 'add'])
        ->name('wishlist.add');

    Route::delete('/wishlist/remove/{course}', [StudentWishlistController::class, 'remove'])
        ->name('wishlist.remove');

    // User Lists Routes
    Route::resource('lists', StudentListController::class);
    Route::post('/lists/{list}/courses/{course}', [StudentListController::class, 'addCourse'])
        ->name('lists.add.course');
    Route::delete('/lists/{list}/courses/{course}', [StudentListController::class, 'removeCourse'])
        ->name('lists.remove.course');

    Route::get('/courses/certificates', [StudentCourseController::class, 'certificates'])
        ->name('courses.certificates');

    Route::get('/courses/{course}', [StudentController::class, 'showCourse'])
        ->name('courses.show');
});

// Instructor Routes
Route::prefix('instructor')->name('instructor.')->middleware(['auth', 'role:instructor'])->group(function () {
    Route::get('/dashboard', [InstructorController::class, 'dashboard'])->name('dashboard');
    Route::get('/settings', [InstructorController::class, 'settings'])->name('settings');

    // Course Management
    Route::prefix('courses')->name('courses.')->group(function () {
        // Section Management
        Route::get('{course}/sections', [SectionController::class, 'index'])
            ->name('sections.index');
        Route::get('{course}/sections/create', [SectionController::class, 'create'])
            ->name('sections.create');
        Route::post('{course}/sections', [SectionController::class, 'store'])
            ->name('sections.store');
        Route::get('{course}/sections/{section}', [SectionController::class, 'show'])
            ->name('sections.show');
        Route::get('{course}/sections/{section}/edit', [SectionController::class, 'edit'])
            ->name('sections.edit');
        Route::put('{course}/sections/{section}', [SectionController::class, 'update'])
            ->name('sections.update');
        Route::delete('{course}/sections/{section}', [SectionController::class, 'destroy'])
            ->name('sections.destroy');
        Route::get('/', [InstructorController::class, 'courses'])->name('index');
        Route::get('/create', [InstructorController::class, 'createCourse'])->name('create');
        Route::get('/create/wide', [InstructorController::class, 'createCourseWide'])->name('create.wide');
        Route::post('/', [InstructorController::class, 'storeCourse'])->name('store');
        Route::get('/{course}', [InstructorController::class, 'showCourse'])->name('show');
        Route::get('/{course}/edit', [InstructorController::class, 'editCourse'])->name('edit');
        Route::put('/{course}', [InstructorController::class, 'updateCourse'])->name('update');
        // Support JSON partial updates (autosave)
        Route::patch('/{course}', [InstructorController::class, 'updateCourse'])->name('update.patch');
        Route::delete('/{course}', [InstructorController::class, 'deleteCourse'])->name('delete');
        Route::patch('/{course}/publish', [InstructorController::class, 'publishCourse'])->name('publish');
    });

    // Categories Management
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/',                 [InstructorController::class, 'categories'])->name('index');
        Route::get('/create',           [InstructorController::class, 'createCategory'])->name('create');
        Route::post('/',                [InstructorController::class, 'storeCategory'])->name('store');
        Route::get('/{category}',       [InstructorController::class, 'showCategory'])->name('show');
        Route::get('/{category}/edit',  [InstructorController::class, 'editCategory'])->name('edit');
        Route::put('/{category}',       [InstructorController::class, 'updateCategory'])->name('update');
        Route::delete('/{category}',    [InstructorController::class, 'deleteCategory'])->name('destroy');
    });
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');

    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'users'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\AdminController::class, 'createUser'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\AdminController::class, 'storeUser'])->name('store');
        Route::get('/{user}', [App\Http\Controllers\Admin\AdminController::class, 'showUser'])->name('show');
        Route::get('/{user}/edit', [App\Http\Controllers\Admin\AdminController::class, 'editUser'])->name('edit');
        Route::put('/{user}', [App\Http\Controllers\Admin\AdminController::class, 'updateUser'])->name('update');
        Route::delete('/{user}', [App\Http\Controllers\Admin\AdminController::class, 'deleteUser'])->name('delete');
        Route::post('/{id}/restore', [App\Http\Controllers\Admin\AdminController::class, 'restoreUser'])->name('restore');
    });

    // Instructors Management
    Route::get('/instructors', [App\Http\Controllers\Admin\AdminController::class, 'instructors'])->name('instructors.index');

    // Students Management
    Route::get('/students', [App\Http\Controllers\Admin\AdminController::class, 'students'])->name('students.index');

    // Course Management
    Route::prefix('courses')->name('courses.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'courses'])->name('index');
        Route::get('/{course}', [App\Http\Controllers\Admin\AdminController::class, 'showCourse'])->name('show');
        Route::patch('/{course}/status', [App\Http\Controllers\Admin\AdminController::class, 'updateCourseStatus'])->name('update-status');
    });

    // Categories Management
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/',                             [CategoryController::class, 'index'])->name('index');
        Route::post('/',                            [CategoryController::class, 'store'])->name('store');
        Route::put('/{category}',                   [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}',                [CategoryController::class, 'destroy'])->name('destroy');
        Route::get('/{category}/courses',           [CategoryController::class, 'courses'])->name('courses');
        Route::patch('/{category}/toggle-status',   [CategoryController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/update-order',                [CategoryController::class, 'updateOrder'])->name('update-order');
    });

    // Reviews Management
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'reviews'])->name('index');
        Route::delete('/{review}', [App\Http\Controllers\Admin\AdminController::class, 'deleteReview'])->name('delete');
    });
});
