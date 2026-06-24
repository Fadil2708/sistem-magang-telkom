<?php

namespace App\Mail\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $url,
        public string $user,
        public int $expire,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Password — Telkom Sukabumi',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.auth.reset-password',
            with: [
                'url' => $this->url,
                'user' => $this->user,
                'expire' => $this->expire,
            ],
        );
    }
}
