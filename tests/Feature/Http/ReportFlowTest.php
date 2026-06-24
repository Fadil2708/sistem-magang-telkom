<?php

namespace Tests\Feature\Http;

use App\Models\InternProfile;
use App\Models\Internship;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ReportFlowTest extends TestCase
{
    public function test_intern_can_upload_report(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create(['intern_id' => $intern->id]);

        $file = UploadedFile::fake()->create('report.pdf', 200);

        $response = $this->actingAs($intern)->post('/api/v1/internships/' . $internship->id . '/reports', [
            'title' => 'Final Report',
            'file_url' => $file,
        ]);

        $response->assertStatus(201);
    }

    public function test_intern_cannot_upload_report_without_file(): void
    {
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create(['intern_id' => $intern->id]);

        $response = $this->actingAs($intern)->postJson('/api/v1/internships/' . $internship->id . '/reports', [
            'title' => 'Final Report',
        ]);

        $response->assertStatus(422);
    }

    public function test_supervisor_can_approve_report(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        $report = \App\Models\FinalReport::factory()->pending()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        $response = $this->actingAs($supervisor)->patchJson('/api/v1/reports/' . $report->id . '/review', [
            'action' => 'approved',
        ]);

        $response->assertStatus(200);
        $this->assertEquals('approved', $report->fresh()->supervisor_approval);
    }

    public function test_supervisor_can_reject_report(): void
    {
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create(['user_id' => $intern->id]);
        $internship = Internship::factory()->active()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
        ]);
        $report = \App\Models\FinalReport::factory()->pending()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
        ]);

        $response = $this->actingAs($supervisor)->patchJson('/api/v1/reports/' . $report->id . '/review', [
            'action' => 'rejected',
        ]);

        $response->assertStatus(200);
        $this->assertEquals('rejected', $report->fresh()->supervisor_approval);
    }
}
