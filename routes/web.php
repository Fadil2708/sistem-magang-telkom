<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\ExportController;
use App\Livewire\Admin\ApplicationReview;
use App\Livewire\Admin\CertificateList;
use App\Livewire\Admin\DashboardStats as AdminDashboardStats;
use App\Livewire\Admin\InternshipList;
use App\Livewire\Admin\LogbookList as AdminLogbookList;
use App\Livewire\Admin\SupervisorMapping;
use App\Livewire\Admin\EvaluationList;
use App\Livewire\Admin\ReportList;
use App\Livewire\Admin\UserList;
use App\Livewire\Admin\UserForm;
use App\Livewire\Admin\InviteList;
use App\Livewire\Admin\TestimonialList;
use App\Livewire\Admin\FaqList;
use App\Livewire\Admin\VacancyForm;
use App\Livewire\Admin\VacancyList;
use App\Livewire\Intern\ApplicationForm;
use App\Livewire\Intern\CertificateView;
use App\Livewire\Intern\FinalReportForm;
use App\Livewire\Intern\LogbookForm;
use App\Livewire\Intern\LogbookList;
use App\Livewire\Intern\MyApplications;
use App\Livewire\Intern\ProfileForm;
use App\Livewire\Intern\EvaluationView;
use App\Livewire\Intern\TestimonialForm;
use App\Livewire\Intern\VacancyList as InternVacancyList;
use App\Livewire\Supervisor\DashboardStats as SupervisorDashboardStats;
use App\Livewire\Supervisor\EvaluationForm;
use App\Livewire\Supervisor\LogbookReview;
use App\Livewire\Supervisor\ProfileForm as SupervisorProfileForm;
use App\Livewire\Supervisor\MyInterns;
use App\Livewire\Supervisor\ReportReview;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\WelcomeController;

Route::get('/', WelcomeController::class);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route(auth()->user()->role . '.dashboard');
    })->name('dashboard');

    Route::prefix('admin')->middleware('role:admin')->name('admin.')->group(function () {
        Route::view('/dashboard', 'admin.dashboard')->name('dashboard');

        Route::get('/users', UserList::class)->name('users');
        Route::get('/users/create', UserForm::class)->name('users.create');
        Route::get('/users/{id}/edit', UserForm::class)->name('users.edit');

        Route::get('/vacancies', VacancyList::class)->name('vacancies.index');
        Route::get('/vacancies/create', VacancyForm::class)->name('vacancies.create');
        Route::get('/vacancies/{id}/edit', VacancyForm::class)->name('vacancies.edit');
        Route::get('/applications', ApplicationReview::class)->name('applications.index');
        Route::get('/applications/{id}/file/{type}', [ApplicationController::class, 'downloadFile'])->name('applications.file');
        Route::get('/supervisors', SupervisorMapping::class)->name('supervisors.index');
        Route::get('/invites', InviteList::class)->name('invites');
        Route::get('/testimonials', TestimonialList::class)->name('testimonials');
        Route::get('/faq', FaqList::class)->name('faq');
        Route::get('/certificates', CertificateList::class)->name('certificates');
        Route::get('/certificates/{id}/download', [CertificateController::class, 'download'])->name('certificates.download');
        Route::get('/internships', InternshipList::class)->name('internships');
        Route::get('/logbooks', AdminLogbookList::class)->name('logbooks');
        Route::get('/evaluations', EvaluationList::class)->name('evaluations');
        Route::get('/reports', ReportList::class)->name('reports');

        Route::prefix('export')->name('export.')->group(function () {
            Route::get('/internships', [ExportController::class, 'internships'])->name('internships');
            Route::get('/logbooks', [ExportController::class, 'logbooks'])->name('logbooks');
            Route::get('/applications', [ExportController::class, 'applications'])->name('applications');
            Route::get('/reports', [ExportController::class, 'reports'])->name('reports');
            Route::get('/evaluations', [ExportController::class, 'evaluations'])->name('evaluations');
            Route::get('/certificates', [ExportController::class, 'certificates'])->name('certificates');
        });
    });

    Route::prefix('supervisor')->middleware('role:supervisor')->name('supervisor.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Supervisor\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/profile', SupervisorProfileForm::class)->name('profile');
        Route::get('/interns', MyInterns::class)->name('interns.index');
        Route::get('/interns/{internship}', [\App\Http\Controllers\Supervisor\InternController::class, 'show'])->name('interns.show');
        Route::get('/logbooks', LogbookReview::class)->name('logbooks');
        Route::get('/reports', ReportReview::class)->name('reports');
        Route::get('/evaluations', EvaluationForm::class)->name('evaluations.create');
        Route::get('/evaluations/{internshipId}', EvaluationForm::class)->name('evaluations.show');
    });

    Route::prefix('intern')->middleware('role:intern')->name('intern.')->group(function () {
        Route::view('/dashboard', 'intern.dashboard')->name('dashboard');

        Route::get('/profile', ProfileForm::class)->name('profile');
        Route::get('/vacancies', InternVacancyList::class)->name('vacancies');
        Route::get('/applications/create/{vacancyId}', ApplicationForm::class)->name('applications.create');
        Route::get('/applications', MyApplications::class)->name('applications');
        Route::get('/applications/{application}', [\App\Http\Controllers\Intern\ApplicationController::class, 'show'])->name('applications.show');
        Route::get('/internship', [\App\Http\Controllers\Intern\InternshipController::class, 'index'])->name('internship');
        Route::get('/logbooks', LogbookList::class)->name('logbooks');
        Route::get('/logbooks/create', LogbookForm::class)->name('logbooks.create');
        Route::get('/logbooks/{id}/edit', LogbookForm::class)->name('logbooks.edit');
        Route::get('/reports', FinalReportForm::class)->name('reports');
        Route::get('/evaluation', EvaluationView::class)->name('evaluation');
        Route::get('/certificate', CertificateView::class)->name('certificate');
        Route::get('/certificates/{id}/download', [\App\Http\Controllers\Intern\CertificateController::class, 'download'])->name('certificates.download');
        Route::get('/testimonials/create', TestimonialForm::class)->name('testimonials.create');
    });

    // ─── Notifications ──────────────────────────────────
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/photo', [ProfileController::class, 'photo'])->name('profile.photo');

    // ─── Private file serving ───────────────────────────
    Route::get('/private/{path}', [App\Http\Controllers\FileController::class, 'serve'])
        ->where('path', '.*')
        ->name('private.serve');
});

// ─── Public Pages ─────────────────────────────────────
Route::get('/verify/{token}', [\App\Http\Controllers\Public\Web\CertificateVerifyController::class, 'show'])
    ->name('public.verify');

Route::get('/vacancies', [\App\Http\Controllers\Public\Web\VacancyController::class, 'index'])
    ->name('public.vacancies');

Route::get('/vacancies/{vacancy}', [\App\Http\Controllers\Public\Web\VacancyController::class, 'show'])
    ->name('public.vacancies.show');

Route::get('/testimonials', [\App\Http\Controllers\Public\Web\TestimonialController::class, 'index'])
    ->name('public.testimonials');

Route::view('/tentang-kami', 'pages.tentang-kami')->name('public.tentang-kami');
Route::view('/syarat-ketentuan', 'pages.syarat-ketentuan')->name('public.syarat');
Route::view('/kebijakan-privasi', 'pages.kebijakan-privasi')->name('public.privacy');

require __DIR__.'/auth.php';
