<?php

namespace App\Livewire\Admin;

use App\Models\Application;
use App\Models\Internship;
use App\Models\Vacancy;
use Livewire\Component;

class DashboardStats extends Component
{
    public $totalInternsActive = 0;
    public $totalVacanciesOpen = 0;
    public $totalApplicationsPending = 0;
    public $totalLogbooksPending = 0;
    public $certificatesThisMonth = 0;

    public function mount(): void
    {
        $this->totalInternsActive = Internship::where('status', 'active')->count();
        $this->totalVacanciesOpen = Vacancy::where('status', 'open')->count();
        $this->totalApplicationsPending = Application::where('status', 'submitted')->count();
        $this->totalLogbooksPending = \App\Models\Logbook::where('validation_status', 'submitted')->count();
        $this->certificatesThisMonth = \App\Models\Certificate::whereMonth('issued_at', now()->month)
            ->whereYear('issued_at', now()->year)
            ->count();
    }

    public function render()
    {
        return view('livewire.admin.dashboard-stats');
    }
}
