<?php

namespace App\Application\UseCases;

use App\Application\DTOs\ImportEquipmentDTO;
use App\Domain\Repositories\ImportHistoryRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class ImportEquipmentUseCase
{
    public function __construct(
        private ImportHistoryRepositoryInterface $historyRepository,
    ) {
    }

    public function execute(ImportEquipmentDTO $dto): string
    {
        $filePath = Storage::disk('local')->putFile('imports', $dto->file);

        $history = $this->historyRepository->create([
            'filename' => $dto->file->getClientOriginalName(),
            'file_path' => $filePath,
            'total_rows' => 0,
            'success_rows' => 0,
            'failed_rows' => 0,
            'status' => 'Pending Review',
            'started_at' => now(),
            'created_by' => $dto->uploadedBy,
        ]);

        return $history->id;
    }
}
