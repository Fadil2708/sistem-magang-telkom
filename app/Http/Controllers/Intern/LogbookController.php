<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Http\Requests\Logbook\StoreLogbookRequest;
use App\Http\Resources\LogbookResource;
use App\Jobs\SendLogbookNotificationJob;
use App\Models\Logbook;
use App\Services\LogbookService;
use App\Services\NotificationService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class LogbookController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly LogbookService $logbookService,
        private readonly NotificationService $notificationService
    ) {}

    public function store(string $internshipId, StoreLogbookRequest $request): JsonResponse
    {
        try {
            $logbook = $this->logbookService->create(
                $internshipId,
                $request->user(),
                $request->validated()
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }

        return $this->success(
            new LogbookResource($logbook),
            'Logbook berhasil dibuat.',
            201
        );
    }

    public function update(string $id, StoreLogbookRequest $request): JsonResponse
    {
        $logbook = Logbook::where('intern_id', $request->user()->id)->findOrFail($id);

        try {
            $logbook = $this->logbookService->update(
                $logbook,
                $request->user(),
                $request->validated()
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }

        return $this->success(
            new LogbookResource($logbook),
            'Logbook berhasil diperbarui.'
        );
    }

    public function submit(string $id): JsonResponse
    {
        $logbook = Logbook::where('intern_id', auth()->id())->findOrFail($id);

        try {
            $logbook = $this->logbookService->submit($logbook, auth()->user());
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }

        dispatch(new SendLogbookNotificationJob(
            $this->notificationService->sendNewLogbookToSupervisor($logbook)
        ));

        return $this->success(
            new LogbookResource($logbook),
            'Logbook berhasil dikirim ke supervisor.'
        );
    }
}
