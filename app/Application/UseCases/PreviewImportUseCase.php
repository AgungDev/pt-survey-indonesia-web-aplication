<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\ImportHistoryRepositoryInterface;
use App\Infrastructure\Excel\EquipmentPreviewImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class PreviewImportUseCase
{
    public function __construct(
        private ImportHistoryRepositoryInterface $historyRepository,
        private EquipmentPreviewImport $previewImport,
    ) {
    }

    public function execute(string $historyId): array
    {
        $history = $this->historyRepository->find($historyId);
        $disk = Storage::disk('local');
        $storagePath = null;
        $errors = [];

        if ($history && $history->file_path) {
            if ($disk->exists($history->file_path)) {
                $storagePath = $disk->path($history->file_path);
            } elseif (Storage::disk('public')->exists($history->file_path)) {
                $disk = Storage::disk('public');
                $storagePath = $disk->path($history->file_path);
            } elseif (file_exists($history->file_path)) {
                $storagePath = $history->file_path;
            }
        }

        if (!$history || !$history->file_path || !$storagePath) {
            $errors[] = 'The import file is missing or unavailable.';
            if ($history && $history->file_path) {
                $errors[] = "Expected file path: {$history->file_path}";
                $errors[] = "Local checked: {$disk->path($history->file_path)}";
            }

            return [
                'history' => $history,
                'rows' => [],
                'columns' => [],
                'summary' => [
                    'total_rows' => 0,
                    'valid_rows' => 0,
                    'invalid_rows' => 0,
                ],
                'errors' => $errors,
            ];
        }

        Excel::import($this->previewImport, $storagePath);

        return [
            'history' => $history,
            'rows' => $this->previewImport->rows,
            'columns' => $this->previewImport->columns,
            'summary' => [
                'total_rows' => $this->previewImport->totalRows,
                'valid_rows' => $this->previewImport->validRows,
                'invalid_rows' => $this->previewImport->invalidRows,
            ],
            'errors' => $this->previewImport->globalErrors,
        ];
    }
}
