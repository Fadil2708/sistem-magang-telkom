<?php

namespace Database\Factories;

use App\Models\Internship;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LogbookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'internship_id' => Internship::factory(),
            'intern_id' => User::factory()->intern(),
            'activity_date' => fake()->date(),
            'activities' => fake()->paragraph(),
            'output' => fake()->sentence(),
            'validation_status' => 'draft',
        ];
    }

    public function draft(): static
    {
        return $this->state(fn() => ['validation_status' => 'draft']);
    }

    public function submitted(): static
    {
        return $this->state(fn() => ['validation_status' => 'submitted']);
    }

    public function approved(): static
    {
        return $this->state(fn() => [
            'validation_status' => 'approved',
            'reviewed_at' => now(),
        ]);
    }

    public function revisionRequested(): static
    {
        return $this->state(fn() => [
            'validation_status' => 'revision_requested',
            'supervisor_notes' => fake()->sentence(),
            'reviewed_at' => now(),
        ]);
    }
}
