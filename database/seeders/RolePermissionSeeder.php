<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            'Offices' => ['view', 'create', 'edit', 'delete'],
            'Users' => ['view', 'create', 'edit', 'delete'],
            'Roles' => ['view', 'create', 'edit', 'delete'],
            'Projects' => ['view', 'create', 'edit', 'delete', 'assign'],
        ];

        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => strtolower($module) . '_' . $action,
                    'guard_name' => 'web',
                ]);
            }
        }

        $role = Role::firstOrCreate(['name' => 'Super Admin']);
        $role->syncPermissions(Permission::all());

    }
}
