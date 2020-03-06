<?php

use think\permission\Collection;
use think\permission\facade\Permission;
use think\permission\facade\Role;

if (!function_exists('collect')) {
    /**
     * Create a collection from the given value.
     *
     * @param mixed $value
     * @return Collection
     */
    function collect($value = null)
    {
        return new Collection($value);
    }
}

/**
 * 是否有权限
 *
 * @param $permission => controller@action
 * @return bool
 */
if (!function_exists('can')) {
	function can($permission)
	{
		$module = request()->module();
		list($controller, $action) = explode('@', $permission);
		$user = request()->session(config('permission.user'));
		$roleIDs = $user->getRoles(false);
		$permission = Permission::getPermissionByModuleAnd($module, $controller, $action);
		if (!$permission) {
			return false;
		}
		$permissions = [];
		foreach ($roleIDs as $role) {
			$permissions = array_merge($permissions, (Role::getRoleBy($role)->getPermissions(false)));
		}
		if (!in_array($permission->id, $permissions)) {
			return false;
		}
		return true;
	}
}
