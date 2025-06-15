<?php

namespace App\Http\Middleware;

use App\Models\Log;
use Closure;

class LogActionsMiddleware
{
    public function handle($request, Closure $next)
    {
        // Proceed with the request and get the response
        $response = $next($request);

        // Check if the user is authenticated
        $performedBy = auth()->check() ? auth()->user()->name : 'Guest';

        // Log the action
        Log::create([
            'action' => $request->route()->getName() ?? 'Unknown Action', // Capture the route name
            'performed_by' => $performedBy,
            'details' => json_encode($request->except(['password', 'password_confirmation'])), // Avoid logging sensitive data
        ]);

        return $response; // Return the original response
    }
}
