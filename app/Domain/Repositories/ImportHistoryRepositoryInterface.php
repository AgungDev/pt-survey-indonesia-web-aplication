<?php

namespace App\Domain\Repositories;

use App\Models\ImportHistory;

interface ImportHistoryRepositoryInterface
{
    public function create(array $data): ImportHistory;

    public function find(string $id): ?ImportHistory;

    public function updateStatus(string $id, array $data): bool;

    public function recent(int $limit = 5): array;
}
