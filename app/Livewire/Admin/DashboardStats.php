<?php

namespace App\Livewire\Admin;

use App\Models\Application;
use App\Models\Internship;
use App\Models\Vacancy;
use App\Models\Logbook;
use App\Models\Certificate;
use Illuminate\Support\Facades\DB;
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

        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
        $monthlyCounts = Application::where('created_at', '>=', $sixMonthsAgo)
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->keyBy(fn($item) => $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT));

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $this->monthlyLabels[] = $date->isoFormat('MMM');
            $this->monthlyApplications[] = $monthlyCounts[$date->format('Y-m')]->count ?? 0;
        }
    }

    public function render()
    {
        return view('livewire.admin.dashboard-stats');
    }
}
