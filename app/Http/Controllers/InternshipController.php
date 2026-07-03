<?php

namespace App\Http\Controllers;

use App\Http\Resources\InternshipResource;
use App\Models\Internship;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

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

        Gate::authorize('view', $internship);

        return $this->success(new InternshipResource($internship));
    }
}
