<?php

namespace App\Notifications;

use App\Mail\Auth\ResetPasswordMail;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordBase;
use Illuminate\Mail\Mailable;

class ResetPassword extends ResetPasswordBase
{
    public function toMail($notifiable): Mailable
    {
        $resetUrl = $this->resetUrl($notifiable);

        return (new ResetPasswordMail(
            url: $resetUrl,
            user: $notifiable->displayName(),
            expire: config('auth.passwords.' . config('auth.defaults.passwords') . '.expire', 60),
        ))->to($notifiable);
    }
}
