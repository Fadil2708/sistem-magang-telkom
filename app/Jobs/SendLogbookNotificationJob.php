<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendLogbookNotificationJob implements ShouldQueue, ShouldBeEncrypted
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
            'logbook.revision_requested' => 'emails.logbook.revision-requested',
            'logbook.approved' => 'emails.logbook.approved',
            'logbook.new_submission' => 'emails.logbook.new-submission',
            default => null,
        };

        if (!$view || !$recipient) {
            return;
        }

        $subject = match ($type) {
            'logbook.revision_requested' => 'Logbook Perlu Revisi',
            'logbook.approved' => 'Logbook Disetujui',
            'logbook.new_submission' => 'Logbook Baru dari Peserta',
            default => 'Notifikasi Logbook',
        };

        if (!$recipient) {
            return;
        }

        Mail::send($view, $data, function ($message) use ($recipient, $subject) {
            $message->to($recipient)
                ->subject($subject);
        });
    }

    public function failed(?\Throwable $exception): void
    {
        Log::error("[SendLogbookNotificationJob] Failed: {$exception->getMessage()}");
    }
}
