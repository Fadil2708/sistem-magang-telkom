<?php

namespace App\Services;

use App\Models\Evaluation;

class EvaluationService
{
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
            $finalScore >= 70 => 'B',
            $finalScore >= 55 => 'C',
            default                        => 'D',
        };
    }

    public function isLocked(Evaluation $evaluation): bool
    {
        return $evaluation->evaluated_at !== null
            || $evaluation->internship->certificate !== null;
    }

    public function lock(Evaluation $evaluation): void
    {
        if ($this->isLocked($evaluation)) {
            throw new \Exception('Penilaian sudah terkunci.');
        }

        $this->calculateScore($evaluation);
        $evaluation->evaluated_at = now();
        $evaluation->save();
    }
}
