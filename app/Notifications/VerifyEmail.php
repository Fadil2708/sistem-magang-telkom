<?php

namespace App\Notifications;

use App\Mail\Auth\VerifyEmailMail;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Mail\Mailable;

class VerifyEmail extends VerifyEmailBase
{
    public function toMail($notifiable): Mailable
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new VerifyEmailMail(
            url: $verificationUrl,
            user: $notifiable->displayName(),
            expire: config('auth.verification.expire', 60),
        ));
    }
}
