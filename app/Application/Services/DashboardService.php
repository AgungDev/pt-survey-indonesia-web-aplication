<?php

namespace App\Application\Services;

use App\Domain\Repositories\EquipmentRepositoryInterface;
use App\Domain\Repositories\ImportHistoryRepositoryInterface;
use App\Domain\Repositories\InspectionRepositoryInterface;

class DashboardService
{
    public function __construct(
        private EquipmentRepositoryInterface $equipmentRepository,
        private InspectionRepositoryInterface $inspectionRepository,
        private ImportHistoryRepositoryInterface $historyRepository,
    ) {
    }

    public function summary(): array
    {
        return [
            'total_equipment' => count($this->equipmentRepository->all()),
            'total_inspections' => $this->inspectionRepository->paginate([], 1)->total(),
            'total_findings' => \App\Models\InspectionFinding::count(),
            'inspections_today' => \App\Models\Inspection::whereDate('survey_timestamp', today())->count(),
            'pending_approvals' => \App\Models\Inspection::where('status', 'Submitted')->count(),
            'recent_imports' => $this->historyRepository->recent(5),
        ];
    }
}
