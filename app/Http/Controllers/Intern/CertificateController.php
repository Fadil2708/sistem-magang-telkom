<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CertificateController extends Controller
{
    use ApiResponse;

    public function download(string $id): JsonResponse|BinaryFileResponse
    {
        $certificate = Certificate::where('intern_id', auth()->id())
            ->findOrFail($id);

        if (!$certificate->certificate_file_url) {
            return $this->error('File sertifikat belum tersedia.', 404);
        }

        $path = Storage::disk('private')->path($certificate->certificate_file_url);

        if (!file_exists($path)) {
            return $this->error('File sertifikat tidak ditemukan.', 404);
        }

        return response()->download($path, 'sertifikat_' . str_replace('/', '-', $certificate->certificate_number) . '.pdf');
    }
}
