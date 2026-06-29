<?php

namespace App\Notifications;

use App\Models\FinalReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public FinalReport $report,
        public string $type,
    ) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $data = [
            'intern_name'  => $this->report->intern->internProfile?->full_name ?? $this->report->intern->email,
            'report_title' => $this->report->title,
        ];

        return (new MailMessage)
            ->subject($this->getSubject())
            ->view($this->getView(), $data);
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type'       => "report.{$this->type}",
            'title'      => $this->getTitle(),
            'body'       => $this->getBody(),
            'url'        => route('intern.reports'),
            'model_type' => 'final_report',
            'model_id'   => $this->report->id,
        ];
    }

    public function toArray($notifiable): array
    {
        return [
            'type'  => $this->type,
            'title' => $this->getTitle(),
            'body'  => $this->getBody(),
        ];
    }

    private function getView(): string
    {
        return match ($this->type) {
            'approved' => 'emails.report.approved',
            'rejected' => 'emails.report.rejected',
        };
    }

    private function getSubject(): string
    {
        return match ($this->type) {
            'approved' => 'Laporan Akhir Disetujui',
            'rejected' => 'Laporan Akhir Ditolak',
        };
    }

    private function getTitle(): string
    {
        return match ($this->type) {
            'approved' => 'Laporan Disetujui',
            'rejected' => 'Laporan Ditolak',
        };
    }

    private function getBody(): string
    {
        $title = $this->report->title;
        return match ($this->type) {
            'approved' => "Laporan \"{$title}\" telah disetujui.",
            'rejected' => "Laporan \"{$title}\" perlu diperbaiki.",
        };
    }
}
