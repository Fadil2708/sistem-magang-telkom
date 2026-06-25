<?php

namespace App\Livewire\Admin;

use App\Jobs\SendApplicationNotificationJob;
use App\Models\Application;
use App\Services\ApplicationService;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class ApplicationReview extends Component
{
    use WithPagination;

    public $filterStatus = '';
    public $filterVacancy = '';
    public $selectedApplicationId = null;

    private NotificationService $notificationService;

    public function boot(NotificationService $notificationService): void
    {
        $this->notificationService = $notificationService;
    }

    // Modal review
    public $showReviewModal = false;
    public $reviewStatus = '';
    public $interviewDate = '';
    public $rejectionReason = '';
    public $adminNotes = '';

    public $selectedApplication = null;

    public function updatingFilterStatus(): void { $this->resetPage(); }
    public function updatingFilterVacancy(): void { $this->resetPage(); }

    public function openReview(string $id): void
    {
        $app = Application::with('intern.internProfile', 'vacancy')->findOrFail($id);
        $this->selectedApplication = $app;
        $this->selectedApplicationId = $id;

        $validTransitions = $this->getValidTransitions($app->status);
        $this->reviewStatus = $validTransitions[0] ?? '';

        $this->interviewDate = $app->interview_date?->format('Y-m-d\TH:i') ?? '';
        $this->rejectionReason = $app->rejection_reason ?? '';
        $this->adminNotes = $app->admin_notes ?? '';
        $this->showReviewModal = true;
    }

    public function updateStatus(ApplicationService $service): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $application = Application::findOrFail($this->selectedApplicationId);

        if ($this->reviewStatus === $application->status) {
            $this->dispatch('toast', message: 'Status sama, tidak ada perubahan.', type: 'warning');
            return;
        }

        if ($this->reviewStatus === 'rejected' && empty(trim($this->rejectionReason ?? ''))) {
            $this->dispatch('toast', message: 'Alasan penolakan wajib diisi.', type: 'error');
            return;
        }

        $parsed = null;
        if ($this->reviewStatus === 'interview_scheduled') {
            $date = trim($this->interviewDate ?? '');
            if (empty($date)) {
                $this->dispatch('toast', message: 'Tanggal interview wajib diisi.', type: 'error');
                return;
            }
            try {
                $parsed = \Carbon\Carbon::parse($date);
            } catch (\Exception $e) {
                $this->dispatch('toast', message: 'Format tanggal interview tidak valid.', type: 'error');
                return;
            }
        }

        try {
            if ($this->reviewStatus === 'accepted') {
                $service->accept($application);
                SendApplicationNotificationJob::dispatch(
                    $this->notificationService->sendApplicationDecision($application)
                );
                $this->dispatch('toast', message: 'Lamaran diterima. Record magang otomatis dibuat.', type: 'success');
            } elseif ($this->reviewStatus === 'rejected') {
                $service->reject($application, $this->rejectionReason);
                SendApplicationNotificationJob::dispatch(
                    $this->notificationService->sendApplicationDecision($application)
                );
                $this->dispatch('toast', message: 'Lamaran ditolak.', type: 'success');
            } else {
                $service->updateStatus(
                    $application,
                    $this->reviewStatus,
                    null,
                    $parsed ? $parsed->format('Y-m-d H:i:s') : null
                );

                if ($this->adminNotes) {
                    $application->update(['admin_notes' => $this->adminNotes]);
                }

                match ($this->reviewStatus) {
                    'interview_scheduled' => SendApplicationNotificationJob::dispatch(
                        $this->notificationService->sendInterviewScheduled($application)
                    ),
                    default => SendApplicationNotificationJob::dispatch(
                        $this->notificationService->sendApplicationStatusUpdated($application)
                    ),
                };

                $this->dispatch('toast', message: 'Status lamaran berhasil diperbarui.', type: 'success');
            }
        } catch (\Exception $e) {
            Log::error("[ApplicationReview] updateStatus error: {$e->getMessage()} file={$e->getFile()}:{$e->getLine()}");
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
            return;
        }

        $this->showReviewModal = false;
        $this->selectedApplication = null;
    }

    private function getValidTransitions(string $currentStatus): array
    {
        return match ($currentStatus) {
            'submitted' => ['under_review'],
            'under_review' => ['interview_scheduled', 'rejected'],
            'interview_scheduled' => ['accepted', 'rejected'],
            'rejected' => ['under_review'],
            default => [],
        };
    }

    public function render()
    {
        $applications = Application::with(['intern.internProfile', 'vacancy'])
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterVacancy, fn($q) => $q->where('vacancy_id', $this->filterVacancy))
            ->orderBy('applied_at', 'desc')
            ->paginate(10);

        $vacancies = \App\Models\Vacancy::select('id', 'title')->get();

        return view('livewire.admin.application-review', compact('applications', 'vacancies'));
    }
}
