<?php

namespace App\Presentation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InspectionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'survey_timestamp' => $this->survey_timestamp,
            'equipment' => [
                'id' => $this->equipment->id,
                'name' => $this->equipment->equipment_name,
            ],
            'inspector' => [
                'id' => $this->inspector->id,
                'name' => $this->inspector->name,
            ],
            'status' => $this->status,
            'recommendation' => $this->recommendation,
            'findings' => $this->findings->map(fn($finding) => [
                'number' => $finding->finding_number,
                'description' => $finding->finding_description,
            ])->all(),
        ];
    }
}
