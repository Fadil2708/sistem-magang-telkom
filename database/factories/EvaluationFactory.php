<?php

namespace Database\Factories;

use App\Models\Internship;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EvaluationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'internship_id' => Internship::factory(),
            'supervisor_id' => User::factory()->supervisor(),
            'soft_skill_score' => fake()->randomFloat(2, 60, 100),
            'hard_skill_score' => fake()->randomFloat(2, 60, 100),
            'attendance_score' => fake()->randomFloat(2, 60, 100),
            'attitude_score' => fake()->randomFloat(2, 60, 100),
            'remarks' => fake()->paragraph(),
        ];
    }

    public function withGrade(string $grade): static
    {
        $scores = match ($grade) {
            'A' => ['soft_skill_score' => 90, 'hard_skill_score' => 90, 'attendance_score' => 90, 'attitude_score' => 90],
            'B' => ['soft_skill_score' => 75, 'hard_skill_score' => 75, 'attendance_score' => 75, 'attitude_score' => 75],
            'C' => ['soft_skill_score' => 60, 'hard_skill_score' => 60, 'attendance_score' => 60, 'attitude_score' => 60],
            'D' => ['soft_skill_score' => 40, 'hard_skill_score' => 40, 'attendance_score' => 40, 'attitude_score' => 40],
            default => [],
        };

        return $this->state(fn() => $scores);
    }

    public function locked(): static
    {
        return $this->state(fn() => [
            'final_score' => fake()->randomFloat(2, 55, 100),
            'grade' => fake()->randomElement(['A', 'B', 'C', 'D']),
            'evaluated_at' => now(),
        ]);
    }
}
