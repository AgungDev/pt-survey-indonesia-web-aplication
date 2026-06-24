<?php

namespace App\Domain\Repositories;

use App\Models\Company;

interface CompanyRepositoryInterface
{
    public function all(array $filters = []): array;

    public function find(string $id): ?Company;

    public function create(array $data): Company;
}
