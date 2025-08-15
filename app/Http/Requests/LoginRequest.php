<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
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
            'email' => 'required|string|email|max:255|exists:users,email',
            'password' => 'required|string|min:8',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->messages();

        $data = collect($errors)->map(function ($messages, $field) {
            return [
                'key' => $field,
                'value' => $messages[0] ?? null,
            ];
        })->values();

        $totalMessages = collect($errors)->flatten()->count();
        $firstMessage = collect($errors)->first()[0] ?? 'Validation error';
        $message = $firstMessage;
        if ($totalMessages > 1) {
            $more = $totalMessages - 1;
            $message .= " (and {$more} more error" . ($more > 1 ? 's' : '') . ")";
        }

        throw new HttpResponseException(response()->json([
            'message' => $message,
            'status'  => 'error',
            'data'    => $data
        ], 422));
    }
}
