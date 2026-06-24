<?php

namespace App\Http\Controllers;

use App\Http\Resources\CertificateResource;
use App\Models\Certificate;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class CertificateController extends Controller
{
    use ApiResponse;

    public function show(string $internshipId): JsonResponse
    {
        $user = auth()->user();

        $certificate = Certificate::where('internship_id', $internshipId)
            ->with(['issuedBy', 'intern', 'internship'])
            ->firstOrFail();

        if ($user->role === 'admin') {
            // Admin can view any
        } elseif ($user->role === 'supervisor') {
            if ($certificate->internship->supervisor_id !== $user->id) {
                return $this->error('Anda tidak berhak melihat sertifikat ini.', 403);
            }
        } elseif ($user->role === 'intern') {
            if ($certificate->intern_id !== $user->id) {
                return $this->error('Anda tidak berhak melihat sertifikat ini.', 403);
            }
        } else {
            return $this->error('Unauthorized.', 403);
        }

        return $this->success(new CertificateResource($certificate));
    }
}
