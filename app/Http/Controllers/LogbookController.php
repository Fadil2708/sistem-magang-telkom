<?php

namespace App\Http\Controllers;

use App\Http\Resources\LogbookResource;
use App\Models\Internship;
use App\Models\Logbook;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LogbookController extends Controller
{
    use ApiResponse;

    public function index(string $internshipId): JsonResponse
    {
        $internship = Internship::findOrFail($internshipId);
        $user = Auth::user();

        if ($user->isIntern() && $internship->intern_id !== $user->id) {
            return $this->error('Anda tidak berhak mengakses data ini.', 403);
        }

        if ($user->isSupervisor() && ($internship->supervisor_id === null || $internship->supervisor_id !== $user->id)) {
            return $this->error('Anda tidak berhak mengakses data ini.', 403);
        }

        $logbooks = Logbook::where('internship_id', $internshipId)
            ->with('intern')
            ->orderBy('activity_date', 'desc')
            ->paginate(15);

        return $this->success(
            LogbookResource::collection($logbooks),
            meta: [
                'current_page' => $logbooks->currentPage(),
                'total' => $logbooks->total(),
            ]
        );
    }

    public function adminIndex(): JsonResponse
    {
        $logbooks = Logbook::with([
            'intern.internProfile',
            'internship.vacancy',
            'internship.supervisor.supervisorProfile',
        ])->latest()->paginate(15);

        return $this->success(
            LogbookResource::collection($logbooks),
            meta: [
                'current_page' => $logbooks->currentPage(),
                'total' => $logbooks->total(),
            ]
        );
    }

    public function show(string $id): JsonResponse
    {
        $logbook = Logbook::with(['intern', 'internship'])->findOrFail($id);
        $user = Auth::user();
        $internship = $logbook->internship;

        if ($user->isIntern() && $internship->intern_id !== $user->id) {
            return $this->error('Anda tidak berhak mengakses data ini.', 403);
        }

        if ($user->isSupervisor() && ($internship->supervisor_id === null || $internship->supervisor_id !== $user->id)) {
            return $this->error('Anda tidak berhak mengakses data ini.', 403);
        }

        return $this->success(new LogbookResource($logbook));
    }
}
