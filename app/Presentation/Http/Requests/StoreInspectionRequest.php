<?php

namespace App\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreInspectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'equipment_id' => ['required', 'uuid', 'exists:equipments,id'],
            'survey_timestamp' => ['required', 'date'],
            'inspection_type' => ['required', 'string', 'max:100'],
            'inspection_result' => ['nullable', 'string', 'max:2000'],
            'recommendation' => ['nullable', 'string', 'max:2000'],
            'unit_photo' => ['nullable', 'image', 'max:10240'],
            'findings' => ['required', 'array', 'min:1'],
            'findings.*.finding_description' => ['required', 'string', 'max:1000'],
            'finding_photos' => ['nullable', 'array'],
            'finding_photos.*.*' => ['nullable', 'image', 'max:10240'],
        ];

        if (! Auth::user()->hasRole('Inspector')) {
            $rules['inspector_id'] = ['required', 'uuid', 'exists:users,id'];
        }

        return $rules;
    }
}
