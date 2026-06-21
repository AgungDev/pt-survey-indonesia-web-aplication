<?php

namespace App\Domain\Repositories;

use App\Models\InspectionFinding;

interface InspectionFindingRepositoryInterface
{
    public function create(array $data): InspectionFinding;

    public function createMany(array $findings, string $inspectionId): array;
}
