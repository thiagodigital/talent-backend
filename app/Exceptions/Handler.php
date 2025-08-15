<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    // ... outras propriedades/métodos padrão do Handler ...

    public function register(): void
    {
        $this->renderable(function (ValidationException $e, $request) {
            $errors = $e->errors(); // ['field' => ['msg1','msg2'], ...]

            // monta o array solicitado: [{ key, value }, ...] pegando só a primeira msg por campo
            $data = collect($errors)->map(function ($messages, $field) {
                return [
                    'key' => $field,
                    'value' => $messages ?? null,
                ];
            })->values()->all();

            // monta a mensagem principal (primeira mensagem + "(and X more error)")
            $totalMessages = collect($errors)->flatten()->count();
            $firstMessage = collect($errors)->first()[0] ?? 'Validation error';
            $message = $firstMessage;
            if ($totalMessages > 1) {
                $more = $totalMessages - 1;
                $message .= " (and {$more} more error" . ($more > 1 ? 's' : '') . ")";
            }

            return response()->json([
                'message' => $message,
                'status'  => 'error',
                'data'    => $data,
            ], 422);
        });
    }
}
