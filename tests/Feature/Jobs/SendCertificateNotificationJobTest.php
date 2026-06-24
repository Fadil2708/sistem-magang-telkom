<?php

namespace Tests\Feature\Jobs;

use App\Jobs\SendCertificateNotificationJob;
use App\Models\Certificate;
use App\Models\User;
use App\Models\InternProfile;
use App\Models\Internship;
use App\Models\Evaluation;
use App\Models\Vacancy;
use App\Models\Application;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendCertificateNotificationJobTest extends TestCase
{
    public function test_handle_sends_email_for_certificate_issued(): void
    {
        $certificate = $this->makeCertificate();
        $job = new SendCertificateNotificationJob($certificate);
        $job->handle();

        $internName = $certificate->intern->internProfile->full_name;
        $this->assertStringContainsString($internName, view('emails.certificate.issued', [
            'intern_name' => $internName,
            'certificate_number' => $certificate->certificate_number,
        ])->render());
    }

    public function test_handle_skips_without_recipient(): void
    {
        Mail::fake();
        $certificate = $this->makeCertificate();
        $certificate->intern->email = null;
        $certificate->intern->save();

        $job = new SendCertificateNotificationJob($certificate);
        $job->handle();

        Mail::assertNothingSent();
    }

    private function makeCertificate(): Certificate
    {
        $admin = User::factory()->admin()->create();
        $supervisor = User::factory()->supervisor()->create();
        $intern = User::factory()->intern()->create();
        InternProfile::factory()->for($intern)->create(['full_name' => 'Budi']);
        $vacancy = Vacancy::factory()->create(['created_by' => $admin->id]);
        $application = Application::factory()->create([
            'intern_id' => $intern->id,
            'vacancy_id' => $vacancy->id,
            'status' => 'accepted',
        ]);
        $internship = Internship::factory()->completed()->create([
            'application_id' => $application->id,
            'intern_id' => $intern->id,
            'supervisor_id' => $supervisor->id,
            'vacancy_id' => $vacancy->id,
        ]);
        Evaluation::factory()->withGrade('A')->locked()->create([
            'internship_id' => $internship->id,
            'supervisor_id' => $supervisor->id,
        ]);
        return Certificate::factory()->create([
            'internship_id' => $internship->id,
            'intern_id' => $intern->id,
            'issued_by' => $admin->id,
            'certificate_number' => 'CERT-001',
        ]);
    }
}
