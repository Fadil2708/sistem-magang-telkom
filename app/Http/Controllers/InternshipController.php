<?php

namespace App\Http\Controllers;

use App\Http\Resources\InternshipResource;
use App\Models\Internship;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class InternshipController extends Controller
{
    use ApiResponse;

    public function show(string $id): JsonResponse
    {
        $internship = Internship::with([
            'intern.internProfile',
            'supervisor.supervisorProfile',
            'vacancy',
            'application',
            'logbooks' => fn($q) => $q->latest()->take(10),
            'finalReport',
            'evaluation',
            'certificate',
        ])->findOrFail($id);

        if (auth()->user()->isSupervisor() && $internship->supervisor_id !== auth()->id()) {
            return $this->error('Anda tidak berhak mengakses data ini.', 403);
        }

        if (auth()->user()->isIntern() && $internship->intern_id !== auth()->id()) {
            return $this->error('Anda tidak berhak mengakses data ini.', 403);
        }

        return $this->success(new InternshipResource($internship));
    }
}
