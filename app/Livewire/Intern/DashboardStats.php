<?php

namespace App\Livewire\Intern;

use App\Services\DashboardService;
use Livewire\Component;

class DashboardStats extends Component
{
    public string $applicationStatus = '-';
    public string $internshipStatus = '-';
    public bool $logbookToday = false;
    public int $logbookThisMonth = 0;
    public string $reportStatus = '-';
    public bool $hasCertificate = false;
    public string $certificateId = '';

    public function mount(DashboardService $dashboardService): void
    {
        $stats = $dashboardService->getInternStats(auth()->id());

        $this->applicationStatus = $stats['applicationStatus'];
        $this->internshipStatus = $stats['internshipStatus'];
        $this->logbookToday = $stats['logbookToday'];
        $this->logbookThisMonth = $stats['logbookThisMonth'];
        $this->reportStatus = $stats['reportStatus'];
        $this->hasCertificate = $stats['hasCertificate'];
        $this->certificateId = $stats['certificateId'];
    }

    public function render()
    {
        return view('livewire.intern.dashboard-stats');
    }
}