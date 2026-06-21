<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Admin', 'description' => 'Full access to all system capabilities.'],
            ['name' => 'Admin', 'description' => 'Manage master data and imports.'],
            ['name' => 'Supervisor', 'description' => 'Review and approve inspections.'],
            ['name' => 'Inspector', 'description' => 'Perform inspections and upload photos.'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}
