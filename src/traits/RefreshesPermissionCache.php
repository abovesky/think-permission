<?php

namespace think\permission\traits;

use think\permission\PermissionRegistrar;

trait RefreshesPermissionCache
{
    public static function onAfterWrite()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public static function onAfterDelete()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}