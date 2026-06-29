<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Http\Resources\LogbookResource;
use App\Models\Logbook;
use App\Notifications\LogbookNotification;
use App\Services\LogbookService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogbookController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly LogbookService $logbookService
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

        $logbook->intern->notify(new LogbookNotification($logbook, $request->action));

        $message = $request->action === 'approved'
            ? 'Logbook berhasil disetujui.'
            : 'Revisi logbook telah diminta.';

        return $this->success(
            new LogbookResource($logbook),
            $message
        );
    }
}
