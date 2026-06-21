<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Repositories\RoleRepositoryInterface;
use App\Models\Role;

class RoleRepository implements RoleRepositoryInterface
{
    public function all(): array
    {
        return Role::orderBy('name')->get()->all();
    }

    public function find(string $id): ?Role
    {
        return Role::find($id);
    }

    public function findByName(string $name): ?Role
    {
        return Role::where('name', $name)->first();
    }

    public function create(array $data): Role
    {
        return Role::create($data);
    }
}
