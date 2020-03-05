<?php

namespace think\permission\exception;

use InvalidArgumentException;

class RoleDoesNotExist extends InvalidArgumentException
{
    public static function withSlug(string $roleSlug)
    {
        return new static("There is no role with slug `{$roleSlug}`.");
    }

    public static function withId(int $roleId)
    {
        return new static("There is no role with id `{$roleId}`.");
    }
}