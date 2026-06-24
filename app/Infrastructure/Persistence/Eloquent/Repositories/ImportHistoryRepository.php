<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Repositories\ImportHistoryRepositoryInterface;
use App\Models\ImportHistory;

class ImportHistoryRepository implements ImportHistoryRepositoryInterface
{
    public function create(array $data): ImportHistory
    {
        return ImportHistory::create($data);
    }

    public function find(string $id): ?ImportHistory
    {
        return ImportHistory::find($id);
    }

    public function updateStatus(string $id, array $data): bool
    {
        $record = ImportHistory::find($id);

        if (!$record) {
            return false;
        }

        $record->update($data);

        return true;
    }

    public function recent(int $limit = 5): array
    {
        return ImportHistory::orderByDesc('started_at')->limit($limit)->get()->all();
    }
}
