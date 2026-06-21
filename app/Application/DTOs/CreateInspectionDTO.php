<?php

namespace App\Application\DTOs;

use Illuminate\Http\UploadedFile;

final class CreateInspectionDTO
{
    public function __construct(
        public string $surveyTimestamp,
        public string $equipmentId,
        public string $inspectorId,
        public string $inspectionType,
        public ?string $inspectionResult,
        public ?string $recommendation,
        public ?UploadedFile $unitPhoto,
        public array $findings,
        public array $findingPhotos,
    ) {
    }
}
