<?php

namespace App\Domain\Repositories;

use App\Models\InspectionPhoto;

interface InspectionPhotoRepositoryInterface
{
    public function create(array $data): InspectionPhoto;
}
