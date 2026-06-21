<?php

namespace App\Application\DTOs;

use Illuminate\Http\UploadedFile;

final class ImportEquipmentDTO
{
    public function __construct(
        public UploadedFile $file,
        public string $uploadedBy,
    ) {
    }
}
