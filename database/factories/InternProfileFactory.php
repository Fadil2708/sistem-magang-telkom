<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InternProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'full_name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'date_of_birth' => fake()->date(max: '2005-01-01'),
            'institution_name' => fake()->company(),
            'institution_type' => fake()->randomElement(['university', 'vocational', 'highschool']),
            'major' => fake()->randomElement(['Computer Science', 'Information Systems', 'Software Engineering', 'Multimedia']),
            'student_id' => fake()->unique()->numerify('STU-#####'),
        ];
    }

    public function complete(): static
    {
        return $this->state(fn(array $attributes) => [
            'cv_url' => 'interns/cv/test.pdf',
            'cover_letter_url' => 'interns/cover-letter/test.pdf',
            'photo_url' => 'interns/photo/test.jpg',
        ]);
    }

    public function minimal(): static
    {
        return $this->state(fn(array $attributes) => [
            'cv_url' => null,
            'cover_letter_url' => null,
            'photo_url' => null,
            'address' => null,
            'phone' => null,
        ]);
    }
}
