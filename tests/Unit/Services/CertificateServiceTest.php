<?php

namespace Tests\Unit\Services;

use App\Models\Certificate;
use App\Models\Evaluation;
use App\Models\Internship;
use App\Models\User;
use App\Models\Vacancy;
use App\Services\CertificateService;
use Tests\TestCase;

class CertificateServiceTest extends TestCase
{
    private CertificateService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CertificateService();
    }

    private function createEvaluationWithGrade(Internship $internship, array $scores = []): Evaluation
    {
        $evaluation = Evaluation::factory()->create(array_merge([
            'internship_id' => $internship->id,
            'supervisor_id' => User::factory()->supervisor()->create()->id,
            'soft_skill_score' => 85,
            'hard_skill_score' => 85,
            'attendance_score' => 85,
            'attitude_score' => 85,
            'evaluated_at' => now(),
        ], $scores));

        $evaluation->calculateFinalScore();
        $evaluation->save();

        return $evaluation;
    }

    public function test_issue_creates_certificate(): void
    {
        $admin = User::factory()->admin()->create();
        $vacancy = Vacancy::factory()->create();
        $internship = Internship::factory()->completed()->create(['vacancy_id' => $vacancy->id]);
        $this->createEvaluationWithGrade($internship);

        $certificate = $this->service->issue($internship, $admin->id);

        $this->assertInstanceOf(Certificate::class, $certificate);
        $this->assertEquals($internship->id, $certificate->internship_id);
        $this->assertEquals($internship->intern_id, $certificate->intern_id);
        $this->assertEquals($admin->id, $certificate->issued_by);
        $this->assertStringContainsString('CERT/TELKOM-SKB/' . now()->year, $certificate->certificate_number);
        $this->assertEquals(64, strlen($certificate->qr_code_token));
        $this->assertStringContainsString('/api/v1/verify/', $certificate->qr_code_url);
        $this->assertNotNull($certificate->issued_at);
    }

    public function test_issue_increments_certificate_number(): void
    {
        $admin = User::factory()->admin()->create();
        $internship1 = Internship::factory()->completed()->create();
        $this->createEvaluationWithGrade($internship1);
        $internship2 = Internship::factory()->completed()->create();
        $this->createEvaluationWithGrade($internship2, [
            'soft_skill_score' => 75,
            'hard_skill_score' => 75,
            'attendance_score' => 75,
            'attitude_score' => 75,
        ]);

        $cert1 = $this->service->issue($internship1, $admin->id);
        $cert2 = $this->service->issue($internship2, $admin->id);

        $this->assertStringEndsWith('001', $cert1->certificate_number);
        $this->assertStringEndsWith('002', $cert2->certificate_number);
    }

    public function test_issue_copies_evaluation_data(): void
    {
        $admin = User::factory()->admin()->create();
        $internship = Internship::factory()->completed()->create();
        $evaluation = $this->createEvaluationWithGrade($internship, [
            'soft_skill_score' => 90,
            'hard_skill_score' => 85,
            'attendance_score' => 95,
            'attitude_score' => 88,
        ]);

        $certificate = $this->service->issue($internship, $admin->id);

        $this->assertEquals($evaluation->fresh()->final_score, $certificate->final_score);
        $this->assertEquals($evaluation->fresh()->grade, $certificate->grade);
    }

    public function test_issue_uses_trait_uuid(): void
    {
        $admin = User::factory()->admin()->create();
        $internship = Internship::factory()->completed()->create();
        $this->createEvaluationWithGrade($internship);

        $certificate = $this->service->issue($internship, $admin->id);

        $this->assertNotNull($certificate->id);
        $this->assertEquals(36, strlen($certificate->id));
    }

    public function test_verify_with_valid_token(): void
    {
        $certificate = Certificate::factory()->create();

        $found = $this->service->verify($certificate->qr_code_token);

        $this->assertNotNull($found);
        $this->assertEquals($certificate->id, $found->id);
        $this->assertTrue($found->relationLoaded('intern'));
        $this->assertTrue($found->relationLoaded('internship'));
    }

    public function test_verify_with_invalid_token_returns_null(): void
    {
        $result = $this->service->verify('non-existent-token');

        $this->assertNull($result);
    }
}
