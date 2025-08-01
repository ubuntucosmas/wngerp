<?php

namespace App\Policies;

use App\Models\Quote;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class QuotePolicy
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
    public function view(User $user, Quote $quote): bool
    {
        // Get the related project
        $project = $quote->project;
        
        if (!$project) {
            return true; // Allow viewing enquiry quotes
        }

        // Super admins and PMs can view any quote
        if ($user->hasAnyRole(['super-admin', 'pm', 'admin'])) {
            return true;
        }

        // POs can only view quotes for projects they are assigned to
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
        return true; // Allow creation, but check project assignment in controller
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Quote $quote): bool
    {
        // Get the related project
        $project = $quote->project;
        
        if (!$project) {
            return true; // Allow updating enquiry quotes
        }

        // Super admins and PMs can update any quote
        if ($user->hasAnyRole(['super-admin', 'pm', 'admin'])) {
            return true;
        }

        // POs can only update quotes for projects they are assigned to
        if ($user->hasRole('po')) {
            return $project->project_officer_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Quote $quote): bool
    {
        // Get the related project
        $project = $quote->project;
        
        if (!$project) {
            return true; // Allow deleting enquiry quotes
        }

        // Super admins and PMs can delete any quote
        if ($user->hasAnyRole(['super-admin', 'pm', 'admin'])) {
            return true;
        }

        // POs can only delete quotes for projects they are assigned to
        if ($user->hasRole('po')) {
            return $project->project_officer_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Quote $quote): bool
    {
        return $user->hasAnyRole(['super-admin', 'pm', 'admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Quote $quote): bool
    {
        return $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user, Quote $quote): bool
    {
        // Only PMs, admins, and super admins can approve quotes
        return $user->hasAnyRole(['super-admin', 'pm', 'admin']);
    }
}
