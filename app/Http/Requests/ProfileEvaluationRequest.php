<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileEvaluationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
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
