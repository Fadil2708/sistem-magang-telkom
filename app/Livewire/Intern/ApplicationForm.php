<?php

namespace App\Livewire\Intern;

use App\Models\Application;
use App\Models\Vacancy;
use App\Notifications\ApplicationNotification;
use App\Services\ApplicationService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ApplicationForm extends Component
{
    public ?Vacancy $vacancy = null;
    public bool $hasApplied = false;
    public bool $profileComplete = false;
    public ?string $applicationStatus = null;
    public ?string $errorMessage = null;

    private ApplicationService $applicationService;

    public function boot(ApplicationService $applicationService): void
    {
        $this->applicationService = $applicationService;
    }

    public function mount(string $vacancyId): void
    {
        $this->vacancy = Vacancy::findOrFail($vacancyId);
        $this->checkStatus();
    }

    public function checkStatus(): void
    {
        $user = auth()->user();

        $this->profileComplete = $user->internProfile !== null
            && $user->internProfile->full_name
            && $user->internProfile->institution_name;

        $existing = Application::where('intern_id', $user->id)
            ->where('vacancy_id', $this->vacancy->id)
            ->first();

        if ($existing) {
            $this->hasApplied = true;
            $this->applicationStatus = $existing->status;
        }
    }

    public function submit(): void
    {
        if (!$this->profileComplete) {
            $this->errorMessage = 'Lengkapi profil Anda sebelum mendaftar.';
            return;
        }

        try {
            $application = $this->applicationService->apply(
                $this->vacancy,
                auth()->user()
            );

            $this->hasApplied = true;
            $this->applicationStatus = $application->status;

            auth()->user()->notify(new ApplicationNotification($application, 'submitted'));

            $this->dispatch('toast', message: 'Lamaran berhasil dikirim!', type: 'success');
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.intern.application-form');
    }
}