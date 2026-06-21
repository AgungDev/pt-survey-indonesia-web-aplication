<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Repositories\InspectionFindingRepositoryInterface;
use App\Models\InspectionFinding;

class InspectionFindingRepository implements InspectionFindingRepositoryInterface
{
    public function create(array $data): InspectionFinding
    {
        return InspectionFinding::create($data);
    }

    public function createMany(array $findings, string $inspectionId): array
    {
        $result = [];

        foreach ($findings as $index => $finding) {
            $result[] = $this->create([
                'inspection_id' => $inspectionId,
                'finding_number' => $index + 1,
                'finding_description' => $finding['finding_description'] ?? '',
            ]);
        }

        return $result;
    }
}
