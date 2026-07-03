<?php

namespace App\Livewire\Supervisor;

use App\Models\Logbook;
use App\Notifications\LogbookNotification;
use App\Services\LogbookService;
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

    private LogbookService $logbookService;

    public function boot(LogbookService $logbookService): void
    {
        $this->logbookService = $logbookService;
    }

    public function mount(): void
    {
        $this->internId = request()->query('intern_id');
    }

    private function toast(string $message, string $type = 'success'): void
    {
        $this->dispatch('toast', message: $message, type: $type);
    }

    private function submittedIdsQuery()
    {
        return Logbook::scopeForSupervisor(auth()->id())
            ->when($this->internId, fn($q) => $q->where('intern_id', $this->internId))
            ->where('validation_status', 'submitted');
    }

    private function allSubmittedIds(): array
    {
        return $this->submittedIdsQuery()->pluck('id')->toArray();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
        $this->selectedLogbooks = [];
    }

    public function approve(string $id): void
    {
        try {
            $logbook = Logbook::findOrFail($id);
            $this->logbookService->review($logbook, auth()->user(), 'approved');
            $logbook->intern->notify(new LogbookNotification($logbook, 'approved'));
            $this->toast('Logbook berhasil disetujui.', 'success');
        } catch (\Exception $e) {
            $this->toast($e->getMessage(), 'error');
        }
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
        $errors = 0;

        $logbooks = Logbook::whereIn('id', $this->selectedLogbooks)->get();

        foreach ($logbooks as $logbook) {
            try {
                $this->logbookService->review($logbook, auth()->user(), 'approved');
                $logbook->intern->notify(new LogbookNotification($logbook, 'approved'));
                $approved++;
            } catch (\Exception $e) {
                $errors++;
            }
        }

        $this->selectedLogbooks = [];

        if ($approved > 0) {
            $this->toast("{$approved} logbook berhasil disetujui." . ($errors > 0 ? " {$errors} gagal." : ''), 'success');
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
            $logbook = Logbook::findOrFail($this->logbookId);
            $this->logbookService->review($logbook, auth()->user(), 'revision_requested', $this->revisionNotes);
            $logbook->intern->notify(new LogbookNotification($logbook, 'revision_requested'));
            $this->showRevisionModal = false;
            $this->toast('Revisi logbook telah diminta.', 'success');
        } catch (\Exception $e) {
            $this->toast($e->getMessage(), 'error');
            $this->showRevisionModal = false;
        }
    }

    public function render()
    {
        $supervisorId = auth()->id();

        $logbooks = Logbook::scopeForSupervisor($supervisorId)
            ->with(['intern.internProfile', 'internship.vacancy'])
            ->when($this->internId, fn($q) => $q->where('intern_id', $this->internId))
            ->when($this->filterStatus, fn($q) => $q->where('validation_status', $this->filterStatus))
            ->orderBy('activity_date', 'desc')
            ->paginate(10);

        $totalSubmitted = $this->submittedIdsQuery()->count();

        return view('livewire.supervisor.logbook-review', compact('logbooks', 'totalSubmitted'));
    }
}
