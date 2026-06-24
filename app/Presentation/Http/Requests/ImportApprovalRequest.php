<?php

namespace App\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ImportApprovalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'industry_id' => [
                'required',
                'string',
                Rule::exists('industries', 'id'),
            ],
            'company_id' => [
                'required',
                'string',
                Rule::exists('companies', 'id')->where(function ($query) {
                    $query->where('industry_id', $this->input('industry_id'));
                }),
            ],
            'comment' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
