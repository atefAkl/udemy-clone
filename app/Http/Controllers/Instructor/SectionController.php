<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SectionController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Display a listing of the sections for a course.
     *
     * @param  int  $courseId
     * @return \Illuminate\Http\Response
     */
    public function index($courseId)
    {
        $course = Course::findOrFail($courseId);
        
        // Check if user is authorized to view these sections
        $this->authorize('viewAny', [Section::class, $course]);
        
        // If this is an API request, return JSON
        if (request()->wantsJson()) {
            $sections = $course->sections()
                ->withCount('lessons')
                ->ordered()
                ->get();
                
            return response()->json([
                'sections' => $sections,
                'course' => $course
            ]);
        }
        
        // For web requests, return the view
        return view('instructor.sections.index', compact('course'));
    }
    
    /**
     * Show the form for creating a new section.
     *
     * @param  int  $courseId
     * @return \Illuminate\Http\Response
     */
    public function create($courseId)
    {
        $course = Course::findOrFail($courseId);
        $this->authorize('create', [Section::class, $course]);
        
        return view('instructor.sections.create', compact('course'));
    }
    

    /**
     * Store a newly created section in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $courseId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $this->authorize('update', $course);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_published' => 'sometimes|boolean',
            'is_free_preview' => 'sometimes|boolean',
        ]);
        
        $section = DB::transaction(function () use ($course, $validated) {
            $section = new Section($validated);
            $section->course_id = $course->id;
            $section->sort_order = $course->sections()->max('sort_order') + 1;
            $section->save();
            
            return $section->load('lessons');
        });
        
        return response()->json([
            'message' => __('Section created successfully'),
            'section' => $section
        ], 201);
    }

    /**
     * Display the specified section with its lessons.
     *
     * @param  int  $courseId
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show($courseId, Section $section)
    {
        $this->authorize('view', $section);
        
        $section->load(['lessons' => function($query) {
            $query->ordered();
        }]);
        
        return response()->json([
            'section' => $section
        ]);
    }

    /**
     * Show the form for editing the specified section.
     *
     * @param  int  $courseId
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function edit($courseId, Section $section)
    {
        $this->authorize('update', $section);
        
        return response()->json([
            'section' => $section->load('course')
        ]);
    }

    /**
     * Update the specified section in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $courseId
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $courseId, Section $section)
    {
        $this->authorize('update', $section);
        
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'is_published' => 'sometimes|boolean',
            'is_free_preview' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer',
        ]);
        
        $section->update($validated);
        
        return response()->json([
            'message' => __('Section updated successfully'),
            'section' => $section->fresh()
        ]);
    }

    /**
     * Remove the specified section from storage.
     *
     * @param  int  $courseId
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy($courseId, Section $section)
    {
        $this->authorize('delete', $section);
        
        DB::transaction(function () use ($section) {
            // Delete all lessons in this section
            $section->lessons()->delete();
            
            // Delete the section
            $section->delete();
        });
        
        return response()->json([
            'message' => __('Section deleted successfully')
        ]);
    }
    
    /**
     * Reorder sections.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $courseId
     * @return \Illuminate\Http\Response
     */
    public function reorder(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $this->authorize('update', $course);
        
        $request->validate([
            'sections' => 'required|array',
            'sections.*.id' => 'required|exists:sections,id',
            'sections.*.sort_order' => 'required|integer',
        ]);
        
        DB::transaction(function () use ($request) {
            foreach ($request->sections as $item) {
                Section::where('id', $item['id'])
                    ->update(['sort_order' => $item['sort_order']]);
            }
        });
        
        return response()->json([
            'message' => __('Sections reordered successfully')
        ]);
    }
}
