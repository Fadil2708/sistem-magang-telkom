<?php

namespace App\Livewire\Admin;

use App\Models\Application;
use App\Models\Internship;
use App\Models\Vacancy;
use App\Models\Logbook;
use App\Models\Certificate;
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

    public function mount(): void
    {
        $this->totalInternsActive = Internship::where('status', 'active')->count();
        $this->totalVacanciesOpen = Vacancy::where('status', 'open')->count();
        $this->totalApplicationsPending = Application::where('status', 'submitted')->count();
        $this->totalLogbooksPending = Logbook::where('validation_status', 'submitted')->count();
        $this->certificatesThisMonth = Certificate::whereMonth('issued_at', now()->month)
            ->whereYear('issued_at', now()->year)
            ->count();

        $this->totalInternships = Internship::count();
        $this->completedInternships = Internship::where('status', 'completed')->count();
        $this->terminatedInternships = Internship::where('status', 'terminated')->count();

        $this->quotaTotal = Vacancy::sum('quota');
        $this->quotaUsed = Internship::whereIn('status', ['active', 'completed'])->count();

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $this->monthlyLabels[] = $date->isoFormat('MMM');
            $this->monthlyApplications[] = Application::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
        }
    }

    public function render()
    {
        return view('livewire.admin.dashboard-stats');
    }
}
