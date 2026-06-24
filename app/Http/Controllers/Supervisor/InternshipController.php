<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Http\Resources\InternshipResource;
use App\Models\Internship;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class InternshipController extends Controller
{
    use ApiResponse;

    public function supervised(): JsonResponse
    {
        $internships = Internship::where('supervisor_id', auth()->id())
            ->with([
                'intern.internProfile',
                'vacancy',
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
