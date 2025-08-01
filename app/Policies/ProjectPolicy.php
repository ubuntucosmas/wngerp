<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view the projects list
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        // Super admins and PMs can view any project
        if ($user->hasAnyRole(['super-admin', 'pm', 'admin'])) {
            return true;
        }

        // POs can view all projects (read-only for non-assigned projects)
        if ($user->hasRole('po')) {
            return true; // Allow viewing all projects
        }

        // Other roles can view all projects
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only PMs, admins, and super admins can create projects
        return $user->hasAnyRole(['super-admin', 'pm', 'admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        // Super admins and PMs can update any project
        if ($user->hasAnyRole(['super-admin', 'pm', 'admin'])) {
            return true;
        }

        // POs can only update projects they are assigned to
        if ($user->hasRole('po')) {
            return $project->project_officer_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can edit project files/resources.
     * This is stricter than view - POs can only edit resources for assigned projects.
     */
    public function edit(User $user, Project $project): bool
    {
        // Super admins and PMs can edit any project resources
        if ($user->hasAnyRole(['super-admin', 'pm', 'admin'])) {
            return true;
        }

        // POs can only edit resources for projects they are assigned to
        if ($user->hasRole('po')) {
            return $project->project_officer_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        // Only PMs, admins, and super admins can delete projects
        return $user->hasAnyRole(['super-admin', 'pm', 'admin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        // Only PMs, admins, and super admins can restore projects
        return $user->hasAnyRole(['super-admin', 'pm', 'admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        // Only super admins can permanently delete projects
        return $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can access project files.
     */
    public function accessFiles(User $user, Project $project): bool
    {
        // Super admins and PMs can access any project files
        if ($user->hasAnyRole(['super-admin', 'pm', 'admin'])) {
            return true;
        }

        // POs can only access files for projects they are assigned to
        if ($user->hasRole('po')) {
            return $project->project_officer_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can manage project phases.
     */
    public function managePhases(User $user, Project $project): bool
    {
        // Super admins and PMs can manage any project phases
        if ($user->hasAnyRole(['super-admin', 'pm', 'admin'])) {
            return true;
        }

        // POs can only manage phases for projects they are assigned to
        if ($user->hasRole('po')) {
            return $project->project_officer_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can manage project budget.
     */
    public function manageBudget(User $user, Project $project): bool
    {
        // Super admins and PMs can manage any project budget
        if ($user->hasAnyRole(['super-admin', 'pm', 'admin'])) {
            return true;
        }

        // POs can only manage budget for projects they are assigned to
        if ($user->hasRole('po')) {
            return $project->project_officer_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can assign project officers.
     */
    public function assignOfficer(User $user, Project $project): bool
    {
        // Only PMs, admins, and super admins can assign project officers
        return $user->hasAnyRole(['super-admin', 'pm', 'admin']);
    }
}
