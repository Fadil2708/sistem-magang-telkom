<?php

namespace Tests\Feature\Jobs;

use App\Jobs\SendReportNotificationJob;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendReportNotificationJobTest extends TestCase
{
    public function test_handle_sends_email_for_report_rejected(): void
    {
        $job = new SendReportNotificationJob([
            'type' => 'report.rejected',
            'recipient' => 'intern@example.com',
            'data' => ['intern_name' => 'Budi', 'report_title' => 'Laporan Akhir'],
        ]);
        $job->handle();

        $html = view('emails.report.rejected', [
            'intern_name' => 'Budi', 'report_title' => 'Laporan Akhir',
        ])->render();
        $this->assertStringContainsString('Budi', $html);
        $this->assertStringContainsString('Laporan Akhir', $html);
    }

    public function test_handle_skips_unknown_type(): void
    {
        Mail::fake();
        $job = new SendReportNotificationJob([
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
        $job = new SendReportNotificationJob([
            'type' => 'report.rejected',
            'recipient' => null,
            'data' => [],
        ]);
        $job->handle();

        Mail::assertNothingSent();
    }
}
