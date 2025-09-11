<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\UserList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListController extends Controller
{
    /**
     * Display a listing of user lists
     */
    public function index()
    {
        $user = Auth::user();
        $lists = UserList::with(['courses' => function($query) {
            $query->take(3);
        }])
        ->where('user_id', $user->id)
        ->withCount('courses')
        ->get();

        return view('student.lists.index', compact('lists'));
    }

    /**
     * Show the form for creating a new list
     */
    public function create()
    {
        return view('student.lists.create');
    }

    /**
     * Store a newly created list
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_public' => 'boolean'
        ]);

        $list = UserList::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'is_public' => $request->boolean('is_public', false)
        ]);

        return redirect()->route('student.lists.show', $list)
            ->with('success', __('student.list_created_successfully'));
    }

    /**
     * Display the specified list
     */
    public function show(UserList $list)
    {
        // Check if user owns the list or if it's public
        if ($list->user_id !== Auth::id() && !$list->is_public) {
            abort(403);
        }

        $list->load('courses');

        return view('student.lists.show', compact('list'));
    }

    /**
     * Show the form for editing the specified list
     */
    public function edit(UserList $list)
    {
        // Check if user owns the list
        if ($list->user_id !== Auth::id()) {
            abort(403);
        }

        return view('student.lists.edit', compact('list'));
    }

    /**
     * Update the specified list
     */
    public function update(Request $request, UserList $list)
    {
        // Check if user owns the list
        if ($list->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_public' => 'boolean'
        ]);

        $list->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_public' => $request->boolean('is_public', false)
        ]);

        return redirect()->route('student.lists.show', $list)
            ->with('success', __('student.list_updated_successfully'));
    }

    /**
     * Remove the specified list
     */
    public function destroy(UserList $list)
    {
        // Check if user owns the list
        if ($list->user_id !== Auth::id()) {
            abort(403);
        }

        $list->delete();

        return redirect()->route('student.lists.index')
            ->with('success', __('student.list_deleted_successfully'));
    }

    /**
     * Add course to list
     */
    public function addCourse(UserList $list, Course $course)
    {
        // Check if user owns the list
        if ($list->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$list->hasCourse($course)) {
            $list->addCourse($course);
            
            return response()->json([
                'success' => true,
                'message' => __('student.course_added_to_list')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __('student.course_already_in_list')
        ]);
    }

    /**
     * Remove course from list
     */
    public function removeCourse(UserList $list, Course $course)
    {
        // Check if user owns the list
        if ($list->user_id !== Auth::id()) {
            abort(403);
        }

        $list->removeCourse($course);

        return response()->json([
            'success' => true,
            'message' => __('student.course_removed_from_list')
        ]);
    }
}
