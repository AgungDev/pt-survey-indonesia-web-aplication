<?php

namespace App\Jobs;

use App\Domain\Repositories\EquipmentRepositoryInterface;
use App\Domain\Repositories\ImportHistoryRepositoryInterface;
use App\Infrastructure\Excel\EquipmentImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ImportEquipmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $historyId,
        public string $filePath,
    ) {
    }

    public function handle(
        ImportHistoryRepositoryInterface $historyRepository,
        EquipmentRepositoryInterface $equipmentRepository,
    ): void {
        $history = $historyRepository->find($this->historyId);

        if (!$history) {
            return;
        }

        $historyRepository->updateStatus($this->historyId, [
            'status' => 'Processing',
            'started_at' => now(),
        ]);

        try {
            Excel::import(
                new EquipmentImport(
                    $historyRepository,
                    $equipmentRepository,
                    $this->historyId,
                    $history->industry_id,
                    $history->company_id
                ),
                Storage::path($this->filePath)
            );

            $historyRepository->updateStatus($this->historyId, [
                'status' => 'Completed',
                'finished_at' => now(),
            ]);
        } catch (\Throwable $exception) {
            $historyRepository->updateStatus($this->historyId, [
                'status' => 'Failed',
                'finished_at' => now(),
            ]);
        }
    }
}
