<?php

namespace App\Domain\Repositories;

use App\Models\Equipment;

interface EquipmentRepositoryInterface
{
    public function all(array $filters = []): array;

    public function find(string $id): ?Equipment;

    public function findBySerial(string $serialNumber): ?Equipment;

    public function findByUnit(string $unitNumber): ?Equipment;

    public function create(array $data): Equipment;
}
