<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Application $application,
        public string $type,
    ) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $internName = $this->application->intern->internProfile?->full_name ?? $this->application->intern->email;
        $vacancyTitle = $this->application->vacancy->title;

        $data = [
            'intern_name'     => $internName,
            'vacancy_title'   => $vacancyTitle,
            'status'          => $this->application->status,
            'interview_date'  => $this->application->interview_date?->format('Y-m-d H:i'),
            'rejection_reason'=> $this->application->rejection_reason,
        ];

        return (new MailMessage)
            ->subject($this->getSubject())
            ->view($this->getView(), $data);
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type'       => "application.{$this->type}",
            'title'      => $this->getTitle(),
            'body'       => $this->getBody(),
            'url'        => route('intern.applications'),
            'model_type' => 'application',
            'model_id'   => $this->application->id,
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
            'submitted'          => 'emails.application.submitted',
            'status_updated'     => 'emails.application.status-updated',
            'interview_scheduled'=> 'emails.application.interview-scheduled',
            'decision'           => 'emails.application.decision',
        };
    }

    private function getSubject(): string
    {
        return match ($this->type) {
            'submitted'           => 'Lamaran Terkirim',
            'status_updated'      => 'Status Lamaran Diperbarui',
            'interview_scheduled' => 'Jadwal Wawancara',
            'decision'            => $this->application->status === 'accepted' ? 'Lamaran Diterima' : 'Keputusan Lamaran',
        };
    }

    private function getTitle(): string
    {
        return match ($this->type) {
            'submitted'           => 'Lamaran Terkirim',
            'status_updated'      => 'Status Lamaran Diperbarui',
            'interview_scheduled' => 'Jadwal Wawancara',
            'decision'            => $this->application->status === 'accepted' ? 'Lamaran Diterima' : 'Lamaran Ditolak',
        };
    }

    private function getBody(): string
    {
        $vacancy = $this->application->vacancy->title;

        return match ($this->type) {
            'submitted'           => "Lamaran Anda untuk {$vacancy} telah dikirim.",
            'status_updated'      => "Status lamaran untuk {$vacancy} telah diperbarui.",
            'interview_scheduled' => "Anda dijadwalkan wawancara untuk {$vacancy}.",
            'decision'            => $this->application->status === 'accepted'
                ? "Selamat! Lamaran Anda untuk {$vacancy} diterima."
                : "Lamaran Anda untuk {$vacancy} ditolak.",
        };
    }
}
