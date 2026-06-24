<?php

namespace App\Http\Controllers;

use App\Http\Resources\FinalReportResource;
use App\Models\FinalReport;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class FinalReportController extends Controller
{
    use ApiResponse;

    public function adminIndex(): JsonResponse
    {
        $reports = FinalReport::with([
            'internship.intern.internProfile',
            'internship.vacancy',
            'internship.supervisor.supervisorProfile',
        ])->latest()->paginate(15);

        return $this->success(
            FinalReportResource::collection($reports),
            meta: [
                'current_page' => $reports->currentPage(),
                'total' => $reports->total(),
            ]
        );
    }

    public function show(string $internshipId): JsonResponse
    {
        $user = auth()->user();

        $report = FinalReport::where('internship_id', $internshipId)
            ->with('internship')
            ->firstOrFail();

        if ($user->role === 'admin') {
            // Admin can view any
        } elseif ($user->role === 'supervisor') {
            if ($report->internship->supervisor_id === null || $report->internship->supervisor_id !== $user->id) {
                return $this->error('Anda tidak berhak melihat laporan ini.', 403);
            }
        } elseif ($user->role === 'intern') {
            if ($report->internship->intern_id !== $user->id) {
                return $this->error('Anda tidak berhak melihat laporan ini.', 403);
            }
        } else {
            return $this->error('Unauthorized.', 403);
        }

        return $this->success(new FinalReportResource($report));
    }
}
