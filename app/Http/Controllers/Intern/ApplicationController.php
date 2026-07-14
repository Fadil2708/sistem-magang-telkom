<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Services\ApplicationService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    use ApiResponse;

    public function show(string $id): View
    {
        $application = Application::with([
            'vacancy', 'intern.internProfile', 'internship'
        ])->findOrFail($id);

        abort_if($application->intern_id !== auth()->id(), 403);

        return view('intern.applications.show', compact('application'));
    }

    public function store(Request $request, ApplicationService $service): JsonResponse
    {
        $request->validate(['vacancy_id' => 'required|exists:vacancies,id']);

        try {
            $application = $service->apply(auth()->user(), $request->vacancy_id);
            return $this->created(new ApplicationResource($application), 'Lamaran berhasil dikirim');
        } catch (\Exception $e) {
            Log::warning("[API] ApplicationController::store: {$e->getMessage()}");
            return $this->error($e->getMessage(), 422);
        }
    }

    public function myApplications(): JsonResponse
    {
        $applications = Application::where('intern_id', auth()->id())
            ->with(['vacancy', 'internship'])
            ->orderByDesc('created_at')
            ->get();

        return $this->success(ApplicationResource::collection($applications));
    }

    public function cancel(string $id, ApplicationService $service): JsonResponse
    {
        $application = Application::findOrFail($id);

        abort_if($application->intern_id !== auth()->id(), 403);

        try {
            $service->cancel($application);
            return $this->success(null, 'Lamaran berhasil dibatalkan');
        } catch (\Exception $e) {
            Log::warning("[API] ApplicationController::cancel: {$e->getMessage()}");
            return $this->error($e->getMessage(), 422);
        }
    }
}
