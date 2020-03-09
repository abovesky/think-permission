<?php

namespace think\permission\model;

use think\Model;
use think\model\relation\BelongsToMany;
use think\permission\contract\Role as RoleContract;
use think\permission\traits\HasPermissions;
use think\permission\traits\RefreshesPermissionCache;
use think\permission\exception\RoleDoesNotExist;
use think\permission\exception\RoleAlreadyExists;

class Role extends Model implements RoleContract
{
    use HasPermissions;
    use RefreshesPermissionCache;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('permission.table_names.roles');
    }

    public static function create(array $attributes = [])
    {
        if (static::where('slug', $attributes['slug'])->find()) {
            throw RoleAlreadyExists::create($attributes['slug']);
        }

        return static::query()->create($attributes);
    }

    /**
     * A role may be given various permissions.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.permission'),
            config('permission.table_names.role_has_permissions'),
            'role_id',
            'permission_id'
        );
    }

    /**
     * A role belongs to some users of the model associated with its guard.
     */
    public function users(): belongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.user'),
            config('permission.table_names.role_has_user'),
            'role_id',
            'user_id'
        );
    }

    /**
     * Find a role by its slug
     *
     * @param string $slug
     *
     * @return RoleContract|\think\permission\model\Role
     *
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function findBySlug(string $slug): RoleContract
    {
        $role = static::where('slug', $slug)->find();

        if (!$role) {
            throw RoleDoesNotExist::withSlug($slug);
        }

        return $role;
    }

    public static function findById(int $id): RoleContract
    {
        $role = static::where('id', $id)->find();

        if (!$role) {
            throw RoleDoesNotExist::withId($id);
        }

        return $role;
    }

    /**
     * Determine if the user may perform the given permission.
     *
     * @param string|Permission $permission
     *
     * @return bool
     *
     */
    public function hasPermissionTo($permission): bool
    {
        $permissionClass = $this->getPermissionClass();

        if (is_string($permission)) {
            $permission = $permissionClass->findBySlug($permission);
        }

        if (is_int($permission)) {
            $permission = $permissionClass->findById($permission);
        }

        return $this->permissions->contains('id', $permission->id);
    }
}