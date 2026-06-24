<?php

namespace App\Livewire\Admin;

use App\Models\Internship;
use App\Services\EvaluationService;
use Livewire\Component;
use Livewire\WithPagination;

class InternshipList extends Component
{
    use WithPagination;

    public $filterStatus = '';
    public $confirmingAction = null;
    public $actionType = '';

    public $editingInternshipId = null;
    public $showDatesModal = false;
    public $actual_start_date = '';
    public $actual_end_date = '';

    public $confirmingLockId = null;

    public function confirmLock(string $id): void
    {
        $this->confirmingLockId = $id;
    }

    public function lockEvaluation(EvaluationService $service): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $internship = Internship::with('evaluation')->findOrFail($this->confirmingLockId);

        if (!$internship->evaluation) {
            $this->dispatch('toast', message: 'Penilaian belum diisi oleh pembimbing.', type: 'error');
            $this->confirmingLockId = null;
            return;
        }

        try {
            $service->lock($internship->evaluation);
            $this->dispatch('toast', message: 'Penilaian berhasil dikunci.', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }

        $this->confirmingLockId = null;
    }

    public function updatingFilterStatus(): void { $this->resetPage(); }

    public function confirmAction(string $id, string $type): void
    {
        $this->confirmingAction = $id;
        $this->actionType = $type;
    }

    public function executeAction(): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $internship = Internship::findOrFail($this->confirmingAction);

        if ($internship->status !== 'active') {
            $this->dispatch('toast', message: 'Hanya magang dengan status aktif yang bisa diubah.', type: 'error');
            $this->confirmingAction = null;
            return;
        }

        $status = $this->actionType === 'terminate' ? 'terminated' : 'completed';

        $internship->update(['status' => $status]);

        $label = $status === 'terminated' ? 'diterminasi' : 'diselesaikan';
        $this->dispatch('toast', message: "Status magang berhasil {$label}.", type: 'success');
        $this->confirmingAction = null;
    }

    public function editDates(string $id): void
    {
        $internship = Internship::findOrFail($id);
        $this->editingInternshipId = $id;
        $this->actual_start_date = $internship->actual_start_date?->format('Y-m-d') ?? '';
        $this->actual_end_date = $internship->actual_end_date?->format('Y-m-d') ?? '';
        $this->showDatesModal = true;
    }

    public function saveDates(): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $this->validate([
            'actual_start_date' => 'nullable|date',
            'actual_end_date' => 'nullable|date|after_or_equal:actual_start_date',
        ]);

        $internship = Internship::findOrFail($this->editingInternshipId);
        $internship->update([
            'actual_start_date' => $this->actual_start_date ?: null,
            'actual_end_date' => $this->actual_end_date ?: null,
        ]);

        $this->showDatesModal = false;
        $this->editingInternshipId = null;
        $this->dispatch('toast', message: 'Tanggal aktual magang berhasil diperbarui.', type: 'success');
    }

    public function render()
    {
        $internships = Internship::with(['intern.internProfile', 'supervisor.supervisorProfile', 'vacancy', 'evaluation'])
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.internship-list', compact('internships'));
    }
}
