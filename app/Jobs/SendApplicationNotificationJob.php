<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendApplicationNotificationJob implements ShouldQueue, ShouldBeEncrypted
{
    use Dispatchable, Queueable;

    public $tries = 3;
    public $maxExceptions = 1;

    public function __construct(
        private readonly array $notificationData
    ) {}

    public function handle(): void
    {
        $type = $this->notificationData['type'];
        $recipient = $this->notificationData['recipient'];
        $data = $this->notificationData['data'];

        $view = match ($type) {
            'application.submitted' => 'emails.application.submitted',
            'application.status_updated' => 'emails.application.status-updated',
            'application.interview_scheduled' => 'emails.application.interview-scheduled',
            'application.decision' => 'emails.application.decision',
            default => null,
        };

        if (!$view) {
            return;
        }

        $subject = match ($type) {
            'application.submitted' => 'Lamaran Terkirim',
            'application.status_updated' => 'Status Lamaran Diperbarui',
            'application.interview_scheduled' => 'Jadwal Wawancara',
            'application.decision' => 'Keputusan Lamaran',
            default => 'Notifikasi Lamaran',
        };

        if (!$recipient) {
            return;
        }

        Mail::to($recipient)->send(
            new \App\Mail\ApplicationNotificationMail($view, $subject, $data)
        );
    }

    public function failed(?\Throwable $exception): void
    {
        Log::error("[SendApplicationNotificationJob] Failed: {$exception->getMessage()}");
    }
}
