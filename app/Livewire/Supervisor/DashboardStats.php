<?php

namespace App\Livewire\Supervisor;

use App\Models\FinalReport;
use App\Models\Internship;
use App\Models\Logbook;
use Livewire\Component;

class DashboardStats extends Component
{
    public int $totalInterns = 0;
    public int $pendingLogbooks = 0;
    public int $approvedLogbooks = 0;
    public int $pendingReports = 0;
    public int $totalLogbooks = 0;
    public int $revisionLogbooks = 0;

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

        $this->revisionLogbooks = Logbook::whereHas('internship', fn($q) =>
            $q->where('supervisor_id', $supervisorId)
        )->where('validation_status', 'revision_requested')->count();

        $this->totalLogbooks = Logbook::whereHas('internship', fn($q) =>
            $q->where('supervisor_id', $supervisorId)
        )->count();

        $this->pendingReports = FinalReport::whereHas('internship', fn($q) =>
            $q->where('supervisor_id', $supervisorId)
        )->where('supervisor_approval', 'pending')->count();
    }

    public function render()
    {
        return view('livewire.supervisor.dashboard-stats');
    }
}
