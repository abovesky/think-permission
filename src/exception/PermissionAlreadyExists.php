<?php

namespace think\permission\exception;

use InvalidArgumentException;

class PermissionAlreadyExists extends InvalidArgumentException
{
    public static function create(string $permissionSlug)
    {
        return new static("A `{$permissionSlug}` permission already exists.");
    }
}