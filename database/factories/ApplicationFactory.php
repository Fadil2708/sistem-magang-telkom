<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'intern_id' => User::factory()->intern(),
            'vacancy_id' => Vacancy::factory(),
            'status' => 'submitted',
            'applied_at' => now(),
        ];
    }

    public function submitted(): static
    {
        return $this->state(fn() => ['status' => 'submitted']);
    }

    public function underReview(): static
    {
        return $this->state(fn() => ['status' => 'under_review']);
    }

    public function interviewScheduled(): static
    {
        return $this->state(fn() => [
            'status' => 'interview_scheduled',
            'interview_date' => now()->addDays(3),
        ]);
    }

    public function accepted(): static
    {
        return $this->state(fn() => ['status' => 'accepted']);
    }

    public function rejected(): static
    {
        return $this->state(fn() => [
            'status' => 'rejected',
            'rejection_reason' => fake()->sentence(),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn() => ['status' => 'cancelled']);
    }
}
