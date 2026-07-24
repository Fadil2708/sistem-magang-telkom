<?php

namespace App\Services;

use App\Models\Evaluation;
use App\Models\Internship;
use Illuminate\Pagination\LengthAwarePaginator;

class EvaluationService
{
    public function getAdminPaginatedList(string $filterGrade = ''): LengthAwarePaginator
    {
        return Evaluation::with(['intern.internProfile', 'internship.vacancy', 'supervisor.supervisorProfile'])
            ->when($filterGrade, fn($q) => $q->where('grade', $filterGrade))
            ->latest()
            ->paginate(10);
    }

    public function getSupervisorEvaluations(string $supervisorId, ?string $internshipId = null): array
    {
        $evaluation = null;
        $internship = null;

        if ($internshipId) {
            $internship = Internship::with(['intern.internProfile', 'vacancy', 'evaluation'])
                ->where('supervisor_id', $supervisorId)
                ->findOrFail($internshipId);
            $evaluation = $internship->evaluation;
        }

        $completedInternships = Internship::with(['intern.internProfile', 'vacancy', 'evaluation'])
            ->where('supervisor_id', $supervisorId)
            ->where('status', 'completed')
            ->whereDoesntHave('evaluation')
            ->get();

        return [
            'evaluation' => $evaluation,
            'internship' => $internship,
            'completedInternships' => $completedInternships,
        ];
    }

    public function calculateScore(Evaluation $evaluation): void
    {
        $finalScore = (
            ($evaluation->soft_skill_score * 0.25) +
            ($evaluation->hard_skill_score * 0.35) +
            ($evaluation->attendance_score * 0.20) +
            ($evaluation->attitude_score * 0.20)
        );

        $finalScore = round($finalScore, 2);
        $evaluation->final_score = $finalScore;

        $evaluation->grade = match (true) {
            $finalScore >= 85 => 'A',
            $finalScore >= 75 => 'B',
            $finalScore >= 60 => 'C',
            $finalScore >= 45 => 'D',
            default           => 'E',
        };

        $evaluation->evaluated_at = now();
        $evaluation->save();
    }

    public function getAdminPaginatedList(string $filterGrade = ''): LengthAwarePaginator
    {
        return Evaluation::with(['internship.intern.internProfile', 'internship.vacancy', 'supervisor.supervisorProfile'])
            ->when($filterGrade, fn($q) => $q->where('grade', $filterGrade))
            ->latest()
            ->paginate(10);
    }

    public function getOrCreateForInternship(string $internshipId, string $supervisorId): Evaluation
    {
        return Evaluation::firstOrCreate(
            ['internship_id' => $internshipId],
            ['supervisor_id' => $supervisorId]
        );
    }

    public function updateAndCalculate(string $id, array $data): Evaluation
    {
        $evaluation = Evaluation::findOrFail($id);
        $evaluation->update($data);
        $this->calculateScore($evaluation);
        return $evaluation->fresh();
    }
}