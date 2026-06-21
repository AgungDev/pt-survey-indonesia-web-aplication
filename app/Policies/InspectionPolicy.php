<?php

namespace App\Policies;

use App\Models\Inspection;
use App\Models\User;

class InspectionPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role->name, ['Super Admin', 'Admin', 'Supervisor', 'Inspector'], true);
    }

    public function create(User $user): bool
    {
        return in_array($user->role->name, ['Super Admin', 'Admin', 'Inspector'], true);
    }

    public function approve(User $user, Inspection $inspection): bool
    {
        return in_array($user->role->name, ['Super Admin', 'Supervisor'], true)
            && $inspection->status === 'Submitted';
    }

    public function update(User $user, Inspection $inspection): bool
    {
        return $inspection->inspector_id === $user->id && $inspection->status === 'Draft';
    }
}
