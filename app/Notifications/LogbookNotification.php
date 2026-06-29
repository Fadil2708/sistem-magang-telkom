<?php

namespace App\Notifications;

use App\Models\Logbook;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LogbookNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Logbook $logbook,
        public string $type,
    ) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $activityDate = $this->logbook->activity_date?->format('Y-m-d');

        $data = [];

        if ($this->type === 'new_submission') {
            $supervisor = $this->logbook->internship?->supervisor;
            $data = [
                'supervisor_name' => $supervisor?->supervisorProfile?->full_name ?? $supervisor?->email ?? 'Pembimbing',
                'intern_name'     => $this->logbook->intern->internProfile?->full_name ?? $this->logbook->intern->email,
                'activity_date'   => $activityDate,
            ];
        } else {
            $data = [
                'intern_name'     => $this->logbook->intern->internProfile?->full_name ?? $this->logbook->intern->email,
                'activity_date'   => $activityDate,
                'supervisor_notes'=> $this->logbook->supervisor_notes,
            ];
        }

        return (new MailMessage)
            ->subject($this->getSubject())
            ->view($this->getView(), $data);
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type'       => "logbook.{$this->type}",
            'title'      => $this->getTitle(),
            'body'       => $this->getBody(),
            'url'        => $this->getUrl(),
            'model_type' => 'logbook',
            'model_id'   => $this->logbook->id,
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
            'approved'          => 'emails.logbook.approved',
            'revision_requested'=> 'emails.logbook.revision-requested',
            'new_submission'    => 'emails.logbook.new-submission',
        };
    }

    private function getSubject(): string
    {
        return match ($this->type) {
            'approved'           => 'Logbook Disetujui',
            'revision_requested' => 'Logbook Perlu Revisi',
            'new_submission'     => 'Logbook Baru dari Peserta',
        };
    }

    private function getTitle(): string
    {
        return match ($this->type) {
            'approved'           => 'Logbook Disetujui',
            'revision_requested' => 'Logbook Perlu Revisi',
            'new_submission'     => 'Logbook Baru Perlu Review',
        };
    }

    private function getBody(): string
    {
        $date = $this->logbook->activity_date?->format('d M Y');
        $internName = $this->logbook->intern->internProfile?->full_name ?? $this->logbook->intern->email;

        return match ($this->type) {
            'approved'           => "Logbook tanggal {$date} telah disetujui.",
            'revision_requested' => "Logbook tanggal {$date} perlu direvisi.",
            'new_submission'     => "{$internName} mengirimkan logbook baru untuk direview.",
        };
    }

    private function getUrl(): string
    {
        return match ($this->type) {
            'approved', 'revision_requested' => route('intern.logbooks'),
            'new_submission'                 => route('supervisor.logbooks'),
        };
    }
}
