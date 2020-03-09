<?php

namespace think\permission\model;

use think\Model;
use think\permission\Collection;
use think\permission\traits\HasRoles;
use think\permission\traits\RefreshesPermissionCache;
use think\permission\PermissionRegistrar;
use think\model\relation\BelongsToMany;
use think\permission\exception\PermissionDoesNotExist;
use think\permission\exception\PermissionAlreadyExists;
use think\permission\contract\Permission as PermissionContract;

class Permission extends Model implements PermissionContract
{
    use HasRoles;
    use RefreshesPermissionCache;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('permission.table_names.permissions');
    }

    public static function create(array $attributes = [])
    {
        $permission = static::getPermissions(['slug' => $attributes['slug']])->first();

        if ($permission) {
            throw PermissionAlreadyExists::create($attributes['slug']);
        }

        return static::query()->create($attributes);
    }

    /**
     * A permission can be applied to roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.role'),
            config('permission.table_names.role_has_permissions'),
            'permission_id',
            'role_id'
        );
    }

    /**
     * A permission belongs to some users of the model associated with its guard.
     */
    public function users(): belongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.user'),
            config('permission.table_names.user_has_permissions'),
            'permission_id',
            'user_id'
        );
    }

    /**
     * Find a permission by its slug.
     * @param string $slug
     * @return PermissionContract
     * @throws PermissionDoesNotExist
     */
    public static function findBySlug(string $slug): PermissionContract
    {
        $permission = static::getPermissions(['slug' => $slug])->first();
        if (! $permission) {
            throw PermissionDoesNotExist::withSlug($slug);
        }

        return $permission;
    }

    /**
     * Find a permission by its id.
     * @param int $id
     * @return PermissionContract
     * @throws PermissionDoesNotExist
     */
    public static function findById(int $id): PermissionContract
    {
        $permission = static::getPermissions(['id' => $id])->first();

        if (! $permission) {
            throw PermissionDoesNotExist::withId($id);
        }

        return $permission;
    }

    /**
     * Get the current cached permissions.
     * @param array $params
     * @return Collection
     */
    protected static function getPermissions(array $params = []): Collection
    {
        return app(PermissionRegistrar::class)
            ->setPermissionClass(static::class)
            ->getPermissions($params);
    }
}