<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\EvaluationResource;
use App\Models\Evaluation;
use App\Services\EvaluationService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly EvaluationService $evaluationService
    ) {}

    public function adminIndex(Request $request): JsonResponse
    {
        $query = Evaluation::with([
            'internship.intern.internProfile',
            'internship.vacancy',
            'supervisor.supervisorProfile',
        ]);

        if ($request->filled('grade')) {
            $query->where('grade', $request->grade);
        }

        $evaluations = $query->latest()->paginate(15);

        return $this->success(
            EvaluationResource::collection($evaluations),
            meta: [
                'current_page' => $evaluations->currentPage(),
                'total' => $evaluations->total(),
            ]
        );
    }

    public function lock(string $id): JsonResponse
    {
        $evaluation = Evaluation::with('internship')->findOrFail($id);

        try {
            $this->evaluationService->lock($evaluation);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }

        return $this->success(
            new EvaluationResource($evaluation->fresh()->load(['supervisor', 'internship'])),
            'Penilaian berhasil dikunci.'
        );
    }
}
