<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;

class DebugController extends Controller
{
    public function fixRoles()
    {
        if (!config('app.debug')) {
            return response('Not in debug mode', 403);
        }

        $results = [];

        // First, run seeders if needed
        $results[] = "=== CHECKING ROLES ===";
        $roleCount = Role::count();
        $results[] = "Role count: {$roleCount}";

        if ($roleCount === 0) {
            $results[] = "Running RoleSeeder...";
            $seeder = new RoleSeeder();
            $seeder->run();
            $results[] = "RoleSeeder completed";
        }

        // Check roles again
        $roles = Role::all();
        $results[] = "\nRoles in database:";
        foreach ($roles as $role) {
            $results[] = "  - ID: {$role->id}, Name: {$role->name}";
        }

        // Now fix user roles
        $rolesByName = $roles->keyBy('name');

        $assignments = [
            'admin@example.com' => 'Super Admin',
            'admin2@example.com' => 'Admin',
            'supervisor@example.com' => 'Supervisor',
            'inspector@example.com' => 'Inspector',
        ];

        $results[] = "\n=== UPDATING USER ROLES ===";
        foreach ($assignments as $email => $roleName) {
            $user = User::where('email', $email)->first();
            if ($user && isset($rolesByName[$roleName])) {
                $user->update(['role_id' => $rolesByName[$roleName]->id]);
                $results[] = "✓ Updated {$email} with role {$roleName}";
            } else {
                if (!$user) {
                    $results[] = "✗ User {$email} not found";
                } else {
                    $results[] = "✗ Role '{$roleName}' not found in database";
                }
            }
        }

        // Verify results
        $results[] = "\n=== VERIFICATION ===";
        $users = User::with('role')->get();
        foreach ($users as $user) {
            $role = $user->role?->name ?? 'NO ROLE';
            $results[] = "{$user->email} => {$role}";
        }

        return response(implode("\n", $results), 200, ['Content-Type' => 'text/plain']);
    }
}
