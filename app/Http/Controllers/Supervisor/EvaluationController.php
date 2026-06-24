<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Evaluation\StoreEvaluationRequest;
use App\Http\Resources\EvaluationResource;
use App\Models\Evaluation;
use App\Models\Internship;
use App\Services\EvaluationService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class EvaluationController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly EvaluationService $evaluationService
    ) {}

    public function store(string $internshipId, StoreEvaluationRequest $request): JsonResponse
    {
        $internship = Internship::where('supervisor_id', $request->user()->id)
            ->where('status', 'completed')
            ->findOrFail($internshipId);

        if ($internship->evaluation) {
            return $this->error('Penilaian sudah ada.', 422);
        }

        $evaluation = new Evaluation([
            'id' => (string) Str::uuid(),
            'internship_id' => $internshipId,
            'supervisor_id' => $request->user()->id,
            ...$request->validated(),
        ]);

        $this->evaluationService->calculateScore($evaluation);
        $evaluation->save();

        return $this->success(
            new EvaluationResource($evaluation),
            'Penilaian berhasil dibuat.',
            201
        );
    }

    public function update(string $id, StoreEvaluationRequest $request): JsonResponse
    {
        $evaluation = Evaluation::whereHas('internship', fn($q) =>
            $q->where('supervisor_id', $request->user()->id)
        )->findOrFail($id);

        if ($evaluation->evaluated_at || $evaluation->internship->certificate) {
            return $this->error('Tidak bisa mengubah penilaian karena sudah dikunci admin atau sertifikat sudah diterbitkan.', 422);
        }

        $evaluation->update($request->validated());
        $this->evaluationService->calculateScore($evaluation);
        $evaluation->save();

        return $this->success(
            new EvaluationResource($evaluation->fresh()->load('supervisor')),
            'Penilaian berhasil diperbarui.'
        );
    }
}
