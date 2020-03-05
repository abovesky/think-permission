<?php

namespace think\permission\exception;

use InvalidArgumentException;

class PermissionDoesNotExist extends InvalidArgumentException
{
    public static function withSlug(string $permissionSlug)
    {
        return new static("There is no permission with slug `{$permissionSlug}`.");
    }

    public static function withId(int $permissionId)
    {
        return new static("There is no permission with id `{$permissionId}`.");
    }
}