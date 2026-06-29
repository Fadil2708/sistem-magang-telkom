<?php

namespace App\Notifications;

use App\Models\Certificate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CertificateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Certificate $certificate,
        public string $type = 'issued',
    ) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $data = [
            'intern_name'        => $this->certificate->intern->internProfile?->full_name ?? $this->certificate->intern->email,
            'certificate_number' => $this->certificate->certificate_number,
        ];

        return (new MailMessage)
            ->subject($this->getSubject())
            ->view($this->getView(), $data);
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type'       => "certificate.{$this->type}",
            'title'      => $this->getTitle(),
            'body'       => $this->getBody(),
            'url'        => route('intern.certificate'),
            'model_type' => 'certificate',
            'model_id'   => $this->certificate->id,
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
        return 'emails.certificate.issued';
    }

    private function getSubject(): string
    {
        return 'Sertifikat Diterbitkan';
    }

    private function getTitle(): string
    {
        return 'Sertifikat Diterbitkan';
    }

    private function getBody(): string
    {
        $num = $this->certificate->certificate_number;
        return "Sertifikat ({$num}) telah diterbitkan. Silakan unduh di aplikasi.";
    }
}
