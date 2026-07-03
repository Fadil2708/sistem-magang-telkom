<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.')->group(function () {

    // ─── Auth (no auth, throttled per endpoint) ──────────────
    Route::prefix('auth')->name('auth.')->middleware('throttle:5,1')->group(function () {
        Route::post('register', [\App\Http\Controllers\Api\AuthController::class, 'register'])->name('register');
        Route::post('login', [\App\Http\Controllers\Api\AuthController::class, 'login'])->name('login');
        Route::post('logout', [\App\Http\Controllers\Api\AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
        Route::post('forgot-password', [\App\Http\Controllers\Api\AuthController::class, 'forgotPassword'])->name('forgot-password');
        Route::post('reset-password', [\App\Http\Controllers\Api\AuthController::class, 'resetPassword'])->name('reset-password');
    });

    // ─── Authenticated + Throttled routes ───────────────────
    Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {

        // Users & Profiles
        Route::get('users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->middleware('role:admin')->name('users.index');
        Route::post('users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->middleware('role:admin')->name('users.store');
        Route::get('users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->middleware('role:admin')->name('users.show');
        Route::put('users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->middleware('role:admin')->name('users.update');
        Route::delete('users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->middleware('role:admin')->name('users.destroy');

        Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
        Route::put('profile/intern', [\App\Http\Controllers\Intern\ProfileController::class, 'update'])->middleware('role:intern')->name('profile.intern.update');
        Route::put('profile/supervisor', [\App\Http\Controllers\Supervisor\ProfileController::class, 'update'])->middleware('role:supervisor')->name('profile.supervisor.update');

        // Vacancies
        Route::get('vacancies', [\App\Http\Controllers\VacancyController::class, 'index'])->name('vacancies.index');
        Route::post('vacancies', [\App\Http\Controllers\VacancyController::class, 'store'])->middleware('role:admin')->name('vacancies.store');
        Route::get('vacancies/{vacancy}', [\App\Http\Controllers\VacancyController::class, 'show'])->name('vacancies.show');
        Route::put('vacancies/{vacancy}', [\App\Http\Controllers\VacancyController::class, 'update'])->middleware('role:admin')->name('vacancies.update');
        Route::delete('vacancies/{vacancy}', [\App\Http\Controllers\VacancyController::class, 'destroy'])->middleware('role:admin')->name('vacancies.destroy');
        Route::patch('vacancies/{vacancy}/status', [\App\Http\Controllers\VacancyController::class, 'updateStatus'])->middleware('role:admin')->name('vacancies.status');

        // Applications
        Route::get('applications', [\App\Http\Controllers\Admin\ApplicationController::class, 'index'])->middleware('role:admin')->name('applications.index');
        Route::post('applications', [\App\Http\Controllers\Intern\ApplicationController::class, 'store'])->middleware(['role:intern', 'profile.complete'])->name('applications.store');
        Route::get('applications/my', [\App\Http\Controllers\Intern\ApplicationController::class, 'myApplications'])->middleware('role:intern')->name('applications.my');
        Route::get('applications/{application}', [\App\Http\Controllers\ApplicationController::class, 'show'])->middleware('role:admin,intern')->name('applications.show');
        Route::patch('applications/{application}/status', [\App\Http\Controllers\Admin\ApplicationController::class, 'updateStatus'])->middleware('role:admin')->name('applications.status');
        Route::patch('applications/{application}/cancel', [\App\Http\Controllers\Intern\ApplicationController::class, 'cancel'])->middleware('role:intern')->name('applications.cancel');

        // Internships
        Route::get('internships', [\App\Http\Controllers\Admin\InternshipController::class, 'index'])->middleware('role:admin')->name('internships.index');
        Route::get('internships/my', [\App\Http\Controllers\Intern\InternshipController::class, 'myInternship'])->middleware('role:intern')->name('internships.my');
        Route::get('internships/supervised', [\App\Http\Controllers\Supervisor\InternshipController::class, 'supervised'])->middleware('role:supervisor')->name('internships.supervised');
        Route::get('internships/{internship}', [\App\Http\Controllers\InternshipController::class, 'show'])->name('internships.show');
        Route::patch('internships/{internship}/supervisor', [\App\Http\Controllers\Admin\InternshipController::class, 'assignSupervisor'])->middleware('role:admin')->name('internships.supervisor');
        Route::patch('internships/{internship}/status', [\App\Http\Controllers\Admin\InternshipController::class, 'updateStatus'])->middleware('role:admin')->name('internships.status');
        Route::patch('internships/{internship}/dates', [\App\Http\Controllers\Admin\InternshipController::class, 'updateDates'])->middleware('role:admin')->name('internships.dates');

        // Logbooks
        Route::get('internships/{internship}/logbooks', [\App\Http\Controllers\LogbookController::class, 'index'])->name('logbooks.index');
        Route::post('internships/{internship}/logbooks', [\App\Http\Controllers\Intern\LogbookController::class, 'store'])->middleware('role:intern')->name('logbooks.store');
        Route::get('logbooks/{logbook}', [\App\Http\Controllers\LogbookController::class, 'show'])->name('logbooks.show');
        Route::put('logbooks/{logbook}', [\App\Http\Controllers\Intern\LogbookController::class, 'update'])->middleware('role:intern')->name('logbooks.update');
        Route::patch('logbooks/{logbook}/submit', [\App\Http\Controllers\Intern\LogbookController::class, 'submit'])->middleware('role:intern')->name('logbooks.submit');
        Route::patch('logbooks/{logbook}/review', [\App\Http\Controllers\Supervisor\LogbookController::class, 'review'])->middleware('role:supervisor')->name('logbooks.review');

        // Final Reports
        Route::post('internships/{internship}/reports', [\App\Http\Controllers\Intern\FinalReportController::class, 'store'])->middleware('role:intern')->name('reports.store');
        Route::get('internships/{internship}/reports', [\App\Http\Controllers\FinalReportController::class, 'show'])->name('reports.show');
        Route::patch('reports/{finalReport}/review', [\App\Http\Controllers\Supervisor\FinalReportController::class, 'review'])->middleware('role:supervisor')->name('reports.review');

        // Evaluations
        Route::post('internships/{internship}/evaluations', [\App\Http\Controllers\Supervisor\EvaluationController::class, 'store'])->middleware('role:supervisor')->name('evaluations.store');
        Route::get('internships/{internship}/evaluations', [\App\Http\Controllers\EvaluationController::class, 'show'])->name('evaluations.show');
        Route::put('evaluations/{evaluation}', [\App\Http\Controllers\Supervisor\EvaluationController::class, 'update'])->middleware('role:supervisor')->name('evaluations.update');
        Route::patch('evaluations/{evaluation}/lock', [\App\Http\Controllers\Admin\EvaluationController::class, 'lock'])->middleware('role:admin')->name('evaluations.lock');

        // Certificates
        Route::post('internships/{internship}/certificates', [\App\Http\Controllers\Admin\CertificateController::class, 'store'])->middleware('role:admin')->name('certificates.store');
        Route::get('internships/{internship}/certificates', [\App\Http\Controllers\CertificateController::class, 'show'])->name('certificates.show');
        Route::get('certificates/{certificate}/download', [\App\Http\Controllers\Intern\CertificateController::class, 'download'])->middleware('role:intern')->name('certificates.download');

        // Testimonials
        Route::post('internships/{internship}/testimonials', [\App\Http\Controllers\Intern\TestimonialController::class, 'store'])->middleware('role:intern')->name('testimonials.store');
        Route::patch('testimonials/{testimonial}/publish', [\App\Http\Controllers\Admin\TestimonialController::class, 'togglePublish'])->middleware('role:admin')->name('testimonials.publish');

        // Dashboard Stats
        Route::get('dashboard/stats', [\App\Http\Controllers\DashboardController::class, 'stats'])->name('dashboard.stats');
    });

    // ─── Public Testimonials (no auth) ─────────────────────
    Route::get('testimonials', [\App\Http\Controllers\Public\TestimonialController::class, 'index'])->name('testimonials.index');

    // ─── Public Verification (no auth) ─────────────────────
    Route::get('verify/{token}', [\App\Http\Controllers\Public\CertificateVerifyController::class, 'verify'])->name('verify');

    // ─── Admin Aggregate Lists ───────────────────────────
    Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('logbooks', [\App\Http\Controllers\LogbookController::class, 'adminIndex'])->name('logbooks.index');
        Route::get('reports', [\App\Http\Controllers\FinalReportController::class, 'adminIndex'])->name('reports.index');
        Route::get('evaluations', [\App\Http\Controllers\Admin\EvaluationController::class, 'adminIndex'])->name('evaluations.index');
        Route::get('certificates', [\App\Http\Controllers\Admin\CertificateController::class, 'adminIndex'])->name('certificates.index');
    });
});
