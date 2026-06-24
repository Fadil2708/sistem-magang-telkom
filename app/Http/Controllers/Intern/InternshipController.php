<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Http\Resources\InternshipResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class InternshipController extends Controller
{
    use ApiResponse;

    public function myInternship(): JsonResponse
    {
        $internships = request()->user()
            ->internships()
            ->with([
                'vacancy',
                'supervisor.supervisorProfile',
                'evaluation',
                'certificate',
                'logbooks' => fn($q) => $q->latest()->take(5),
            ])
            ->latest()
            ->paginate(15);

        return $this->success(
            InternshipResource::collection($internships),
            meta: [
                'current_page' => $internships->currentPage(),
                'total' => $internships->total(),
            ]
        );
    }
}
