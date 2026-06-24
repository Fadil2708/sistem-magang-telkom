<?php

namespace Database\Factories;

use App\Models\Internship;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestimonialFactory extends Factory
{
    public function definition(): array
    {
        return [
            'intern_id' => User::factory()->intern(),
            'internship_id' => Internship::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'content' => fake()->paragraph(),
            'is_published' => false,
        ];
    }

    public function published(): static
    {
        return $this->state(fn() => ['is_published' => true]);
    }

    public function unpublished(): static
    {
        return $this->state(fn() => ['is_published' => false]);
    }
}
