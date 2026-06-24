<?php

namespace App\Jobs;

use App\Models\Certificate;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendCertificateNotificationJob implements ShouldQueue, ShouldBeEncrypted
{
    use Dispatchable, Queueable;

    public $tries = 3;
    public $maxExceptions = 1;

    public function __construct(
        private readonly Certificate $certificate
    ) {}

    public function handle(): void
    {
        $notificationData = app(NotificationService::class)->sendCertificateIssued($this->certificate);

        $type = $notificationData['type'];
        $recipient = $notificationData['recipient'];
        $data = $notificationData['data'];

        $view = match ($type) {
            'certificate.issued' => 'emails.certificate.issued',
            default => null,
        };

        if (!$view) {
            return;
        }

        $subject = match ($type) {
            'certificate.issued' => 'Sertifikat Diterbitkan',
            default => 'Notifikasi Sertifikat',
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
        Log::error("[SendCertificateNotificationJob] Failed: {$exception->getMessage()}");
    }
}
