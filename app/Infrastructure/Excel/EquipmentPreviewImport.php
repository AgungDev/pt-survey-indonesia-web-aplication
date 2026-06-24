<?php

namespace App\Infrastructure\Excel;

use App\Domain\Repositories\EquipmentRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EquipmentPreviewImport implements ToCollection, WithHeadingRow
{
    public array $rows = [];
    public array $columns = [];
    public array $globalErrors = [];
    public int $totalRows = 0;
    public int $validRows = 0;
    public int $invalidRows = 0;

    private array $seenSerials = [];
    private array $seenUnits = [];

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
        'location' => ['location', 'lokasi', 'lokasi_unit'],
        'unit_number' => ['unit_number', 'nomor_unit', 'unit no', 'unit', 'no_unit'],
        'serial_number' => ['serial_number', 'nomor_seri', 'serial', 'no_serial', 'serial no', 'no_serino_item'],
        'model_type' => ['model_type', 'tipe_model', 'model', 'type'],
        'brand' => ['brand', 'merek', 'merk', 'merek_unit'],
        'capacity' => ['capacity', 'kapasitas'],
        'email_address' => ['email_address', 'email', 'user_email', 'email user', 'email_user'],
        'jenis_pemeriksaan' => ['jenis_pemeriksaan', 'inspection_type', 'jenis pemeriksaan'],
        'hasil_pemeriksaan' => ['hasil_pemeriksaan', 'inspection_result', 'hasil pemeriksaan'],
        'foto_unit' => ['foto_unit', 'photo_unit', 'unit_photo', 'foto_unit'],
        'temuan' => ['temuan', 'findings', 'temuan_unit', 'temuan_alat'],
    ];

    public function __construct(
        private EquipmentRepositoryInterface $equipmentRepository,
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function collection(Collection $rows): void
    {
        $this->columns = $rows->first()?->keys()->map(fn ($key) => (string) $key)->all() ?? [];

        foreach ($rows as $index => $row) {
            $this->totalRows++;

            $createdByEmail = trim((string) $this->getValue($row, 'email_address'));
            $createdByUser = null;
            $emailError = null;

            if ($createdByEmail === '') {
                $emailError = 'Email address is required.';
            } elseif (!filter_var($createdByEmail, FILTER_VALIDATE_EMAIL)) {
                $emailError = 'Email address is invalid.';
            } else {
                $createdByUser = $this->userRepository->findByEmail($createdByEmail);
                if (!$createdByUser) {
                    $emailError = "User email '{$createdByEmail}' not found.";
                }
            }

            // industry/company removed from preview per request

            $userIdFromFile = $this->getValue($row, 'user_id');
            $userNameFromFile = $this->getValue($row, 'user_name');
            $user = [
                'id' => $createdByUser?->id ?: ($userIdFromFile !== '' ? $userIdFromFile : null),
                'email' => $createdByEmail,
                'name' => $createdByUser?->name ?: $userNameFromFile,
            ];

            $inspection = [
                'equipment_name' => trim((string) $this->getValue($row, 'equipment_name')),
                'equipment_category' => trim((string) $this->getValue($row, 'equipment_category')),
                'equipment_category_id' => $this->getValue($row, 'equipment_category_id') ?: null,
                'location' => trim((string) $this->getValue($row, 'location')),
                'unit_number' => trim((string) $this->getValue($row, 'unit_number')),
                'serial_number' => trim((string) $this->getValue($row, 'serial_number')),
                'model_type' => trim((string) $this->getValue($row, 'model_type')),
                'brand' => trim((string) $this->getValue($row, 'brand')),
                'capacity' => trim((string) $this->getValue($row, 'capacity')),
                'jenis_pemeriksaan' => trim((string) $this->getValue($row, 'jenis_pemeriksaan')),
                'hasil_pemeriksaan' => trim((string) $this->getValue($row, 'hasil_pemeriksaan')),
                'foto_unit' => trim((string) $this->getValue($row, 'foto_unit')),
                'timestamp' => now()->toDateTimeString(),
            ];

            // parse temuan/findings into array
            $rawFindings = trim((string) $this->getValue($row, 'temuan'));
            $findings = [];
            if ($rawFindings !== '' && !in_array(strtolower($rawFindings), ['n/a', '-', 'na'])) {
                if (preg_match('/\d+\./', $rawFindings)) {
                    $parts = preg_split('/\s*\d+\.\s*/', $rawFindings);
                    foreach ($parts as $p) {
                        $p = trim($p);
                        if ($p !== '') {
                            $findings[] = $p;
                        }
                    }
                } else {
                    if (strpos($rawFindings, "\n") !== false) {
                        $parts = preg_split("/\r\n|\n|\r/", $rawFindings);
                    } else {
                        $parts = preg_split('/\s*[,;]\s*/', $rawFindings);
                    }

                    foreach ($parts as $p) {
                        $p = trim($p);
                        if ($p !== '' && !in_array(strtolower($p), ['n/a', '-', 'na'])) {
                            $findings[] = $p;
                        }
                    }
                }
            }

            $inspection['temuan'] = $findings;

            $errors = [];

            if ($inspection['equipment_name'] === '') {
                $errors[] = 'Equipment name is required.';
            }
            if ($inspection['unit_number'] === '') {
                $errors[] = 'Unit number is required.';
            }
            if ($inspection['serial_number'] === '') {
                $errors[] = 'Serial number is required.';
            }

            if ($inspection['serial_number'] !== '' && isset($this->seenSerials[$inspection['serial_number']])) {
                $errors[] = 'Duplicate serial number in file.';
            }
            if ($inspection['unit_number'] !== '' && isset($this->seenUnits[$inspection['unit_number']])) {
                $errors[] = 'Duplicate unit number in file.';
            }

            if ($inspection['serial_number'] !== '' && $this->equipmentRepository->findBySerial($inspection['serial_number'])) {
                $errors[] = 'Serial number already exists in system.';
            }
            if ($inspection['unit_number'] !== '' && $this->equipmentRepository->findByUnit($inspection['unit_number'])) {
                $errors[] = 'Unit number already exists in system.';
            }
            if ($emailError !== null) {
                $errors[] = $emailError;
            }

            if ($inspection['serial_number'] !== '') {
                $this->seenSerials[$inspection['serial_number']] = true;
            }
            if ($inspection['unit_number'] !== '') {
                $this->seenUnits[$inspection['unit_number']] = true;
            }

            $isValid = count($errors) === 0;
            if ($isValid) {
                $this->validRows++;
            } else {
                $this->invalidRows++;
            }

            $this->rows[] = [
                'row_number' => $index + 2,
                'data' => [
                    'user' => $user,
                    'inspections' => [$inspection],
                ],
                'errors' => $errors,
                'valid' => $isValid,
            ];
        }

        if ($this->totalRows === 0) {
            $this->globalErrors[] = 'The import file contains no rows.';
        }
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
