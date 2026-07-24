<?php

namespace App\Livewire\Admin;

use App\Services\InternshipService;
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

    private InternshipService $internshipService;

    public function boot(InternshipService $internshipService): void
    {
        $this->internshipService = $internshipService;
    }

    public function updatingFilterStatus(): void { $this->resetPage(); }

    public function confirmAction(string $id, string $type): void
    {
        $this->confirmingAction = $id;
        $this->actionType = $type;
    }

    public function executeAction(): void
    {
        try {
            $this->internshipService->updateStatus($this->confirmingAction, $this->actionType);
            $this->dispatch('toast', message: 'Status magang berhasil diperbarui.', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }
        $this->cancelAction();
    }

    public function cancelAction(): void
    {
        $this->confirmingAction = null;
        $this->actionType = '';
    }

    public function editDates(string $id): void
    {
        $internship = \App\Models\Internship::findOrFail($id);
        $this->editingInternshipId = $id;
        $this->actual_start_date = $internship->actual_start_date?->format('Y-m-d') ?? '';
        $this->actual_end_date = $internship->actual_end_date?->format('Y-m-d') ?? '';
        $this->showDatesModal = true;
    }

    public function saveDates(): void
    {
        $this->validate([
            'actual_start_date' => 'nullable|date',
            'actual_end_date' => 'nullable|date|after_or_equal:actual_start_date',
        ]);

        $this->internshipService->updateDates(
            $this->editingInternshipId,
            $this->actual_start_date ?: null,
            $this->actual_end_date ?: null
        );

        $this->showDatesModal = false;
        $this->editingInternshipId = null;
        $this->dispatch('toast', message: 'Tanggal magang berhasil diperbarui.', type: 'success');
    }

    public function render()
    {
        $internships = $this->internshipService->getAdminPaginatedList($this->filterStatus);
        return view('livewire.admin.internship-list', compact('internships'));
    }
}