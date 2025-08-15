<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfileTraitRequest extends FormRequest
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
            'traits' => 'required|array|min:1',
            'traits.*.id' => 'required|exists:profile_traits,id',
            'traits.*.score' => 'required',
            'collaborator_id' => 'required|exists:collaborators,id',
        ];
    }
}
