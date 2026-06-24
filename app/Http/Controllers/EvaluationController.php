<?php

namespace App\Http\Controllers;

use App\Http\Resources\EvaluationResource;
use App\Models\Evaluation;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class EvaluationController extends Controller
{
    use ApiResponse;

    public function show(string $internshipId): JsonResponse
    {
        $user = auth()->user();

        $evaluation = Evaluation::where('internship_id', $internshipId)
            ->with('supervisor', 'internship')
            ->firstOrFail();

        if ($user->role === 'admin') {
            // Admin can view any
        } elseif ($user->role === 'supervisor') {
            if ($evaluation->internship->supervisor_id !== $user->id) {
                return $this->error('Anda tidak berhak melihat penilaian ini.', 403);
            }
        } elseif ($user->role === 'intern') {
            if ($evaluation->internship->intern_id !== $user->id) {
                return $this->error('Anda tidak berhak melihat penilaian ini.', 403);
            }
        } else {
            return $this->error('Unauthorized.', 403);
        }

        return $this->success(new EvaluationResource($evaluation));
    }
}
