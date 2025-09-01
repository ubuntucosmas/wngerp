<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // For AJAX requests, return null to send 401 response
        if ($request->expectsJson()) {
            return null;
        }

        // For regular requests, redirect to login
        return route('login');
    }

    /**
     * Handle an unauthenticated user.
     */
    protected function unauthenticated($request, array $guards)
    {
        if ($request->expectsJson() || $request->ajax()) {
            // For AJAX requests, return JSON response
            return response()->json([
                'message' => 'Your session has expired. Please log in again.',
                'redirect' => route('login'),
                'authenticated' => false
            ], 401);
        }

        // For regular requests, redirect with message
        return redirect()->guest(route('login'))
            ->with('warning', 'Your session has expired. Please log in again.');
    }
}