<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Factories\Factory;

class InternshipFactory extends Factory
{
    public function definition(): array
    {
        $vacancy = Vacancy::factory()->create();

        return [
            'application_id' => Application::factory(),
            'intern_id' => User::factory()->intern(),
            'supervisor_id' => User::factory()->supervisor(),
            'vacancy_id' => $vacancy->id,
            'status' => 'active',
            'actual_start_date' => now(),
            'actual_end_date' => now()->addMonths(3),
        ];
    }

    public function active(): static
    {
        return $this->state(fn() => ['status' => 'active']);
    }

    public function completed(): static
    {
        return $this->state(fn() => [
            'status' => 'completed',
            'actual_end_date' => now()->subDay(),
        ]);
    }

    public function terminated(): static
    {
        return $this->state(fn() => [
            'status' => 'terminated',
            'actual_end_date' => now()->subDay(),
        ]);
    }
}
