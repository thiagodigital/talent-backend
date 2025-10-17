<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvaluationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'collaborator_id' => 'required|exists:collaborators,id',
            'skills' => 'required|array',
            'skills.*.id' => 'required|exists:evaluations,id',
            'skills.*.value' => 'required|integer|min:1|max:10',
            'skills.*.type' => 'required|string',
        ];
    }
}
