<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\ImportHistoryRepositoryInterface;
use App\Jobs\ImportEquipmentJob;

class ApproveImportUseCase
{
    public function __construct(private ImportHistoryRepositoryInterface $historyRepository)
    {
    }

    public function execute(string $historyId, string $approverId, string $industryId, string $companyId, ?string $comment = null): bool
    {
        $history = $this->historyRepository->find($historyId);

        if (!$history || $history->status !== 'Pending Review' || !$history->file_path) {
            return false;
        }

        $success = $this->historyRepository->updateStatus($historyId, [
            'status' => 'Approved',
            'approved_by' => $approverId,
            'approved_at' => now(),
            'industry_id' => $industryId,
            'company_id' => $companyId,
            'review_comment' => $comment,
            'reviewed_at' => now(),
        ]);

        if (!$success) {
            return false;
        }

        ImportEquipmentJob::dispatch($historyId, $history->file_path);

        return true;
    }
}
