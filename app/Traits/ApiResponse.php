<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function success(mixed $data, string $message = 'Success', int $code = 200, mixed $meta = null): JsonResponse
    {
        $response = ['success' => true, 'message' => $message, 'data' => $data];
        if ($meta) {
            $response['meta'] = $meta;
        }
        return response()->json($response, $code);
    }

    protected function created(mixed $data, string $message = 'Created'): JsonResponse
    {
        return $this->success($data, $message, 201);
    }

    protected function error(string $message, int $code = 400, mixed $errors = null): JsonResponse
    {
        $response = ['success' => false, 'message' => $message];
        if ($errors) {
            $response['errors'] = $errors;
        }
        return response()->json($response, $code);
    }
}
