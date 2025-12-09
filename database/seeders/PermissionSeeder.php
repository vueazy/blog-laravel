<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Symfony\Component\Yaml\Yaml;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = Yaml::parse(
            input: file_get_contents(database_path('seeders/yaml/ListPermission.yaml'))
        );

        $listPermissions = collect($permissions['permissions'])
            ->map(fn ($permission) 
                => array_merge($permission, ['guard_name' => 'web'])
            );

        Permission::insert($listPermissions->toArray());
    }
}
