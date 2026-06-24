<?php

namespace App\Providers;

use App\Mail\ResendTransport;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        URL::forceRootUrl(config('app.url'));

        Mail::extend('resend-api', function () {
            return new ResendTransport(
                env('MAIL_RESEND_API_KEY', config('services.resend.key')),
            );
        });
    }
}
