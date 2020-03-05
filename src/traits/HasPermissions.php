<?php

namespace think\permission\traits;

trait HasPermissions
{
    private $permissionClass;

    /**
     * A model may have multiple direct permissions.
     */
    public function permissions()
    {
        return $this->morphTo(
            ['model_type', config('permission.column_names.model_morph_key')],
            config('permission.table_names.model_has_permissions')
        );
    }

}