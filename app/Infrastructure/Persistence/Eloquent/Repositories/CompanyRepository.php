<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Repositories\CompanyRepositoryInterface;
use App\Models\Company;

class CompanyRepository implements CompanyRepositoryInterface
{
    public function all(array $filters = []): array
    {
        $query = Company::with('industry');

        if (!empty($filters['industry_id'])) {
            $query->where('industry_id', $filters['industry_id']);
        }

        return $query->orderBy('name')->get()->all();
    }

    public function find(string $id): ?Company
    {
        return Company::with('industry')->find($id);
    }

    public function create(array $data): Company
    {
        return Company::create($data);
    }
}
