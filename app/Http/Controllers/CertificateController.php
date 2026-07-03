<?php

namespace App\Http\Controllers;

use App\Http\Resources\CertificateResource;
use App\Models\Certificate;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class CertificateController extends Controller
{
    use ApiResponse;

    public function show(string $internshipId): JsonResponse
    {
        $certificate = Certificate::where('internship_id', $internshipId)
            ->with(['issuedBy', 'intern', 'internship'])
            ->firstOrFail();

        Gate::authorize('view', $certificate);

        return $this->success(new CertificateResource($certificate));
    }
}
