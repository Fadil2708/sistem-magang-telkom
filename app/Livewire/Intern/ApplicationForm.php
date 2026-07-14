<?php

namespace App\Livewire\Intern;

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

    public function mount(string $vacancyId): void
    {
        $this->vacancy = Vacancy::findOrFail($vacancyId);
        $this->checkStatus();
    }

    public function checkStatus(): void
    {
        $intern = auth()->user();
        $profile = $intern->internProfile;

        $required = \App\Models\InternProfile::requiredFields();
        $this->profileComplete = $profile && collect($required)->every(fn($f) => !empty($profile->{$f}));

        $existing = $intern->applications()->where('vacancy_id', $this->vacancy->id)->first();

        if ($existing) {
            $this->applicationStatus = $existing->status;
            $this->hasApplied = !in_array($existing->status, ['rejected', 'cancelled']);
        } else {
            $this->applicationStatus = null;
            $this->hasApplied = false;
        }
    }

    public function apply(ApplicationService $service): void
    {
        try {
            $application = $service->apply(auth()->user(), $this->vacancy->id);
            auth()->user()->notify(new ApplicationNotification($application, 'submitted'));
            $this->hasApplied = true;
            $this->applicationStatus = 'submitted';
            $this->errorMessage = null;
            session()->flash('success', 'Lamaran berhasil dikirim!');
        } catch (\Exception $e) {
            Log::warning("[ApplicationForm] apply error: {$e->getMessage()}");
            $this->errorMessage = $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.intern.application-form');
    }
}
