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
            
            // Check if user exists
            $user = User::where('email', $data['email'])->first();
            
            if ($user) {
                // Update existing user with role
                $user->update([
                    'role_id' => $role?->id,
                ]);
            } else {
                // Create new user with role
                User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => bcrypt('password'),
                    'role_id' => $role?->id,
                ]);
            }
        }
    }
}
