<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpFoundation\Response;

class HandleUnauthorized
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (AuthorizationException $e) {
            // Check if it's an AJAX request
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => 'You don\'t have permission to perform this action.',
                    'details' => [
                        'reason' => 'Access denied',
                        'action' => 'Contact your administrator if you believe this is an error'
                    ]
                ], 403);
            }

            // For regular requests, redirect with error message
            return redirect()->back()->with([
                'error' => 'unauthorized_access',
                'error_message' => 'You don\'t have permission to perform this action.',
                'error_details' => 'This could be because you\'re trying to access a resource you\'re not assigned to.'
            ]);
        }
    }
}