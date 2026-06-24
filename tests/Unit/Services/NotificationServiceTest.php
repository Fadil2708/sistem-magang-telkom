<?php

namespace Tests\Unit\Services;

use App\Models\Application;
use App\Models\Certificate;
use App\Models\FinalReport;
use App\Models\Internship;
use App\Models\Logbook;
use App\Models\User;
use App\Models\Vacancy;
use App\Services\NotificationService;
use Tests\TestCase;

class NotificationServiceTest extends TestCase
{
    private NotificationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new NotificationService();
    }

    public function test_send_application_submitted(): void
    {
        $intern = User::factory()->intern()->create();
        \App\Models\InternProfile::factory()->create(['user_id' => $intern->id]);
        $application = Application::factory()->submitted()->create(['intern_id' => $intern->id]);

        $result = $this->service->sendApplicationSubmitted($application);

        $this->assertEquals('application.submitted', $result['type']);
        $this->assertEquals($application->intern->email, $result['recipient']);
        $this->assertEquals($application->intern->internProfile->full_name, $result['data']['intern_name']);
        $this->assertEquals($application->vacancy->title, $result['data']['vacancy_title']);
    }

    public function test_send_application_status_updated(): void
    {
        $application = Application::factory()->underReview()->create();

        $result = $this->service->sendApplicationStatusUpdated($application);

        $this->assertEquals('application.status_updated', $result['type']);
        $this->assertEquals($application->intern->email, $result['recipient']);
        $this->assertArrayHasKey('status', $result['data']);
        $this->assertEquals('under_review', $result['data']['status']);
    }

    public function test_send_interview_scheduled(): void
    {
        $application = Application::factory()->interviewScheduled()->create();

        $result = $this->service->sendInterviewScheduled($application);

        $this->assertEquals('application.interview_scheduled', $result['type']);
        $this->assertArrayHasKey('interview_date', $result['data']);
        $this->assertNotNull($result['data']['interview_date']);
    }

    public function test_send_application_decision_for_accepted(): void
    {
        $application = Application::factory()->accepted()->create();

        $result = $this->service->sendApplicationDecision($application);

        $this->assertEquals('application.decision', $result['type']);
        $this->assertEquals('accepted', $result['data']['status']);
    }

    public function test_send_application_decision_for_rejected(): void
    {
        $application = Application::factory()->rejected()->create();

        $result = $this->service->sendApplicationDecision($application);

        $this->assertEquals('application.decision', $result['type']);
        $this->assertEquals('rejected', $result['data']['status']);
        $this->assertNotNull($result['data']['rejection_reason']);
    }

    public function test_send_logbook_revision_requested(): void
    {
        $logbook = Logbook::factory()->revisionRequested()->create();

        $result = $this->service->sendLogbookRevisionRequested($logbook);

        $this->assertEquals('logbook.revision_requested', $result['type']);
        $this->assertEquals($logbook->intern->email, $result['recipient']);
        $this->assertArrayHasKey('supervisor_notes', $result['data']);
    }

    public function test_send_logbook_approved(): void
    {
        $logbook = Logbook::factory()->approved()->create();

        $result = $this->service->sendLogbookApproved($logbook);

        $this->assertEquals('logbook.approved', $result['type']);
        $this->assertEquals($logbook->intern->email, $result['recipient']);
    }

    public function test_send_report_rejected(): void
    {
        $intern = User::factory()->intern()->create();
        \App\Models\InternProfile::factory()->create(['user_id' => $intern->id]);
        $report = FinalReport::factory()->rejected()->create(['intern_id' => $intern->id]);

        $result = $this->service->sendReportRejected($report);

        $this->assertEquals('report.rejected', $result['type']);
        $this->assertEquals($report->intern->email, $result['recipient']);
        $this->assertEquals($report->title, $result['data']['report_title']);
    }

    public function test_send_certificate_issued(): void
    {
        $certificate = Certificate::factory()->create();

        $result = $this->service->sendCertificateIssued($certificate);

        $this->assertEquals('certificate.issued', $result['type']);
        $this->assertEquals($certificate->intern->email, $result['recipient']);
        $this->assertEquals($certificate->certificate_number, $result['data']['certificate_number']);
    }

    public function test_send_new_logbook_to_supervisor(): void
    {
        $internship = Internship::factory()->active()->create();
        $logbook = Logbook::factory()->submitted()->create([
            'internship_id' => $internship->id,
            'intern_id' => $internship->intern_id,
        ]);

        $result = $this->service->sendNewLogbookToSupervisor($logbook);

        $this->assertEquals('logbook.new_submission', $result['type']);
        $this->assertEquals($internship->supervisor->email, $result['recipient']);
        $this->assertArrayHasKey('supervisor_name', $result['data']);
    }
}
