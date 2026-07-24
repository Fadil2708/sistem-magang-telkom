<?php

namespace App\Livewire\Admin;

use App\Services\DashboardService;
use Livewire\Component;

class DashboardStats extends Component
{
    public int $totalInternsActive = 0;
    public int $totalVacanciesOpen = 0;
    public int $totalApplicationsPending = 0;
    public int $totalLogbooksPending = 0;
    public int $certificatesThisMonth = 0;

    public array $monthlyApplications = [];
    public array $monthlyLabels = [];
    public int $totalInternships = 0;
    public int $completedInternships = 0;
    public int $terminatedInternships = 0;
    public int $quotaUsed = 0;
    public int $quotaTotal = 0;
    public int $activeInternships = 0;
    public array $statsCards = [];
    public array $internshipStats = [];

    public function mount(DashboardService $dashboardService): void
    {
        $stats = $dashboardService->getAdminStats();

        $this->totalInternsActive = $stats['totalInternsActive'];
        $this->totalVacanciesOpen = $stats['totalVacanciesOpen'];
        $this->totalApplicationsPending = $stats['totalApplicationsPending'];
        $this->totalLogbooksPending = $stats['totalLogbooksPending'];
        $this->certificatesThisMonth = $stats['certificatesThisMonth'];
        $this->totalInternships = $stats['totalInternships'];
        $this->completedInternships = $stats['completedInternships'];
        $this->terminatedInternships = $stats['terminatedInternships'];
        $this->totalQuota = $stats['totalQuota'];
        $this->activeInternships = $stats['activeInternships'];
        $this->monthlyLabels = $stats['monthlyLabels'];
        $this->monthlyApplications = $stats['monthlyData'];
    }

    public function render()
    {
        return view('livewire.admin.dashboard-stats');
    }
}