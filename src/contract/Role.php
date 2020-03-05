<?php

namespace think\permission\contract;

use think\model\relation\BelongsToMany;

interface Role
{
    /**
     * A role may be given various permissions.
     *
     * @return \think\model\relation\BelongsToMany
     */
    public function permissions(): BelongsToMany;

    /**
     * Find a role by its slug.
     *
     * @param string $slug
     *
     * @return \think\permission\contract\Role
     *
     * @throws \think\permission\exception\RoleDoesNotExist
     */
    public static function findBySlug(string $slug): self;

    /**
     * Find a role by its id.
     *
     * @param int $id
     *
     * @return \think\permission\contract\Role
     *
     * @throws \think\permission\exception\RoleDoesNotExist
     */
    public static function findById(int $id): self;

    /**
     * Determine if the user may perform the given permission.
     *
     * @param string|\think\permission\contract\Permission $permission
     *
     * @return bool
     */
    public function hasPermissionTo($permission): bool;
}