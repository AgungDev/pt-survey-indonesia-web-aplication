<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Repositories\InspectionRepositoryInterface;
use App\Models\Inspection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InspectionRepository implements InspectionRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Inspection::with(['equipment', 'inspector', 'findings', 'photos']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['inspector_id'])) {
            $query->where('inspector_id', $filters['inspector_id']);
        }

        return $query->orderByDesc('survey_timestamp')->paginate($perPage);
    }

    public function find(string $id): ?Inspection
    {
        return Inspection::with(['equipment', 'findings', 'photos'])->find($id);
    }

    public function create(array $data): Inspection
    {
        return Inspection::create($data);
    }

    public function updateStatus(string $id, string $status, ?string $approvedBy = null): bool
    {
        $inspection = Inspection::find($id);

        if (!$inspection) {
            return false;
        }

        $inspection->status = $status;
        $inspection->approved_by = $approvedBy;
        $inspection->approved_at = now();

        return $inspection->save();
    }
}
