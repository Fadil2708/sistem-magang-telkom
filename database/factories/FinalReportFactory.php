<?php

namespace Database\Factories;

use App\Models\Internship;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinalReportFactory extends Factory
{
    public function definition(): array
    {
        return [
            'internship_id' => Internship::factory(),
            'intern_id' => User::factory()->intern(),
            'title' => fake()->sentence(4),
            'file_url' => 'reports/test-report.pdf',
            'file_size_kb' => fake()->numberBetween(100, 5000),
            'submitted_at' => now(),
            'supervisor_approval' => 'pending',
        ];
    }

    public function pending(): static
    {
        return $this->state(fn() => ['supervisor_approval' => 'pending']);
    }

    public function approved(): static
    {
        return $this->state(fn() => [
            'supervisor_approval' => 'approved',
            'approved_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn() => ['supervisor_approval' => 'rejected']);
    }
}
