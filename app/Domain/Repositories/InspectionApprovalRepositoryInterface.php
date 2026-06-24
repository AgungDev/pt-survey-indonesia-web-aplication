<?php

namespace App\Domain\Repositories;

use App\Models\InspectionApproval;

interface InspectionApprovalRepositoryInterface
{
    public function create(array $data): InspectionApproval;
}
