<?php

namespace App\Policies;

use App\Models\Enquiry;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EnquiryPolicy
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
    public function view(User $user, Enquiry $enquiry): bool
    {
        // Super admins and PMs can view any enquiry
        if ($user->hasAnyRole(['super-admin', 'pm', 'admin'])) {
            return true;
        }

        // POs can view all enquiries (read-only for non-assigned enquiries)
        if ($user->hasRole('po')) {
            return true; // Allow viewing all enquiries
        }

        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // All authenticated users can create enquiries
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Enquiry $enquiry): bool
    {
        // Super admins and PMs can update any enquiry
        if ($user->hasAnyRole(['super-admin', 'pm', 'admin'])) {
            return true;
        }

        // POs can only update enquiries they are assigned to
        if ($user->hasRole('po')) {
            return $enquiry->assigned_po === $user->name;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Enquiry $enquiry): bool
    {
        // Only PMs, admins, and super admins can delete enquiries
        return $user->hasAnyRole(['super-admin', 'pm', 'admin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Enquiry $enquiry): bool
    {
        return $user->hasAnyRole(['super-admin', 'pm', 'admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Enquiry $enquiry): bool
    {
        return $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can access enquiry files.
     */
    public function accessFiles(User $user, Enquiry $enquiry): bool
    {
        // Super admins and PMs can access any enquiry files
        if ($user->hasAnyRole(['super-admin', 'pm', 'admin'])) {
            return true;
        }

        // POs can only access files for enquiries they are assigned to
        if ($user->hasRole('po')) {
            return $enquiry->assigned_po === $user->name;
        }

        return false;
    }

    /**
     * Determine whether the user can manage enquiry phases.
     */
    public function managePhases(User $user, Enquiry $enquiry): bool
    {
        // Super admins and PMs can manage any enquiry phases
        if ($user->hasAnyRole(['super-admin', 'pm', 'admin'])) {
            return true;
        }

        // POs can only manage phases for enquiries they are assigned to
        if ($user->hasRole('po')) {
            return $enquiry->assigned_po === $user->name;
        }

        return false;
    }

    /**
     * Determine whether the user can convert enquiry to project.
     */
    public function convert(User $user, Enquiry $enquiry): bool
    {
        // Only PMs, admins, and super admins can convert enquiries
        return $user->hasAnyRole(['super-admin', 'pm', 'admin']);
    }
}
