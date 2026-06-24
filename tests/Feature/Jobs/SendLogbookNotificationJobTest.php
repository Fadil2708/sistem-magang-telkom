<?php

namespace Tests\Feature\Jobs;

use App\Jobs\SendLogbookNotificationJob;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendLogbookNotificationJobTest extends TestCase
{
    public function test_handle_sends_email_for_revision_requested(): void
    {
        $job = new SendLogbookNotificationJob([
            'type' => 'logbook.revision_requested',
            'recipient' => 'intern@example.com',
            'data' => ['intern_name' => 'Budi', 'activity_date' => '2026-05-24', 'supervisor_notes' => 'Perbaiki'],
        ]);
        $job->handle();

        $html = view('emails.logbook.revision-requested', [
            'intern_name' => 'Budi', 'activity_date' => '2026-05-24', 'supervisor_notes' => 'Perbaiki',
        ])->render();
        $this->assertStringContainsString('Budi', $html);
        $this->assertStringContainsString('Perbaiki', $html);
    }

    public function test_handle_sends_email_for_approved(): void
    {
        $job = new SendLogbookNotificationJob([
            'type' => 'logbook.approved',
            'recipient' => 'intern@example.com',
            'data' => ['intern_name' => 'Budi', 'activity_date' => '2026-05-24'],
        ]);
        $job->handle();

        $html = view('emails.logbook.approved', [
            'intern_name' => 'Budi', 'activity_date' => '2026-05-24',
        ])->render();
        $this->assertStringContainsString('Budi', $html);
    }

    public function test_handle_sends_email_for_new_submission(): void
    {
        $job = new SendLogbookNotificationJob([
            'type' => 'logbook.new_submission',
            'recipient' => 'supervisor@example.com',
            'data' => ['supervisor_name' => 'Pak Joko', 'intern_name' => 'Budi', 'activity_date' => '2026-05-24'],
        ]);
        $job->handle();

        $html = view('emails.logbook.new-submission', [
            'supervisor_name' => 'Pak Joko', 'intern_name' => 'Budi', 'activity_date' => '2026-05-24',
        ])->render();
        $this->assertStringContainsString('Pak Joko', $html);
        $this->assertStringContainsString('Budi', $html);
    }

    public function test_handle_skips_unknown_type(): void
    {
        Mail::fake();
        $job = new SendLogbookNotificationJob([
            'type' => 'unknown.type',
            'recipient' => 'intern@example.com',
            'data' => [],
        ]);
        $job->handle();

        Mail::assertNothingSent();
    }

    public function test_handle_skips_without_recipient(): void
    {
        Mail::fake();
        $job = new SendLogbookNotificationJob([
            'type' => 'logbook.approved',
            'recipient' => null,
            'data' => [],
        ]);
        $job->handle();

        Mail::assertNothingSent();
    }
}
