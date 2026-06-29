<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CertificateResource;
use App\Jobs\GenerateCertificatePdfJob;
use App\Models\Certificate;
use App\Models\Internship;
use App\Notifications\CertificateNotification;
use App\Services\CertificateService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CertificateController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly CertificateService $certificateService
    ) {}

    public function adminIndex(Request $request): JsonResponse
    {
        $query = Certificate::with([
            'intern.internProfile',
            'issuedBy',
            'internship.vacancy',
        ]);

        if ($request->filled('grade')) {
            $query->where('grade', $request->grade);
        }

        $certificates = $query->latest()->paginate(15);

        return $this->success(
            CertificateResource::collection($certificates),
            meta: [
                'current_page' => $certificates->currentPage(),
                'total' => $certificates->total(),
            ]
        );
    }

    public function store(string $internshipId): JsonResponse
    {
        $internship = Internship::with('evaluation')->findOrFail($internshipId);

        try {
            $certificate = $this->certificateService->issue($internship, auth()->id());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }

        $certificate->intern->notify(new CertificateNotification($certificate));

        dispatch(new GenerateCertificatePdfJob($certificate));

        return $this->success(
            new CertificateResource($certificate->load(['issuedBy', 'intern', 'internship'])),
            'Sertifikat berhasil diterbitkan.',
            201
        );
    }

    public function download(string $id): BinaryFileResponse
    {
        $certificate = Certificate::findOrFail($id);

        if (!$certificate->certificate_file_url) {
            abort(404, 'File sertifikat belum tersedia.');
        }

        $path = Storage::disk('private')->path($certificate->certificate_file_url);

        if (!file_exists($path)) {
            abort(404, 'File sertifikat tidak ditemukan.');
        }

        return response()->download($path, 'sertifikat_' . str_replace('/', '-', $certificate->certificate_number) . '.pdf');
    }
}
