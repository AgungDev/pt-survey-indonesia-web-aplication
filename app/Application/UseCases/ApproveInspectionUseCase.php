<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\InspectionRepositoryInterface;

class ApproveInspectionUseCase
{
    public function __construct(private InspectionRepositoryInterface $inspectionRepository)
    {
    }

    public function execute(string $inspectionId, string $status, string $approverId): bool
    {
        return $this->inspectionRepository->updateStatus($inspectionId, $status, $approverId);
    }
}
