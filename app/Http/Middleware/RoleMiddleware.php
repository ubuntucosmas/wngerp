<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (Auth::check()) {
            $user = Auth::user();
            Log::info('User Role Check', ['user_id' => $user->id, 'user_roles' => $user->getRoleNames(), 'required_roles' => $roles]);
            if ($user->hasAnyRole($roles)) {
                return $next($request);
            }
        }

        abort(403, 'User does not have the right roles.');
    }
}