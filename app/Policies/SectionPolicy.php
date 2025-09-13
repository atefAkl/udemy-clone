<?php

namespace App\Policies;

use App\Models\Section;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SectionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_section');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Section $section): bool
    {
        // Allow if user is the course instructor or has view permission
        return $user->id === $section->course->instructor_id || 
               $user->can('view_section');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only instructors can create sections
        return $user->hasRole('instructor') || $user->can('create_section');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Section $section): bool
    {
        // Only the course instructor can update sections
        return $user->id === $section->course->instructor_id || 
               $user->can('update_section');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Section $section): bool
    {
        // Only the course instructor can delete sections
        return $user->id === $section->course->instructor_id || 
               $user->can('delete_section');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Section $section): bool
    {
        return $user->id === $section->course->instructor_id || 
               $user->can('restore_section');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Section $section): bool
    {
        return $user->id === $section->course->instructor_id || 
               $user->can('force_delete_section');
    }
}
