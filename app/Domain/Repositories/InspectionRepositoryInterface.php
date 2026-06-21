<?php

namespace App\Domain\Repositories;

use App\Models\Inspection;

interface InspectionRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator;

    public function find(string $id): ?Inspection;

    public function create(array $data): Inspection;

    public function updateStatus(string $id, string $status, ?string $approvedBy = null): bool;
}
