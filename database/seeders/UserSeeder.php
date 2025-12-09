<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::all();

        foreach ($roles as $role) {
            $user = User::factory()->create([
                'name' => ucfirst($role->name),
                'email' => $role->name . '@test.com',
            ]);

            $user->assignRole($role);
        }
    }
}
