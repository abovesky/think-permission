<?php

namespace think\permission;

use Illuminate\Support\Facades\Auth;
use think\permission\exception\UnauthorizedException;

class RoleOrPermissionMiddleware
{
	public function handle($request, \Closure $next, $roleOrPermission)
	{
        if (Auth::guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $rolesOrPermissions = is_array($roleOrPermission)
            ? $roleOrPermission
            : explode('|', $roleOrPermission);

        if (! Auth::user()->hasAnyRole($rolesOrPermissions) && ! Auth::user()->hasAnyPermission($rolesOrPermissions)) {
            throw UnauthorizedException::forRolesOrPermissions($rolesOrPermissions);
        }

        return $next($request);
	}
}