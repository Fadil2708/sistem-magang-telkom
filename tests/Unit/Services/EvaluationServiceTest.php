<?php

namespace Tests\Unit\Services;

use App\Models\Certificate;
use App\Models\Evaluation;
use App\Models\Internship;
use App\Models\User;
use App\Services\EvaluationService;
use Tests\TestCase;

class EvaluationServiceTest extends TestCase
{
    private EvaluationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new EvaluationService();
    }

    public function test_calculate_final_score_grade_a(): void
    {
        $evaluation = Evaluation::factory()->make([
            'soft_skill_score' => 90,
            'hard_skill_score' => 90,
            'attendance_score' => 90,
            'attitude_score' => 90,
        ]);

        $evaluation->calculateFinalScore();

        $expected = (90 * 0.25) + (90 * 0.35) + (90 * 0.20) + (90 * 0.20);
        $this->assertEquals($expected, $evaluation->final_score);
        $this->assertEquals('A', $evaluation->grade);
    }

    public function test_calculate_final_score_grade_b(): void
    {
        $evaluation = Evaluation::factory()->make([
            'soft_skill_score' => 75,
            'hard_skill_score' => 75,
            'attendance_score' => 75,
            'attitude_score' => 75,
        ]);

        $evaluation->calculateFinalScore();

        $this->assertEquals(75, $evaluation->final_score);
        $this->assertEquals('B', $evaluation->grade);
    }

    public function test_calculate_final_score_grade_c(): void
    {
        $evaluation = Evaluation::factory()->make([
            'soft_skill_score' => 60,
            'hard_skill_score' => 60,
            'attendance_score' => 60,
            'attitude_score' => 60,
        ]);

        $evaluation->calculateFinalScore();

        $this->assertEquals(60, $evaluation->final_score);
        $this->assertEquals('C', $evaluation->grade);
    }

    public function test_calculate_final_score_grade_d(): void
    {
        $evaluation = Evaluation::factory()->make([
            'soft_skill_score' => 40,
            'hard_skill_score' => 40,
            'attendance_score' => 40,
            'attitude_score' => 40,
        ]);

        $evaluation->calculateFinalScore();

        $this->assertEquals(40, $evaluation->final_score);
        $this->assertEquals('D', $evaluation->grade);
    }

    public function test_calculate_final_score_boundary_a(): void
    {
        $evaluation = Evaluation::factory()->make([
            'soft_skill_score' => 85,
            'hard_skill_score' => 85,
            'attendance_score' => 85,
            'attitude_score' => 85,
        ]);

        $evaluation->calculateFinalScore();

        $this->assertEquals(85, $evaluation->final_score);
        $this->assertEquals('A', $evaluation->grade);
    }

    public function test_calculate_final_score_boundary_b_upper(): void
    {
        $evaluation = Evaluation::factory()->make([
            'soft_skill_score' => 84.99,
            'hard_skill_score' => 84.99,
            'attendance_score' => 84.99,
            'attitude_score' => 84.99,
        ]);

        $evaluation->calculateFinalScore();

        $this->assertEquals(84.99, $evaluation->final_score);
        $this->assertEquals('B', $evaluation->grade);
    }

    public function test_is_locked_returns_true_when_evaluated_at_set(): void
    {
        $evaluation = Evaluation::factory()->locked()->create();

        $this->assertTrue($this->service->isLocked($evaluation));
    }

    public function test_is_locked_returns_true_when_certificate_exists(): void
    {
        $internship = Internship::factory()->completed()->create();
        $evaluation = Evaluation::factory()->create([
            'internship_id' => $internship->id,
            'supervisor_id' => User::factory()->supervisor(),
            'evaluated_at' => null,
        ]);
        Certificate::factory()->create(['internship_id' => $internship->id]);

        $this->assertTrue($this->service->isLocked($evaluation));
    }

    public function test_is_locked_returns_false_when_not_locked(): void
    {
        $evaluation = Evaluation::factory()->create([
            'evaluated_at' => null,
        ]);

        $this->assertFalse($this->service->isLocked($evaluation));
    }

    public function test_lock_sets_evaluated_at(): void
    {
        $evaluation = Evaluation::factory()->create([
            'evaluated_at' => null,
        ]);

        $this->service->lock($evaluation);

        $this->assertNotNull($evaluation->fresh()->evaluated_at);
    }

    public function test_lock_throws_when_already_locked(): void
    {
        $evaluation = Evaluation::factory()->locked()->create();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Penilaian sudah terkunci.');

        $this->service->lock($evaluation);
    }
}
