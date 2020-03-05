<?php

namespace think\permission\middleware;

use Illuminate\Support\Facades\Auth;
use think\permission\exception\UnauthorizedException;

class RoleMiddleware
{
	public function handle($request, \Closure $next, $role)
	{
        if (Auth::guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $roles = is_array($role)
            ? $role
            : explode('|', $role);

        if (! Auth::user()->hasAnyRole($roles)) {
            throw UnauthorizedException::forRoles($roles);
        }

        return $next($request);
	}
}