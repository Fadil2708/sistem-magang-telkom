<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordBase;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends ResetPasswordBase
{
    public function toMail($notifiable): MailMessage
    {
        $resetUrl = $this->resetUrl($notifiable);

        return (new MailMessage)
            ->subject('Reset Password — Telkom Sukabumi')
            ->view('emails.auth.reset-password', [
                'url' => $resetUrl,
                'user' => $notifiable->displayName(),
                'expire' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire', 60),
            ]);
    }
}
