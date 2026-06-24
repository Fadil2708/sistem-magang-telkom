<?php

namespace App\Exceptions;

use Exception;

class QuotaFullException extends Exception
{
    public function __construct()
    {
        parent::__construct('Kuota lowongan sudah penuh.', 422);
    }

    public function render(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
        ], 422);
    }
}
