<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected static function booted()
    {
        static::creating(function ($permission) {
            $permission->guard_name = config('auth.defaults.guard');
        });
    }
}
