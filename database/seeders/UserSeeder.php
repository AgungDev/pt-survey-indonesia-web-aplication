<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Role::all()->keyBy('name');

        $users = [
            'Super Admin' => ['name' => 'Super Admin', 'email' => 'admin@example.com'],
            'Admin' => ['name' => 'Admin User', 'email' => 'admin2@example.com'],
            'Supervisor' => ['name' => 'Supervisor User', 'email' => 'supervisor@example.com'],
            'Inspector' => ['name' => 'Inspector User', 'email' => 'inspector@example.com'],
        ];

        foreach ($users as $roleName => $data) {
            $role = $roles->get($roleName);
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => bcrypt('password'),
                    'role_id' => $role?->id,
                ]
            );
        }
    }
}
