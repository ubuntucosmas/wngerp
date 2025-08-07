<?php

namespace App\Policies;

use App\Models\MaterialList;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MaterialListPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MaterialList $materialList): bool
    {
        // Get the related project or enquiry
        $project = $materialList->project;
        $enquiry = $materialList->enquiry;
        
        // Allow viewing enquiry material lists (before conversion)
        if (!$project && $enquiry) {
            return true;
        }

        // For project material lists (including converted from enquiries)
        if ($project) {
            // Super admins and PMs can view any material list
            if ($user->hasAnyRole(['super-admin', 'pm', 'admin'])) {
                return true;
            }

            // POs can view all material lists (read-only for non-assigned projects)
            if ($user->hasRole('po')) {
                return true; // Allow viewing all project material lists
            }
        }

        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Allow creation, but check project assignment in controller
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MaterialList $materialList): bool
    {
        // Get the related project
        $project = $materialList->project;
        
        if (!$project) {
            return true; // Allow updating enquiry material lists
        }

        // Super admins and PMs can update any material list
        if ($user->hasAnyRole(['super-admin', 'pm', 'po', 'admin'])) {
            return true;
        }

        // POs can only update material lists for projects they are assigned to
        if ($user->hasRole('po')) {
            return $project->project_officer_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MaterialList $materialList): bool
    {
        // Get the related project
        $project = $materialList->project;
        
        if (!$project) {
            return true; // Allow deleting enquiry material lists
        }

        // Super admins and PMs can delete any material list
        if ($user->hasAnyRole(['super-admin', 'pm', 'admin'])) {
            return true;
        }

        // POs can only delete material lists for projects they are assigned to
        if ($user->hasRole('po')) {
            return $project->project_officer_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MaterialList $materialList): bool
    {
        return $user->hasAnyRole(['super-admin', 'pm', 'admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MaterialList $materialList): bool
    {
        return $user->hasRole('super-admin');
    }
}
