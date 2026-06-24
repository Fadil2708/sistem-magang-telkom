<?php

namespace Tests\Feature\Http;

use App\Models\Evaluation;
use App\Models\Internship;
use App\Models\User;
use Tests\TestCase;

class CertificateFlowTest extends TestCase
{
    public function test_admin_can_issue_certificate(): void
    {
        $admin = User::factory()->admin()->create();
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->completed()->create(['intern_id' => $intern->id]);
        Evaluation::factory()->create([
            'internship_id' => $internship->id,
            'supervisor_id' => User::factory()->supervisor()->create()->id,
            'evaluated_at' => now(),
        ]);
        $internship->evaluation->calculateFinalScore();
        $internship->evaluation->save();

        $response = $this->actingAs($admin)->postJson('/api/v1/internships/' . $internship->id . '/certificates');

        $response->assertStatus(201)
            ->assertJsonStructure(['success', 'data' => ['certificate_number', 'grade']]);
    }

    public function test_non_admin_cannot_issue_certificate(): void
    {
        $intern = User::factory()->intern()->create();
        $internship = Internship::factory()->completed()->create(['intern_id' => $intern->id]);

        $response = $this->actingAs($intern)->postJson('/api/v1/internships/' . $internship->id . '/certificates');

        $response->assertStatus(403);
    }

    public function test_public_verify_valid_token(): void
    {
        $certificate = \App\Models\Certificate::factory()->create();

        $response = $this->getJson('/api/v1/verify/' . $certificate->qr_code_token);

        $response->assertStatus(200)
            ->assertJsonPath('data.nomor_sertifikat', $certificate->certificate_number);
    }

    public function test_public_verify_invalid_token(): void
    {
        $response = $this->getJson('/api/v1/verify/invalid-token');

        $response->assertStatus(404);
    }
}
