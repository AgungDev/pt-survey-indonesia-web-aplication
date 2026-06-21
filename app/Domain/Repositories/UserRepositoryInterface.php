<?php

namespace App\Domain\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function all(): array;

    public function find(string $id): ?User;

    public function findByEmail(string $email): ?User;

    public function create(array $data): User;
}
