<?php

namespace Database\Factories;

use App\Models\Internship;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CertificateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'internship_id' => Internship::factory(),
            'intern_id' => User::factory()->intern(),
            'certificate_number' => 'CERT/TELKOM-SKB/' . now()->year . '/' . fake()->unique()->numerify('####'),
            'issued_by' => User::factory()->admin(),
            'final_score' => fake()->randomFloat(2, 55, 100),
            'grade' => fake()->randomElement(['A', 'B', 'C', 'D']),
            'qr_code_token' => fake()->unique()->sha256(),
            'qr_code_url' => fake()->url(),
            'issued_at' => now(),
        ];
    }
}
