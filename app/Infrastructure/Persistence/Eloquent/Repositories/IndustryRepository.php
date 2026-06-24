<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Repositories\IndustryRepositoryInterface;
use App\Models\Industry;

class IndustryRepository implements IndustryRepositoryInterface
{
    public function all(): array
    {
        return Industry::orderBy('name')->get()->all();
    }

    public function find(string $id): ?Industry
    {
        return Industry::find($id);
    }

    public function create(array $data): Industry
    {
        return Industry::create($data);
    }
}
