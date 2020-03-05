<?php

namespace think\permission\exception;

use InvalidArgumentException;

class RoleAlreadyExists extends InvalidArgumentException
{
    public static function create(string $roleSlug)
    {
        return new static("A role `{$roleSlug}` already exists.");
    }
}