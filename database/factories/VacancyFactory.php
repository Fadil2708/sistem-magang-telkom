<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VacancyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'created_by' => User::factory(),
            'title' => fake()->jobTitle(),
            'division' => fake()->randomElement(['IT', 'HR', 'Finance', 'Marketing', 'Operations']),
            'description' => fake()->paragraphs(3, true),
            'qualifications' => fake()->paragraphs(2, true),
            'quota' => fake()->numberBetween(1, 10),
            'start_date' => fake()->dateTimeBetween('+1 week', '+2 weeks')->format('Y-m-d'),
            'end_date' => fake()->dateTimeBetween('+3 months', '+6 months')->format('Y-m-d'),
            'application_deadline' => fake()->dateTimeBetween('+1 week', '+1 month')->format('Y-m-d'),
            'status' => 'open',
        ];
    }

    public function open(): static
    {
        return $this->state(fn(array $attributes) => ['status' => 'open']);
    }

    public function closed(): static
    {
        return $this->state(fn(array $attributes) => ['status' => 'closed']);
    }

    public function draft(): static
    {
        return $this->state(fn(array $attributes) => ['status' => 'draft']);
    }
}
