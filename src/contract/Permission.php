<?php

namespace think\permission\contract;

use think\model\relation\BelongsToMany;
use think\permission\exception\PermissionDoesNotExist;

interface Permission
{
    /**
     * A permission can be applied to roles.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany;

    /**
     * Find a permission by its slug.
     *
     * @param string $slug
     *
     * @throws PermissionDoesNotExist
     *
     * @return Permission
     */
    public static function findBySlug(string $slug): self;

    /**
     * Find a permission by its id.
     *
     * @param int $id
     *
     * @throws PermissionDoesNotExist
     *
     * @return Permission
     */
    public static function findById(int $id): self;
}