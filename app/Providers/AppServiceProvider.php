<?php

namespace App\Providers;

use App\Models\Application;
use App\Models\Certificate;
use App\Models\Internship;
use App\Policies\ApplicationPolicy;
use App\Policies\CertificatePolicy;
use App\Policies\InternshipPolicy;
use App\View\Composers\LayoutComposer;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        URL::forceRootUrl(config('app.url'));

        Gate::policy(Application::class, ApplicationPolicy::class);
        Gate::policy(Certificate::class, CertificatePolicy::class);
        Gate::policy(Internship::class, InternshipPolicy::class);

        View::composer('layouts.app', LayoutComposer::class);
    }
}
