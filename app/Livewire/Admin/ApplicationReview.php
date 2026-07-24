<?php

namespace App\Livewire\Admin;

use App\Models\Application;
use App\Models\Vacancy;
use App\Notifications\ApplicationNotification;
use App\Services\ApplicationService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class ApplicationReview extends Component
{
    use WithPagination;

    public string $filterStatus = '';
    public string $filterVacancy = '';
    public ?string $selectedApplicationId = null;

    public bool $showReviewModal = false;
    public string $reviewStatus = '';
    public ?string $rejectionReason = null;
    public ?string $interviewDate = null;
    public ?string $adminNotes = null;

    private ApplicationService $applicationService;

    public function boot(ApplicationService $applicationService): void
    {
        $this->applicationService = $applicationService;
    }

    public function updatingFilterStatus(): void { $this->resetPage(); }
    public function updatingFilterVacancy(): void { $this->resetPage(); }

    public function openReview(string $id): void
    {
        $app = Application::with('intern.internProfile', 'vacancy')->findOrFail($id);
        $this->selectedApplicationId = $app->id;
        $this->reviewStatus = $app->status;
        $this->rejectionReason = $app->rejection_reason;
        $this->interviewDate = $app->interview_date?->format('Y-m-d\TH:i');
        $this->adminNotes = $app->admin_notes;
        $this->showReviewModal = true;
    }

    public function updateStatus(): void
    {
        $this->validate([
            'reviewStatus' => 'required|in:under_review,interview_scheduled,accepted,rejected',
            'rejectionReason' => 'required_if:reviewStatus,rejected|string|nullable',
            'interviewDate' => 'nullable|date',
            'adminNotes' => 'nullable|string',
        ]);

        $application = Application::findOrFail($this->selectedApplicationId);

        try {
            if ($this->reviewStatus === 'accepted') {
                $this->applicationService->accept($application);
                $application->refresh()->intern->notify(new ApplicationNotification($application, 'decision'));
            } elseif ($this->reviewStatus === 'rejected') {
                $this->applicationService->reject($application, $this->rejectionReason);
                $application->refresh()->intern->notify(new ApplicationNotification($application, 'decision'));
            } else {
                $this->applicationService->updateStatus(
                    $application,
                    $this->reviewStatus,
                    $this->rejectionReason,
                    $this->interviewDate
                );

                if ($this->adminNotes) {
                    $application->update(['admin_notes' => $this->adminNotes]);
                }

                $application->refresh();

                if ($this->reviewStatus === 'interview_scheduled') {
                    $application->intern->notify(new ApplicationNotification($application, 'interview_scheduled'));
                } else {
                    $application->intern->notify(new ApplicationNotification($application, 'status_updated'));
                }
            }
        } catch (\Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
            return;
        }

        $this->showReviewModal = false;
        $this->dispatch('toast', message: 'Status lamaran berhasil diperbarui.', type: 'success');
    }

    public function render()
    {
        $applications = Application::with(['intern.internProfile', 'vacancy', 'internship'])
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterVacancy, fn($q) => $q->where('vacancy_id', $this->filterVacancy))
            ->orderBy('applied_at', 'desc')
            ->paginate(15);

        $vacancies = Vacancy::select('id', 'title')->get();

        return view('livewire.admin.application-review', compact('applications', 'vacancies'));
    }
}