<?php

namespace App\Livewire\Admin;

use App\Services\InternshipService;
use Livewire\Component;
use Livewire\WithPagination;

class SupervisorMapping extends Component
{
    use WithPagination;

    public $filterStatus = 'active';

    private InternshipService $internshipService;

    public function boot(InternshipService $internshipService): void
    {
        $this->internshipService = $internshipService;
    }

    public function updatingFilterStatus(): void { $this->resetPage(); }

    public function assignSupervisor(string $internshipId, string $supervisorId): void
    {
        $this->internshipService->assignSupervisor($internshipId, $supervisorId);
        $this->dispatch('toast', message: 'Supervisor berhasil ditetapkan.', type: 'success');
    }

    public function render()
    {
        $internships = $this->internshipService->getSupervisorMappedList($this->filterStatus);
        $supervisors = $this->internshipService->getSupervisors();
        return view('livewire.admin.supervisor-mapping', compact('internships', 'supervisors'));
    }
}