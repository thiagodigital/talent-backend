<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCollaboratorRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:collaborators,email',
            'phone' => 'nullable|string|max:20',
            'address_id' => 'nullable|uuid|exists:addresses,id',
            'position_id' => 'nullable|uuid|exists:positions,id',
            'role_id' => 'required|uuid|exists:roles,id',
        ];
    }
}
