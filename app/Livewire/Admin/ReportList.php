<?php

namespace App\Livewire\Admin;

use App\Models\FinalReport;
use Livewire\Component;
use Livewire\WithPagination;

class ReportList extends Component
{
    use WithPagination;

    public $filterStatus = '';

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $reports = FinalReport::with([
            'intern.internProfile',
            'internship.vacancy',
            'internship.supervisor.supervisorProfile',
        ])
            ->when($this->filterStatus, fn($q) => $q->where('supervisor_approval', $this->filterStatus))
            ->latest('submitted_at')
            ->paginate(10);

        return view('livewire.admin.report-list', compact('reports'));
    }
}
