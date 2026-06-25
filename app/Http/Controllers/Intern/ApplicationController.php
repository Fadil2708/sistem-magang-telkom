<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Http\Requests\Application\StoreApplicationRequest;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Services\ApplicationService;
use App\Services\NotificationService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ApplicationController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly ApplicationService $applicationService,
        private readonly NotificationService $notificationService
    ) {}

    public function store(StoreApplicationRequest $request): JsonResponse
    {
        try {
            $application = $this->applicationService->apply(
                $request->user(),
                $request->vacancy_id
            );

            $this->notificationService->sendEmail(
                $this->notificationService->sendApplicationSubmitted($application)
            );

            return $this->success(
                new ApplicationResource($application->load('vacancy')),
                'Lamaran berhasil dikirim.',
                201
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }

    public function myApplications(): JsonResponse
    {
        $applications = request()->user()
            ->applications()
            ->with('vacancy')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return $this->success(
            ApplicationResource::collection($applications),
            meta: [
                'current_page' => $applications->currentPage(),
                'total' => $applications->total(),
            ]
        );
    }

    public function cancel(string $id): JsonResponse
    {
        $user = request()->user();
        $application = Application::where('intern_id', $user->id)->findOrFail($id);

        try {
            $this->applicationService->cancel($application);
            return $this->success(null, 'Lamaran berhasil dibatalkan.');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }
}
