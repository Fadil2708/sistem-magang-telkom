<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Http\Requests\FinalReport\StoreFinalReportRequest;
use App\Http\Resources\FinalReportResource;
use App\Models\Internship;
use App\Models\FinalReport;
use App\Services\FileUploadService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class FinalReportController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly FileUploadService $fileUploadService
    ) {}

    public function store(string $internshipId, StoreFinalReportRequest $request): JsonResponse
    {
        $internship = Internship::where('intern_id', $request->user()->id)
            ->where('status', 'active')
            ->findOrFail($internshipId);

        $existing = FinalReport::where('internship_id', $internshipId)->first();
        if ($existing) {
            $this->fileUploadService->delete($existing->file_url);
        }

        $fileUrl = $this->fileUploadService->uploadFinalReport(
            $request->file('file_url'),
            $internshipId
        );

        if (!$fileUrl) {
            return $this->error('Gagal mengupload file. Silakan coba lagi.', 500);
        }

        $data = [
            'id' => (string) Str::uuid(),
            'internship_id' => $internshipId,
            'intern_id' => $request->user()->id,
            'title' => $request->title,
            'file_url' => $fileUrl,
            'file_size_kb' => $request->file('file_url')->getSize() / 1024,
            'submitted_at' => now(),
            'supervisor_approval' => 'pending',
        ];

        $report = FinalReport::updateOrCreate(
            ['internship_id' => $internshipId],
            $data
        );

        return $this->success(
            new FinalReportResource($report),
            'Laporan akhir berhasil diupload.',
            201
        );
    }
}
