<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use Illuminate\Console\Command;

class FixUserRoles extends Command
{
    protected $signature = 'fix:user-roles';
    protected $description = 'Fix user roles assignment';

    public function handle()
    {
        $roles = Role::all()->keyBy('name');

        $assignments = [
            'admin@example.com' => 'Super Admin',
            'admin2@example.com' => 'Admin',
            'supervisor@example.com' => 'Supervisor',
            'inspector@example.com' => 'Inspector',
        ];

        foreach ($assignments as $email => $roleName) {
            $user = User::where('email', $email)->first();
            if ($user && isset($roles[$roleName])) {
                $user->update(['role_id' => $roles[$roleName]->id]);
                $this->info("✓ Updated {$email} with role {$roleName}");
            } else {
                if (!$user) {
                    $this->error("✗ User {$email} not found");
                } else {
                    $this->error("✗ Role {$roleName} not found");
                }
            }
        }

        $this->line("\n=== Users and their roles ===");
        $users = User::with('role')->get();
        foreach ($users as $user) {
            $role = $user->role?->name ?? 'NO ROLE';
            $this->line("{$user->email} => {$role}");
        }
    }
}
