<?php

namespace App\Livewire\Supervisor;

use App\Models\FinalReport;
use App\Models\Internship;
use App\Models\Logbook;
use Livewire\Component;

class DashboardStats extends Component
{
    public $totalInterns = 0;
    public $pendingLogbooks = 0;
    public $approvedLogbooks = 0;
    public $pendingReports = 0;

    public function mount(): void
    {
        $supervisorId = auth()->id();

        $this->totalInterns = Internship::where('supervisor_id', $supervisorId)
            ->where('status', 'active')
            ->count();

        $this->pendingLogbooks = Logbook::whereHas('internship', fn($q) =>
            $q->where('supervisor_id', $supervisorId)
        )->where('validation_status', 'submitted')->count();

        $this->approvedLogbooks = Logbook::whereHas('internship', fn($q) =>
            $q->where('supervisor_id', $supervisorId)
        )->where('validation_status', 'approved')->count();

        $this->pendingReports = FinalReport::whereHas('internship', fn($q) =>
            $q->where('supervisor_id', $supervisorId)
        )->where('supervisor_approval', 'pending')->count();
    }

    public function render()
    {
        return view('livewire.supervisor.dashboard-stats');
    }
}
