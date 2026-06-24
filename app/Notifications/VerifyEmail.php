<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmail extends VerifyEmailBase
{
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifikasi Email — Telkom Sukabumi')
            ->view('emails.auth.verify-email', [
                'url' => $verificationUrl,
                'user' => $notifiable->displayName(),
                'expire' => config('auth.verification.expire', 60),
            ]);
    }
}
