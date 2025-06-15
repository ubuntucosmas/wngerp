<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;

class RoleLevelMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @param  int  $minLevel
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role, $minLevel = 1)
    {
        if (!auth()->check()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $user = auth()->user();
        if (!$user->hasRole($role)) {
            throw UnauthorizedException::forRoles([$role]);
        }

        if ($user->level < $minLevel) {
            throw UnauthorizedException::forRoles([$role . ' (Level ' . $minLevel . ' required, current level: ' . $user->level . ')']);
        }

        return $next($request);
    }
}
