<?php

namespace App\Domain\Repositories;

use App\Models\Role;

interface RoleRepositoryInterface
{
    public function all(): array;

    public function find(string $id): ?Role;

    public function findByName(string $name): ?Role;

    public function create(array $data): Role;
}
