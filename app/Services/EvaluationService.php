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

        $evaluation->final_score = round($finalScore, 2);

        $evaluation->grade = match (true) {
            $evaluation->final_score >= 85 => 'A',
            $evaluation->final_score >= 70 => 'B',
            $evaluation->final_score >= 55 => 'C',
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
