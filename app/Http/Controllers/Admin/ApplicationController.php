<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Notifications\ApplicationNotification;
use App\Services\ApplicationService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApplicationController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly ApplicationService $applicationService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $query = Application::with(['intern.internProfile', 'vacancy', 'internship']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('vacancy_id')) {
            $query->where('vacancy_id', $request->vacancy_id);
        }

        $applications = $query->orderBy('applied_at', 'desc')->paginate(15);

        return $this->success(
            ApplicationResource::collection($applications),
            meta: [
                'current_page' => $applications->currentPage(),
                'total' => $applications->total(),
            ]
        );
    }

    public function updateStatus(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:submitted,under_review,interview_scheduled,accepted,rejected',
            'rejection_reason' => 'required_if:status,rejected|string',
            'interview_date' => 'nullable|date',
            'admin_notes' => 'nullable|string',
        ]);

        $application = Application::findOrFail($id);

        try {
            if ($request->status === 'accepted') {
                $this->applicationService->accept($application);

                $application->refresh()->intern->notify(new ApplicationNotification($application, 'decision'));
            } elseif ($request->status === 'rejected') {
                $this->applicationService->reject($application, $request->rejection_reason);

                $application->refresh()->intern->notify(new ApplicationNotification($application, 'decision'));
            } else {
                $this->applicationService->updateStatus(
                    $application,
                    $request->status,
                    $request->rejection_reason,
                    $request->interview_date
                );

                if ($request->filled('admin_notes')) {
                    $application->update(['admin_notes' => $request->admin_notes]);
                }

                $application->refresh();

                if ($request->status === 'interview_scheduled') {
                    $application->intern->notify(new ApplicationNotification($application, 'interview_scheduled'));
                } else {
                    $application->intern->notify(new ApplicationNotification($application, 'status_updated'));
                }
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }

        return $this->success(
            new ApplicationResource($application->fresh()->load(['intern.internProfile', 'vacancy', 'internship'])),
            'Status lamaran berhasil diperbarui.'
        );
    }

    public function downloadFile(string $id, string $type): StreamedResponse
    {
        $application = Application::with('intern.internProfile')->findOrFail($id);
        $profile = $application->intern?->internProfile;

        if (!$profile) {
            abort(404, 'Profil intern tidak ditemukan.');
        }

        $field = match ($type) {
            'photo' => 'photo_url',
            'cv' => 'cv_url',
            'cover-letter' => 'cover_letter_url',
            default => null,
        };

        if (!$field || !$profile->{$field}) {
            abort(404, 'File tidak ditemukan.');
        }

        $disk = Storage::disk(config('filesystems.private_disk'));

        if (!$disk->exists($profile->{$field})) {
            abort(404, 'File tidak ditemukan di penyimpanan.');
        }

        return $disk->response($profile->{$field});
    }
}
