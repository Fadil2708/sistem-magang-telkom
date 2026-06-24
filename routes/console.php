<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Tutup lowongan yang sudah lewat deadline secara otomatis setiap jam
Schedule::command('vacancies:close-expired')->everyThirtyMinutes();
