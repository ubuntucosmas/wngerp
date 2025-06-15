<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRoleLevel
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $role
     * @param  int  $minLevel
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $role, $minLevel)
    {
        $user = Auth::user();

        if (!$user || !$user->hasRole($role) || $user->level < (int) $minLevel) {
            abort(403, 'Unauthorized: insufficient role level.');
        }

        return $next($request);
    }
}
