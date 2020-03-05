<?php

use think\permission\facade\Permission;
use think\permission\facade\Role;

if (!function_exists('collect')) {
    /**
     * 数组转换为数据集对象
     * @param array $resultSet 数据集数组
     * @return \think\model\Collection|\think\Collection
     */
    function collect($resultSet)
    {
        $item = current($resultSet);
        if ($item instanceof Model) {
            return \think\model\Collection::make($resultSet);
        } else {
            return \think\Collection::make($resultSet);
        }
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
