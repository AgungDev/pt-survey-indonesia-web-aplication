<?php

namespace App\Application\UseCases;

use App\Application\DTOs\ImportEquipmentDTO;
use App\Domain\Repositories\ImportHistoryRepositoryInterface;
use App\Jobs\ImportEquipmentJob;
use Illuminate\Support\Facades\Storage;

class ImportEquipmentUseCase
{
    public function __construct(
        private ImportHistoryRepositoryInterface $historyRepository,
    ) {
    }

    public function execute(ImportEquipmentDTO $dto): string
    {
        $filePath = $dto->file->store('imports');

        $history = $this->historyRepository->create([
            'filename' => $dto->file->getClientOriginalName(),
            'total_rows' => 0,
            'success_rows' => 0,
            'failed_rows' => 0,
            'status' => 'Pending',
            'started_at' => now(),
            'created_by' => $dto->uploadedBy,
        ]);

        ImportEquipmentJob::dispatch($history->id, $filePath);

        return $history->id;
    }
}
