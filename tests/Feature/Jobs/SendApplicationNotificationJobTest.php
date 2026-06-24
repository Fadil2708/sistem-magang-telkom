<?php

namespace Tests\Feature\Jobs;

use App\Jobs\SendApplicationNotificationJob;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendApplicationNotificationJobTest extends TestCase
{
    public function test_handle_sends_email_for_submitted(): void
    {
        $job = new SendApplicationNotificationJob([
            'type' => 'application.submitted',
            'recipient' => 'intern@example.com',
            'data' => ['intern_name' => 'Budi', 'vacancy_title' => 'IT Support'],
        ]);
        $job->handle();

        $html = view('emails.application.submitted', [
            'intern_name' => 'Budi', 'vacancy_title' => 'IT Support',
        ])->render();
        $this->assertStringContainsString('Budi', $html);
        $this->assertStringContainsString('IT Support', $html);
    }

    public function test_handle_sends_email_for_status_updated(): void
    {
        $job = new SendApplicationNotificationJob([
            'type' => 'application.status_updated',
            'recipient' => 'intern@example.com',
            'data' => ['intern_name' => 'Budi', 'vacancy_title' => 'IT Support', 'status' => 'under_review'],
        ]);
        $job->handle();

        $html = view('emails.application.status-updated', [
            'intern_name' => 'Budi', 'vacancy_title' => 'IT Support', 'status' => 'under_review',
        ])->render();
        $this->assertStringContainsString('Budi', $html);
    }

    public function test_handle_sends_email_for_interview_scheduled(): void
    {
        $job = new SendApplicationNotificationJob([
            'type' => 'application.interview_scheduled',
            'recipient' => 'intern@example.com',
            'data' => ['intern_name' => 'Budi', 'vacancy_title' => 'IT Support', 'interview_date' => '2026-06-01 10:00'],
        ]);
        $job->handle();

        $html = view('emails.application.interview-scheduled', [
            'intern_name' => 'Budi', 'vacancy_title' => 'IT Support', 'interview_date' => '2026-06-01 10:00',
        ])->render();
        $this->assertStringContainsString('Budi', $html);
    }

    public function test_handle_sends_email_for_decision(): void
    {
        $job = new SendApplicationNotificationJob([
            'type' => 'application.decision',
            'recipient' => 'intern@example.com',
            'data' => ['intern_name' => 'Budi', 'vacancy_title' => 'IT Support', 'status' => 'accepted', 'rejection_reason' => null],
        ]);
        $job->handle();

        $html = view('emails.application.decision', [
            'intern_name' => 'Budi', 'vacancy_title' => 'IT Support', 'status' => 'accepted', 'rejection_reason' => null,
        ])->render();
        $this->assertStringContainsString('Budi', $html);
    }

    public function test_handle_skips_unknown_type(): void
    {
        Mail::fake();
        $job = new SendApplicationNotificationJob([
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
        $job = new SendApplicationNotificationJob([
            'type' => 'application.submitted',
            'recipient' => null,
            'data' => [],
        ]);
        $job->handle();

        Mail::assertNothingSent();
    }
}
