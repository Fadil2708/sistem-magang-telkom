<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Internship\UpdateDatesRequest;
use App\Http\Resources\InternshipResource;
use App\Models\Internship;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InternshipController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $query = Internship::with([
            'intern.internProfile',
            'supervisor.supervisorProfile',
            'vacancy',
            'application',
        ]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $internships = $query->latest()->paginate(15);

        return $this->success(
            InternshipResource::collection($internships),
            meta: [
                'current_page' => $internships->currentPage(),
                'total' => $internships->total(),
            ]
        );
    }

    public function assignSupervisor(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'supervisor_id' => 'required|uuid|exists:users,id',
        ]);

        $supervisor = User::findOrFail($request->supervisor_id);

        if ($supervisor->role !== 'supervisor') {
            return $this->error('User yang dipilih bukan seorang supervisor.', 422);
        }

        if (!$supervisor->is_active) {
            return $this->error('Supervisor yang dipilih tidak aktif.', 422);
        }

        $internship = Internship::findOrFail($id);
        $internship->update(['supervisor_id' => $request->supervisor_id]);

        return $this->success(
            new InternshipResource($internship->fresh()->load([
                'intern.internProfile',
                'supervisor.supervisorProfile',
                'vacancy',
            ])),
            'Supervisor berhasil ditetapkan.'
        );
    }

    public function updateStatus(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:completed,terminated',
        ]);

        $internship = Internship::findOrFail($id);

        if ($internship->status !== 'active') {
            return $this->error('Hanya magang dengan status aktif yang bisa diubah.', 422);
        }

        $internship->update(['status' => $request->status]);

        return $this->success(
            new InternshipResource($internship->fresh()->load([
                'intern.internProfile',
                'supervisor.supervisorProfile',
                'vacancy',
            ])),
            'Status magang berhasil diperbarui.'
        );
    }

    public function updateDates(UpdateDatesRequest $request, string $id): JsonResponse
    {
        $internship = Internship::findOrFail($id);

        $internship->update($request->validated());

        return $this->success(
            new InternshipResource($internship->fresh()->load([
                'intern.internProfile',
                'supervisor.supervisorProfile',
                'vacancy',
            ])),
            'Tanggal magang berhasil diperbarui.'
        );
    }
}
