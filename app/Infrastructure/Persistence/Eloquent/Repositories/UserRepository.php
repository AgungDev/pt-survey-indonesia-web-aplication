<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Repositories\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function all(): array
    {
        return User::with('role')->orderBy('name')->get()->all();
    }

    public function find(string $id): ?User
    {
        return User::with('role')->find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }
}
