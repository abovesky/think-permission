<?php

namespace think\permission\model;

use think\Model;
use think\Collection;
use think\permission\traits\HasRoles;
use think\permission\traits\RefreshesPermissionCache;
use think\permission\PermissionRegistrar;
use think\model\relation\BelongsToMany;
use think\model\relation\MorphToMany;
use think\permission\exception\PermissionDoesNotExist;
use think\permission\exception\PermissionAlreadyExists;
use think\permission\contract\Permission as PermissionContract;

class Permission extends Model implements PermissionContract
{
    use HasRoles;
    use RefreshesPermissionCache;

    protected $table;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('permission.table_names.permissions');
    }

    public static function create(array $attributes = [])
    {
        $permission = static::getPermissions(['slug' => $attributes['slug'], 'guard_name' => $attributes['guard_name']])->first();

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
    public function users(): MorphToMany
    {
        return $this->morphedByMany(
            getModelForGuard($this->attributes['guard_name']),
            'model',
            config('permission.table_names.model_has_permissions'),
            'permission_id',
            config('permission.column_names.model_morph_key')
        );
    }

    /**
     * Find a permission by its slug.
     *
     * @param string $slug
     *
     * @throws \Spatie\Permission\Exceptions\PermissionDoesNotExist
     *
     * @return \Spatie\Permission\Contracts\Permission
     */
    public static function findBySlug(string $slug): PermissionContract
    {
        $permission = static::getPermissions(['name' => $name, 'guard_name' => $guardName])->first();
        if (! $permission) {
            throw PermissionDoesNotExist::withSlug($slug);
        }

        return $permission;
    }

    /**
     * Find a permission by its id.
     *
     * @param int $id
     *
     * @throws \Spatie\Permission\Exceptions\PermissionDoesNotExist
     *
     * @return \Spatie\Permission\Contracts\Permission
     */
    public static function findById(int $id): PermissionContract
    {
        $permission = static::getPermissions(['id' => $id, 'guard_name' => $guardName])->first();

        if (! $permission) {
            throw PermissionDoesNotExist::withId($id);
        }

        return $permission;
    }

    /**
     * Get the current cached permissions.
     */
    protected static function getPermissions(array $params = []): Collection
    {
        return app(PermissionRegistrar::class)
            ->setPermissionClass(static::class)
            ->getPermissions($params);
    }
}