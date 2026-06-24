<?php

namespace App\Domain\Repositories;

use App\Models\Industry;

interface IndustryRepositoryInterface
{
    public function all(): array;

    public function find(string $id): ?Industry;

    public function create(array $data): Industry;
}
