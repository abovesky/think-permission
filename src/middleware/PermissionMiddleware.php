<?php

namespace think\permission;

use traits\controller\Jump;

class PermissionMiddleware
{
	use Jump;

	public function handle($request, \Closure $next, $permission)
	{
		$controller = $request->controller();
		$action     = $request->action();

		if (!can(strtolower(sprintf('%s@%s', $controller, $action)))) {
			$this->error('没有权限操作');
		}

		return $next($request);
	}
}