<?php

namespace App\Presentation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ImportHistoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'filename' => $this->filename,
            'status' => $this->status,
            'total_rows' => $this->total_rows,
            'success_rows' => $this->success_rows,
            'failed_rows' => $this->failed_rows,
            'started_at' => $this->started_at,
            'finished_at' => $this->finished_at,
        ];
    }
}
