<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Repositories\InspectionPhotoRepositoryInterface;
use App\Models\InspectionPhoto;

class InspectionPhotoRepository implements InspectionPhotoRepositoryInterface
{
    public function create(array $data): InspectionPhoto
    {
        return InspectionPhoto::create($data);
    }
}
