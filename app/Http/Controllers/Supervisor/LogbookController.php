<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Http\Resources\LogbookResource;
use App\Jobs\SendLogbookNotificationJob;
use App\Models\Logbook;
use App\Services\LogbookService;
use App\Services\NotificationService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogbookController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly LogbookService $logbookService,
        private readonly NotificationService $notificationService
    ) {}

    public function review(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'action' => 'required|in:approved,revision_requested',
            'supervisor_notes' => 'nullable|string',
        ]);

        $logbook = Logbook::findOrFail($id);

        try {
            $logbook = $this->logbookService->review(
                $logbook,
                $request->user(),
                $request->action,
                $request->supervisor_notes
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }

        dispatch(new SendLogbookNotificationJob(
            $request->action === 'approved'
                ? $this->notificationService->sendLogbookApproved($logbook)
                : $this->notificationService->sendLogbookRevisionRequested($logbook)
        ));

        $message = $request->action === 'approved'
            ? 'Logbook berhasil disetujui.'
            : 'Revisi logbook telah diminta.';

        return $this->success(
            new LogbookResource($logbook),
            $message
        );
    }
}
