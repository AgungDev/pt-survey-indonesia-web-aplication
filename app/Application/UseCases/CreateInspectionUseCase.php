<?php

namespace App\Application\UseCases;

use App\Application\DTOs\CreateInspectionDTO;
use App\Domain\Repositories\InspectionFindingRepositoryInterface;
use App\Domain\Repositories\InspectionPhotoRepositoryInterface;
use App\Domain\Repositories\InspectionRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class CreateInspectionUseCase
{
    public function __construct(
        private InspectionRepositoryInterface $inspectionRepository,
        private InspectionFindingRepositoryInterface $findingRepository,
        private InspectionPhotoRepositoryInterface $photoRepository,
    ) {
    }

    public function execute(CreateInspectionDTO $dto)
    {
        $unitPhotoUrl = null;

        if ($dto->unitPhoto) {
            $unitPhotoUrl = Storage::disk('public')->putFile('inspection/unit', $dto->unitPhoto);
        }

        $inspection = $this->inspectionRepository->create([
            'survey_timestamp' => $dto->surveyTimestamp,
            'equipment_id' => $dto->equipmentId,
            'inspector_id' => $dto->inspectorId,
            'inspection_type' => $dto->inspectionType,
            'inspection_result' => $dto->inspectionResult,
            'recommendation' => $dto->recommendation,
            'unit_photo' => $unitPhotoUrl,
            'status' => 'Submitted',
        ]);

        $findings = $this->findingRepository->createMany($dto->findings, $inspection->id);

        foreach ($dto->findingPhotos as $findingId => $files) {
            foreach ($files as $file) {
                if (!$file) {
                    continue;
                }

                $photoUrl = Storage::disk('public')->putFile('inspection/findings', $file);

                $this->photoRepository->create([
                    'inspection_id' => $inspection->id,
                    'finding_id' => $findingId,
                    'photo_url' => $photoUrl,
                    'photo_type' => 'FINDING',
                ]);
            }
        }

        return $inspection;
    }
}
