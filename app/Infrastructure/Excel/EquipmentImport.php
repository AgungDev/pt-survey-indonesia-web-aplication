<?php

namespace App\Infrastructure\Excel;

use App\Domain\Repositories\EquipmentRepositoryInterface;
use App\Domain\Repositories\ImportHistoryRepositoryInterface;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EquipmentImport implements ToCollection, WithHeadingRow
{
    public function __construct(
        private ImportHistoryRepositoryInterface $historyRepository,
        private EquipmentRepositoryInterface $equipmentRepository,
        private string $historyId,
    ) {
    }

    public function collection(Collection $rows): void
    {
        $total = 0;
        $success = 0;
        $failed = 0;

        foreach ($rows as $row) {
            $total++;

            $data = [
                'equipment_name' => trim((string) $row->get('equipment_name', '')),
                'equipment_category' => trim((string) $row->get('equipment_category', '')),
                'location' => trim((string) $row->get('location', '')),
                'unit_number' => trim((string) $row->get('unit_number', '')),
                'serial_number' => trim((string) $row->get('serial_number', '')),
                'model_type' => trim((string) $row->get('model_type', '')),
                'brand' => trim((string) $row->get('brand', '')),
                'capacity' => trim((string) $row->get('capacity', '')),
            ];

            if (empty($data['equipment_name']) || empty($data['unit_number']) || empty($data['serial_number'])) {
                $failed++;
                continue;
            }

            if ($this->equipmentRepository->findBySerial($data['serial_number']) || $this->equipmentRepository->findByUnit($data['unit_number'])) {
                $failed++;
                continue;
            }

            $this->equipmentRepository->create($data);
            $success++;
        }

        $this->historyRepository->updateStatus($this->historyId, [
            'total_rows' => $total,
            'success_rows' => $success,
            'failed_rows' => $failed,
        ]);
    }
}
