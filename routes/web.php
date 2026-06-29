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

use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Artisan;

Route::get('/seed', function () {
    $lockFile = storage_path('app/seeded.lock');
    if (file_exists($lockFile)) {
        abort(403, 'Seeding sudah pernah dilakukan.');
    }

    $token = request('token');
    if (!$token || $token !== config('app.seeder_token')) {
        abort(403, 'Token tidak valid.');
    }

    Artisan::call('db:seed', ['--force' => true]);

    file_put_contents($lockFile, now());

    return response('✅ Seeder berhasil & otomatis dinonaktifkan.');
});

Route::get('/', WelcomeController::class);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route(auth()->user()->role . '.dashboard');
    })->name('dashboard');

    Route::prefix('admin')->middleware('role:admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

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
        Route::get('/dashboard', function () {
            return view('supervisor.dashboard');
        })->name('dashboard');

        Route::get('/profile', SupervisorProfileForm::class)->name('profile');
        Route::get('/interns', MyInterns::class)->name('interns.index');
        Route::get('/interns/{internship}', function (string $id) {
            $internship = \App\Models\Internship::with(['intern.internProfile', 'vacancy'])->findOrFail($id);
            if ($internship->supervisor_id === null || $internship->supervisor_id !== auth()->id()) {
                abort(403);
            }
            return view('supervisor.interns.show', compact('internship'));
        })->name('interns.show');
        Route::get('/logbooks', LogbookReview::class)->name('logbooks');
        Route::get('/reports', ReportReview::class)->name('reports');
        Route::get('/evaluations', EvaluationForm::class)->name('evaluations.create');
        Route::get('/evaluations/{internshipId}', EvaluationForm::class)->name('evaluations.show');
    });

    Route::prefix('intern')->middleware('role:intern')->name('intern.')->group(function () {
        Route::get('/dashboard', function () {
            return view('intern.dashboard');
        })->name('dashboard');

        Route::get('/profile', ProfileForm::class)->name('profile');
        Route::get('/vacancies', InternVacancyList::class)->name('vacancies');
        Route::get('/applications/create/{vacancyId}', ApplicationForm::class)->name('applications.create');
        Route::get('/applications', MyApplications::class)->name('applications');
        Route::get('/applications/{application}', function (string $id) {
            $application = \App\Models\Application::with([
                'vacancy', 'intern.internProfile', 'internship'
            ])->findOrFail($id);

            if ($application->intern_id !== auth()->id()) {
                abort(403);
            }

            return view('intern.applications.show', compact('application'));
        })->name('applications.show');
        Route::get('/internship', function () {
            $internship = \App\Models\Internship::with([
                'vacancy', 'supervisor.supervisorProfile'
            ])->where('intern_id', auth()->id())->first();

            if (!$internship) {
                return view('intern.internship.index');
            }

            return view('intern.internship.index', compact('internship'));
        })->name('internship');
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
    Route::get('/notifications', function () {
        $notifications = auth()->user()
            ->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('notifications.index', compact('notifications'));
    })->name('notifications');

    Route::post('/notifications/read-all', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back();
    })->name('notifications.read-all');

    Route::get('/notifications/{id}/read', function (string $id) {
        $notif = auth()->user()->notifications()->findOrFail($id);
        $notif->markAsRead();
        $redirect = request('redirect', route('notifications'));
        return redirect($redirect);
    })->name('notifications.read');

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
