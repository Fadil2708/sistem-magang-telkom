<?php

namespace App\Livewire\Supervisor;

use App\Jobs\SendLogbookNotificationJob;
use App\Models\Internship;
use App\Models\Logbook;
use App\Services\NotificationService;
use Livewire\Component;
use Livewire\WithPagination;

class LogbookReview extends Component
{
    use WithPagination;

    public string $filterStatus = 'submitted';
    public ?string $logbookId = null;
    public bool $showRevisionModal = false;
    public string $revisionNotes = '';
    public array $selectedLogbooks = [];
    public ?string $internId = null;

    private NotificationService $notificationService;

    public function mount(): void
    {
        $this->internId = request()->query('intern_id');
    }

    public function boot(NotificationService $notificationService): void
    {
        $this->notificationService = $notificationService;
    }

    private function toast(string $message, string $type = 'success'): void
    {
        $this->dispatch('toast', message: $message, type: $type);
    }

    private function allSubmittedIds(): array
    {
        return Logbook::whereHas('internship', fn($q) =>
            $q->where('supervisor_id', auth()->id())
                ->when($this->internId, fn($q) => $q->where('intern_id', $this->internId))
        )
            ->where('validation_status', 'submitted')
            ->pluck('id')
            ->toArray();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
        $this->selectedLogbooks = [];
    }

    public function approve(string $id): void
    {
        try {
            $logbook = Logbook::whereHas('internship', fn($q) =>
                $q->where('supervisor_id', auth()->id())
            )->with(['intern.internProfile'])->findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->toast('Logbook tidak ditemukan.', 'error');
            return;
        }

        if ($logbook->validation_status !== 'submitted') {
            $this->toast('Hanya logbook dengan status submitted yang bisa disetujui.', 'error');
            return;
        }

        $logbook->update([
            'validation_status' => 'approved',
            'reviewed_at' => now(),
        ]);

        SendLogbookNotificationJob::dispatch(
            $this->notificationService->sendLogbookApproved($logbook)
        );

        $this->toast('Logbook berhasil disetujui.', 'success');
    }

    public function toggleSelectAll(): void
    {
        $allIds = $this->allSubmittedIds();
        $allSelected = empty(array_diff($allIds, $this->selectedLogbooks));
        $this->selectedLogbooks = $allSelected
            ? []
            : $allIds;
    }

    public function bulkApprove(): void
    {
        if (empty($this->selectedLogbooks)) {
            $this->toast('Pilih logbook yang ingin disetujui.', 'error');
            return;
        }

        $approved = 0;
        $skipped = 0;

        $logbooks = Logbook::whereIn('id', $this->selectedLogbooks)
            ->whereHas('internship', fn($q) =>
                $q->where('supervisor_id', auth()->id())
            )->with(['intern.internProfile'])->get();

        $foundIds = $logbooks->pluck('id')->toArray();
        $skipped += count($this->selectedLogbooks) - $logbooks->count();

        foreach ($logbooks as $logbook) {
            if ($logbook->validation_status !== 'submitted') {
                $skipped++;
                continue;
            }

            $logbook->update([
                'validation_status' => 'approved',
                'reviewed_at' => now(),
            ]);

            SendLogbookNotificationJob::dispatch(
                $this->notificationService->sendLogbookApproved($logbook)
            );

            $approved++;
        }

        $this->selectedLogbooks = [];

        if ($approved > 0) {
            $this->toast("{$approved} logbook berhasil disetujui.", 'success');
        } else {
            $this->toast('Tidak ada logbook yang bisa disetujui.', 'error');
        }
    }

    public function openRevision(string $id): void
    {
        $this->logbookId = $id;
        $this->revisionNotes = '';
        $this->showRevisionModal = true;
    }

    public function requestRevision(): void
    {
        $this->validate(['revisionNotes' => 'required|string|max:500']);

        try {
            $logbook = Logbook::whereHas('internship', fn($q) =>
                $q->where('supervisor_id', auth()->id())
            )->with(['intern.internProfile'])->findOrFail($this->logbookId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->toast('Logbook tidak ditemukan.', 'error');
            $this->showRevisionModal = false;
            return;
        }

        if ($logbook->validation_status !== 'submitted') {
            $this->toast('Hanya logbook dengan status submitted yang bisa direview.', 'error');
            $this->showRevisionModal = false;
            return;
        }

        $logbook->update([
            'validation_status' => 'revision_requested',
            'supervisor_notes' => $this->revisionNotes,
            'reviewed_at' => now(),
        ]);

        SendLogbookNotificationJob::dispatch(
            $this->notificationService->sendLogbookRevisionRequested($logbook)
        );

        $this->showRevisionModal = false;
        $this->toast('Revisi logbook telah diminta.', 'success');
    }

    public function render()
    {
        $supervisorId = auth()->id();

        $logbooks = Logbook::whereHas('internship', fn($q) =>
            $q->where('supervisor_id', $supervisorId)
                ->when($this->internId, fn($q) => $q->where('intern_id', $this->internId))
        )
            ->with(['intern.internProfile', 'internship.vacancy'])
            ->when($this->filterStatus, fn($q) => $q->where('validation_status', $this->filterStatus))
            ->orderBy('activity_date', 'desc')
            ->paginate(10);

        $totalSubmitted = Logbook::whereHas('internship', fn($q) =>
            $q->where('supervisor_id', $supervisorId)
                ->when($this->internId, fn($q) => $q->where('intern_id', $this->internId))
        )
            ->where('validation_status', 'submitted')
            ->count();

        return view('livewire.supervisor.logbook-review', compact('logbooks', 'totalSubmitted'));
    }
}
