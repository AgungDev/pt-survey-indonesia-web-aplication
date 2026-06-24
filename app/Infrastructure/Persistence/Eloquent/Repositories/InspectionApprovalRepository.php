<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Repositories\InspectionApprovalRepositoryInterface;
use App\Models\InspectionApproval;

class InspectionApprovalRepository implements InspectionApprovalRepositoryInterface
{
    public function create(array $data): InspectionApproval
    {
        return InspectionApproval::create($data);
    }
}
