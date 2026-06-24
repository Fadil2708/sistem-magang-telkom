<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendReportNotificationJob implements ShouldQueue, ShouldBeEncrypted
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
            'report.rejected' => 'emails.report.rejected',
            'report.approved' => 'emails.report.approved',
            default => null,
        };

        if (!$view) {
            return;
        }

        $subject = match ($type) {
            'report.rejected' => 'Laporan Akhir Ditolak',
            'report.approved' => 'Laporan Akhir Disetujui',
            default => 'Notifikasi Laporan',
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
        Log::error("[SendReportNotificationJob] Failed: {$exception->getMessage()}");
    }
}
