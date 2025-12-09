<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['admin', 'editor', 'writer'];

        foreach ($roles as $role) {
            $createdRole = Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);

            $permissions = $this->choosePermissionsEachRoles($createdRole);
            $createdRole->syncPermissions($permissions);
        }
    }

    /**
     * Setting up condition for role permissions.
     * 
     * @param Role $role
     */
    private function choosePermissionsEachRoles(Role $role)
    {
        return match ($role->name) {
            'admin' => Permission::all(),
            'editor' => Permission::whereNotLike('name', "%RolePermission%")->whereNotLike('name', "%User%")->get(),
            'writer' => Permission::whereLike('name', "%Post%")->get(),
        };
    }
}
