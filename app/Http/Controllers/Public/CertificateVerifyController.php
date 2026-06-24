<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class CertificateVerifyController extends Controller
{
    use ApiResponse;

    public function verify(string $token): JsonResponse
    {
        $certificate = Certificate::where('qr_code_token', $token)
            ->with([
                'intern.internProfile',
                'internship.vacancy',
            ])
            ->firstOrFail();

        $profile = $certificate->intern?->internProfile;

        return $this->success([
            'nama' => $profile?->full_name,
            'institusi' => $profile?->institution_name,
            'posisi' => $certificate->internship?->vacancy?->title,
            'periode_mulai' => $certificate->internship?->actual_start_date?->format('Y-m-d'),
            'periode_selesai' => $certificate->internship?->actual_end_date?->format('Y-m-d'),
            'nilai_akhir' => $certificate->final_score,
            'grade' => $certificate->grade,
            'nomor_sertifikat' => $certificate->certificate_number,
            'diterbitkan' => $certificate->issued_at?->format('Y-m-d'),
        ]);
    }
}
