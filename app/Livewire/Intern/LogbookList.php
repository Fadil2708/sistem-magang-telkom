<?php

namespace App\Livewire\Intern;

use App\Jobs\SendLogbookNotificationJob;
use App\Models\Internship;
use App\Models\Logbook;
use App\Services\NotificationService;
use Livewire\Component;
use Livewire\WithPagination;

class LogbookList extends Component
{
    use WithPagination;

    public ?string $filterStatus = '';
    public ?Internship $internship = null;
    public bool $hasActiveInternship = false;

    private NotificationService $notificationService;

    public function boot(NotificationService $notificationService): void
    {
        $this->notificationService = $notificationService;
    }

    public function mount(): void
    {
        $this->internship = Internship::where('intern_id', auth()->id())
            ->where('status', 'active')
            ->latest()
            ->first();

        $this->hasActiveInternship = $this->internship !== null;
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function delete(string $id): void
    {
        $logbook = Logbook::where('intern_id', auth()->id())
            ->where('validation_status', 'draft')
            ->findOrFail($id);

        $logbook->delete();
        $this->resetPage();
        session()->flash('success', 'Logbook berhasil dihapus.');
    }

    public function submit(string $id): void
    {
        $logbook = Logbook::where('intern_id', auth()->id())
            ->where('validation_status', 'draft')
            ->findOrFail($id);

        $logbook->update(['validation_status' => 'submitted']);

        SendLogbookNotificationJob::dispatch(
            $this->notificationService->sendNewLogbookToSupervisor($logbook)
        );

        $this->resetPage();
        session()->flash('success', 'Logbook berhasil dikirim ke supervisor.');
    }

    public function render()
    {
        $logbooks = collect();
        if ($this->internship) {
            $logbooks = Logbook::where('internship_id', $this->internship->id)
                ->when($this->filterStatus, fn($q) => $q->where('validation_status', $this->filterStatus))
                ->orderBy('activity_date', 'desc')
                ->paginate(10);
        }

        return view('livewire.intern.logbook-list', [
            'logbooks' => $logbooks,
            'internship' => $this->internship,
        ]);
    }
}
