<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupervisorProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'full_name' => fake()->name(),
            'employee_id' => fake()->unique()->numerify('EMP-#####'),
            'division' => fake()->randomElement(['IT', 'HR', 'Finance', 'Marketing', 'Operations']),
            'position' => fake()->jobTitle(),
            'phone' => fake()->phoneNumber(),
        ];
    }
}
