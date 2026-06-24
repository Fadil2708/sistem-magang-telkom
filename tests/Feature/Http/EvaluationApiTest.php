<?php

namespace Tests\Feature\Http;

use App\Models\Evaluation;
use App\Models\Internship;
use App\Models\User;
use Tests\TestCase;

class EvaluationApiTest extends TestCase
{
    public function test_supervisor_can_create_evaluation(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->completed()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);

        $response = $this->actingAs($supervisor)->postJson('/api/v1/internships/' . $internship->id . '/evaluations', [
            'soft_skill_score' => 85,
            'hard_skill_score' => 90,
            'attendance_score' => 80,
            'attitude_score'   => 88,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true);
        $this->assertDatabaseHas('evaluations', ['internship_id' => $internship->id]);
    }

    public function test_supervisor_cannot_create_duplicate_evaluation(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->completed()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        Evaluation::factory()->create(['internship_id' => $internship->id, 'supervisor_id' => $supervisor->id]);

        $response = $this->actingAs($supervisor)->postJson('/api/v1/internships/' . $internship->id . '/evaluations', [
            'soft_skill_score' => 85,
            'hard_skill_score' => 90,
            'attendance_score' => 80,
            'attitude_score'   => 88,
        ]);

        $response->assertStatus(422);
    }

    public function test_supervisor_cannot_evaluate_non_completed_internship(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);

        $response = $this->actingAs($supervisor)->postJson('/api/v1/internships/' . $internship->id . '/evaluations', [
            'soft_skill_score' => 85,
            'hard_skill_score' => 90,
            'attendance_score' => 80,
            'attitude_score'   => 88,
        ]);

        $response->assertStatus(404);
    }

    public function test_intern_can_view_evaluation(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->completed()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        $evaluation = Evaluation::factory()->create([
            'internship_id' => $internship->id,
            'supervisor_id' => $supervisor->id,
        ]);
        $evaluation->calculateFinalScore();
        $evaluation->save();

        $response = $this->actingAs($intern)->getJson('/api/v1/internships/' . $internship->id . '/evaluations');

        $response->assertStatus(200)
            ->assertJsonPath('success', true);
    }

    public function test_supervisor_can_update_evaluation(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->completed()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        $evaluation = Evaluation::factory()->create([
            'internship_id' => $internship->id,
            'supervisor_id' => $supervisor->id,
        ]);

        $response = $this->actingAs($supervisor)->putJson('/api/v1/evaluations/' . $evaluation->id, [
            'soft_skill_score' => 95,
            'hard_skill_score' => 95,
            'attendance_score' => 95,
            'attitude_score'   => 95,
        ]);

        $response->assertStatus(200);
    }

    public function test_other_supervisor_cannot_update_evaluation(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $otherSupervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->completed()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        $evaluation = Evaluation::factory()->create([
            'internship_id' => $internship->id,
            'supervisor_id' => $supervisor->id,
        ]);

        $response = $this->actingAs($otherSupervisor)->putJson('/api/v1/evaluations/' . $evaluation->id, [
            'soft_skill_score' => 95,
            'hard_skill_score' => 95,
            'attendance_score' => 95,
            'attitude_score'   => 95,
        ]);

        $response->assertStatus(404);
    }
}
