<?php

namespace App\Policies;

use App\Models\DesignAsset;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DesignAssetPolicy
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
    public function view(User $user, DesignAsset $designAsset): bool
    {
        // Get the related project or enquiry
        $project = $designAsset->project;
        $enquiry = $designAsset->enquiry;
        
        // Allow viewing enquiry design assets (before conversion)
        if (!$project && $enquiry) {
            return true;
        }

        // For project design assets (including converted from enquiries)
        if ($project) {
            // Super admins and PMs can view any design asset
            if ($user->hasAnyRole(['super-admin', 'pm', 'admin'])) {
                return true;
            }

            // POs can view all design assets (read-only for non-assigned projects)
            if ($user->hasRole('po')) {
                return true; // Allow viewing all project design assets
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
    public function update(User $user, DesignAsset $designAsset): bool
    {
        // Get the related project or enquiry
        $project = $designAsset->project;
        $enquiry = $designAsset->enquiry;
        
        if (!$project && $enquiry) {
            // For enquiry design assets
            // Super admins and PMs can update any enquiry design asset
            if ($user->hasAnyRole(['super-admin', 'pm', 'admin'])) {
                return true;
            }

            // POs can only update design assets for enquiries they are assigned to
            if ($user->hasRole('po')) {
                return $enquiry->assigned_po === $user->name;
            }
        }

        if ($project) {
            // For project design assets
            // Super admins and PMs can update any project design asset
            if ($user->hasAnyRole(['super-admin', 'pm', 'admin'])) {
                return true;
            }

            // POs can only update design assets for projects they are assigned to
            if ($user->hasRole('po')) {
                return $project->project_officer_id === $user->id;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DesignAsset $designAsset): bool
    {
        // Get the related project or enquiry
        $project = $designAsset->project;
        $enquiry = $designAsset->enquiry;
        
        if (!$project && $enquiry) {
            // For enquiry design assets
            // Super admins and PMs can delete any enquiry design asset
            if ($user->hasAnyRole(['super-admin', 'pm', 'admin'])) {
                return true;
            }

            // POs can only delete design assets for enquiries they are assigned to
            if ($user->hasRole('po')) {
                return $enquiry->assigned_po === $user->name;
            }
        }

        if ($project) {
            // For project design assets
            // Super admins and PMs can delete any project design asset
            if ($user->hasAnyRole(['super-admin', 'pm', 'admin'])) {
                return true;
            }

            // POs can only delete design assets for projects they are assigned to
            if ($user->hasRole('po')) {
                return $project->project_officer_id === $user->id;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DesignAsset $designAsset): bool
    {
        return $user->hasAnyRole(['super-admin', 'pm', 'admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DesignAsset $designAsset): bool
    {
        return $user->hasRole('super-admin');
    }
}
