<?php

namespace think\permission\contract;

use think\model\relation\BelongsToMany;

interface Permission
{
    /**
     * A permission can be applied to roles.
     *
     * @return \think\model\relation\BelongsToMany
     */
    public function roles(): BelongsToMany;

    /**
     * Find a permission by its slug.
     *
     * @param string $slug
     *
     * @throws \think\permission\exception\PermissionDoesNotExist
     *
     * @return Permission
     */
    public static function findBySlug(string $slug): self;

    /**
     * Find a permission by its id.
     *
     * @param int $id
     *
     * @throws \think\permission\exception\PermissionDoesNotExist
     *
     * @return Permission
     */
    public static function findById(int $id): self;
}