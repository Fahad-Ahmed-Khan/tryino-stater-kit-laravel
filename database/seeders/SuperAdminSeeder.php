<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@tryinotech.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password123'), // 🔐 change in production!
            ]
        );

        $role = Role::firstOrCreate(['name' => 'Super Admin']);
        $user->assignRole($role);
    }
}
