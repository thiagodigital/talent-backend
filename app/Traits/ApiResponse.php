<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function successResponse($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'status'  => 'ok',
            'data'    => $data,
        ], $code);
    }

    protected function errorResponse($message = 'Something went wrong', $errors = null, int $code = 400): JsonResponse
    {
        $response = [
            'message' => $message,
            'status'  => 'error',
        ];

        if (!is_null($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}
