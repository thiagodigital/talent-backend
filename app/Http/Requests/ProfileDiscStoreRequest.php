<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileDiscStoreRequest extends FormRequest
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
            'traits' => 'required|array',
            'traits.*.id' => 'required|exists:profile_traits,id',
            'traits.*.score' => 'required|integer|min:0|max:100',
        ];
    }
}
