<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Course;
use App\Http\Requests\Courses\SectionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SectionController extends Controller
{
    use AuthorizesRequests;

    public function index($courseId)
    {
        $course = Course::findOrFail($courseId);

        return response()->json([
            'course' => $course
        ]);
    }


    public function store(SectionRequest $request, Course $course)
    {
        $vtd = $request->validated();
        $vtd['course_id'] = $course->id;
        $vtd['sort_order'] = $course->sections->max('sort_order') + 1;

        try {
            Section::create($vtd);
            return redirect()->back()->with('success', 'Section created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Section creation failed: ' . $e->getMessage());
        }
    }

    public function update(SectionRequest $request, Section $section)
    {
        try {
            $validated = $request->validated();
            $oldOrder = $section->sort_order;
            $newOrder = $validated['sort_order'] ?? $oldOrder;

            DB::transaction(function () use ($section, $validated, $oldOrder, $newOrder) {
                // If sort_order changed, reorder other sections
                if ($newOrder != $oldOrder) {
                    $course = $section->course;
                    
                    // Ensure new order is within valid range
                    $maxOrder = $course->sections()->count();
                    $newOrder = max(1, min($newOrder, $maxOrder));
                    
                    if ($newOrder > $oldOrder) {
                        // Moving down: decrement sections between old and new position
                        $course->sections()
                            ->where('sort_order', '>', $oldOrder)
                            ->where('sort_order', '<=', $newOrder)
                            ->decrement('sort_order');
                    } else {
                        // Moving up: increment sections between new and old position
                        $course->sections()
                            ->where('sort_order', '>=', $newOrder)
                            ->where('sort_order', '<', $oldOrder)
                            ->increment('sort_order');
                    }
                    
                    $validated['sort_order'] = $newOrder;
                }

                // Update the section
                $section->update($validated);
            });

            return redirect()->back()->with('success', 'Section updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Section update failed: ' . $e->getMessage());
        }
    }

    public function destroy(Section $section)
    {
        try {
            DB::transaction(function () use ($section) {
                // Get sections with higher sort_order from the SAME COURSE
                $affectedSections = $section->course->sections()
                    ->where('sort_order', '>', $section->sort_order)
                    ->get();

                // Delete all lessons in this section
                $section->lessons()->delete();

                // Delete the section
                $section->delete();

                // Decrement sort_order for sections that came after this one
                foreach ($affectedSections as $affectedSection) {
                    $affectedSection->decrement('sort_order');
                }
            });

            return redirect()->back()->with('success', 'Section and all its lessons deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Section deletion failed: ' . $e->getMessage());
        }
    }
}
