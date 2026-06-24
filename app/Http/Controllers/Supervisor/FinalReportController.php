<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Http\Resources\FinalReportResource;
use App\Jobs\SendReportNotificationJob;
use App\Models\FinalReport;
use App\Services\NotificationService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FinalReportController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly NotificationService $notificationService
    ) {}

    public function review(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'action' => 'required|in:approved,rejected',
            'notes' => 'nullable|string',
        ]);

        $report = FinalReport::whereHas('internship', fn($q) =>
            $q->where('supervisor_id', auth()->id())
        )->findOrFail($id);

        if ($report->supervisor_approval !== 'pending') {
            return $this->error('Laporan ini sudah direview.', 422);
        }

        $updateData = ['supervisor_approval' => $request->action];

        if ($request->action === 'approved') {
            $updateData['approved_at'] = now();
        }

        $report->update($updateData);

        if ($request->action === 'rejected') {
            dispatch(new SendReportNotificationJob(
                $this->notificationService->sendReportRejected($report)
            ));
        }

        $message = $request->action === 'approved'
            ? 'Laporan akhir berhasil disetujui.'
            : 'Laporan akhir ditolak.';

        return $this->success(
            new FinalReportResource($report->fresh()),
            $message
        );
    }
}
