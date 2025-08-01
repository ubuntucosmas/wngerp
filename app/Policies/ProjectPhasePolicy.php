<?php

namespace App\Policies;

use App\Models\ProjectPhase;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPhasePolicy
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
    public function view(User $user, ProjectPhase $projectPhase): bool
    {
        // Get the related project
        $project = $this->getRelatedProject($projectPhase);
        
        if (!$project) {
            return true; // Allow viewing enquiry phases
        }

        // Super admins and PMs can view any project phase
        if ($user->hasAnyRole(['super-admin', 'pm', 'admin'])) {
            return true;
        }

        // POs can only view phases for projects they are assigned to
        if ($user->hasRole('po')) {
            return $project->project_officer_id === $user->id;
        }

        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only PMs, admins, and super admins can create phases
        return $user->hasAnyRole(['super-admin', 'pm', 'admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProjectPhase $projectPhase): bool
    {
        // Get the related project
        $project = $this->getRelatedProject($projectPhase);
        
        if (!$project) {
            return true; // Allow updating enquiry phases
        }

        // Super admins and PMs can update any project phase
        if ($user->hasAnyRole(['super-admin', 'pm', 'admin'])) {
            return true;
        }

        // POs can only update phases for projects they are assigned to
        if ($user->hasRole('po')) {
            return $project->project_officer_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProjectPhase $projectPhase): bool
    {
        // Only PMs, admins, and super admins can delete phases
        return $user->hasAnyRole(['super-admin', 'pm', 'admin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProjectPhase $projectPhase): bool
    {
        return $user->hasAnyRole(['super-admin', 'pm', 'admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProjectPhase $projectPhase): bool
    {
        return $user->hasRole('super-admin');
    }

    /**
     * Get the related project for the phase
     */
    private function getRelatedProject(ProjectPhase $projectPhase): ?\App\Models\Project
    {
        // Check if it's a project phase
        if ($projectPhase->phaseable_type === \App\Models\Project::class) {
            return \App\Models\Project::find($projectPhase->phaseable_id);
        }

        // If it's an enquiry phase, return null
        return null;
    }
}
