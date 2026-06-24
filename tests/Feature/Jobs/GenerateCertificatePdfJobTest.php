<?php

namespace Tests\Feature\Jobs;

use App\Jobs\GenerateCertificatePdfJob;
use App\Models\Certificate;
use App\Models\Evaluation;
use App\Models\InternProfile;
use App\Models\Internship;
use App\Models\SupervisorProfile;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GenerateCertificatePdfJobTest extends TestCase
{
    public function test_handle_generates_pdf_and_updates_certificate(): void
    {
        Storage::fake('private');

        $supervisor = User::factory()->supervisor()->create();
        SupervisorProfile::factory()->create([
            'user_id' => $supervisor->id,
            'full_name' => 'Pembimbing Satu',
        ]);

        $admin = User::factory()->admin()->create();

        $intern = User::factory()->intern()->create();
        InternProfile::factory()->create([
            'user_id' => $intern->id,
            'full_name' => 'Peserta Magang',
            'institution_name' => 'Universitas Contoh',
        ]);

        $vacancy = Vacancy::factory()->create(['created_by' => $admin->id]);

        $internship = Internship::factory()->completed()->create([
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
            'vacancy_id' => $vacancy->id,
        ]);

        $evaluation = Evaluation::factory()->create([
            'internship_id' => $internship->id,
            'supervisor_id' => $supervisor->id,
        ]);
        $evaluation->calculateFinalScore();
        $evaluation->save();

        $certificate = Certificate::factory()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
            'issued_by' => $admin->id,
            'certificate_file_url' => null,
        ]);

        $job = new GenerateCertificatePdfJob($certificate);
        $job->handle();

        $this->assertNotNull($certificate->fresh()->certificate_file_url);
        Storage::disk('private')->assertExists($certificate->fresh()->certificate_file_url);
    }

    public function test_unique_id_returns_certificate_id(): void
    {
        $certificate = Certificate::factory()->create();

        $job = new GenerateCertificatePdfJob($certificate);

        $this->assertEquals($certificate->id, $job->uniqueId());
    }
}
