<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;

class PermissionLevelMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission
     * @param  int  $minLevel
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission, $minLevel = 1)
    {
        if (!auth()->check()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $user = auth()->user();
        if (!$user->hasPermissionTo($permission)) {
            throw UnauthorizedException::forPermissions([$permission]);
        }

        if ($user->level < $minLevel) {
            throw UnauthorizedException::forPermissions([$permission . ' (Level ' . $minLevel . ' required, current level: ' . $user->level . ')']);
        }

        return $next($request);
    }
}
