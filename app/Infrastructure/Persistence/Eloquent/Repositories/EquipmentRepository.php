<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Repositories\EquipmentRepositoryInterface;
use App\Models\Equipment;

class EquipmentRepository implements EquipmentRepositoryInterface
{
    public function all(array $filters = []): array
    {
        $query = Equipment::query();

        if (!empty($filters['category'])) {
            $query->where('equipment_category', $filters['category']);
        }

        if (!empty($filters['location'])) {
            $query->where('location', $filters['location']);
        }

        return $query->orderBy('equipment_name')->get()->all();
    }

    public function find(string $id): ?Equipment
    {
        return Equipment::find($id);
    }

    public function findBySerial(string $serialNumber): ?Equipment
    {
        return Equipment::where('serial_number', $serialNumber)->first();
    }

    public function findByUnit(string $unitNumber): ?Equipment
    {
        return Equipment::where('unit_number', $unitNumber)->first();
    }

    public function create(array $data): Equipment
    {
        return Equipment::create($data);
    }
}
