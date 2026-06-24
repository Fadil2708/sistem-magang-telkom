<?php

namespace App\Exceptions;

use Exception;

class IncompleteProfileException extends Exception
{
    public function __construct(string $field = '')
    {
        $message = $field
            ? "Profil belum lengkap: {$field}"
            : 'Lengkapi profil Anda terlebih dahulu.';

        parent::__construct($message, 422);
    }

    public function render(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
        ], 422);
    }
}
