<?php

namespace App\Livewire\Supervisor;

use App\Models\Internship;
use App\Services\InternshipService;
use Livewire\Component;
use Livewire\WithPagination;

class MyInterns extends Component
{
    use WithPagination;

    public $filterStatus = 'active';

    private InternshipService $internshipService;

    public function boot(InternshipService $internshipService): void
    {
        $this->internshipService = $internshipService;
    }

    public function updatingFilterStatus(): void { $this->resetPage(); }

    public function render()
    {
        $internships = $this->internshipService->getSupervisorInterns(auth()->id(), $this->filterStatus);
        return view('livewire.supervisor.my-interns', compact('internships'));
    }
}