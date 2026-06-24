<?php

namespace App\Infrastructure\Excel;

use App\Domain\Repositories\EquipmentRepositoryInterface;
use App\Domain\Repositories\ImportHistoryRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EquipmentImport implements ToCollection, WithHeadingRow
{
    private static array $columnAliases = [
        'industry_id' => ['industry_id', 'id_industri', 'industry_uid', 'id/uid_industri', 'id_industri'],
        'industry_uid' => ['industry_uid', 'uid_industri', 'industri_uid'],
        'industry_name' => ['industry_name', 'nama_industri', 'industri', 'industry_name'],
        'company_id' => ['company_id', 'id_company', 'id_perusahaan', 'company_uid', 'id/uid_perusahaan'],
        'company_uid' => ['company_uid', 'uid_company', 'perusahaan_uid'],
        'company_name' => ['company_name', 'nama_perusahaan', 'company_name', 'nama_company'],
        'user_id' => ['user_id', 'id_user', 'uid_user'],
        'user_name' => ['user_name', 'nama_user', 'nama pengguna', 'name'],
        'equipment_name' => ['equipment_name', 'nama_peralatanunit', 'nama_peralatan', 'nama_peralatan unit', 'nama_unit', 'nama alat', 'nama_alat'],
        'equipment_category' => ['equipment_category', 'kategori_alat', 'kategori alat', 'category', 'category_name'],
        'equipment_category_id' => ['equipment_category_id', 'kategori_alat_id', 'category_id'],
        'location' => ['location', 'lokasi'],
        'unit_number' => ['unit_number', 'nomor_unit', 'unit no', 'unit', 'no_unit'],
        'serial_number' => ['serial_number', 'nomor_seri', 'serial', 'no_serial', 'serial no'],
        'model_type' => ['model_type', 'tipe_model', 'model', 'type'],
        'brand' => ['brand', 'merek', 'merk'],
        'capacity' => ['capacity', 'kapasitas'],
        'email_address' => ['email_address', 'email', 'user_email', 'email user', 'email_user'],
    ];

    public function __construct(
        private ImportHistoryRepositoryInterface $historyRepository,
        private EquipmentRepositoryInterface $equipmentRepository,
        private UserRepositoryInterface $userRepository,
        private string $historyId,
        private ?string $industryId = null,
        private ?string $companyId = null,
    ) {
    }

    public function collection(Collection $rows): void
    {
        $total = 0;
        $success = 0;
        $failed = 0;

        foreach ($rows as $row) {
            $total++;

            $createdByEmail = trim((string) $this->getValue($row, 'email_address'));
            $createdByUser = null;

            if ($createdByEmail !== '') {
                $createdByUser = $this->userRepository->findByEmail($createdByEmail);
            }

            $data = [
                'equipment_name' => trim((string) $this->getValue($row, 'equipment_name')),
                'equipment_category' => trim((string) $this->getValue($row, 'equipment_category')),
                'location' => trim((string) $this->getValue($row, 'location')),
                'unit_number' => trim((string) $this->getValue($row, 'unit_number')),
                'serial_number' => trim((string) $this->getValue($row, 'serial_number')),
                'model_type' => trim((string) $this->getValue($row, 'model_type')),
                'brand' => trim((string) $this->getValue($row, 'brand')),
                'capacity' => trim((string) $this->getValue($row, 'capacity')),
                'created_by' => $createdByUser?->id,
            ];

            if ($createdByEmail === '' || !filter_var($createdByEmail, FILTER_VALIDATE_EMAIL) || !$createdByUser) {
                $failed++;
                continue;
            }

            if (empty($data['equipment_name']) || empty($data['unit_number']) || empty($data['serial_number'])) {
                $failed++;
                continue;
            }

            if ($this->equipmentRepository->findBySerial($data['serial_number']) || $this->equipmentRepository->findByUnit($data['unit_number'])) {
                $failed++;
                continue;
            }

            $this->equipmentRepository->create(array_merge($data, [
                'industry_id' => $this->industryId,
                'company_id' => $this->companyId,
            ]));
            $success++;
        }

        $this->historyRepository->updateStatus($this->historyId, [
            'total_rows' => $total,
            'success_rows' => $success,
            'failed_rows' => $failed,
        ]);
    }

    private function getValue(Collection $row, string $field): string
    {
        foreach (self::$columnAliases[$field] ?? [] as $alias) {
            if ($row->has($alias)) {
                return (string) $row->get($alias, '');
            }

            if ($row->has(strtolower($alias))) {
                return (string) $row->get(strtolower($alias), '');
            }
        }

        return '';
    }
}
