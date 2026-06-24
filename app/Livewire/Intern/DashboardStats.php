<?php

namespace App\Livewire\Intern;

use App\Models\Application;
use App\Models\Certificate;
use App\Models\FinalReport;
use App\Models\Internship;
use App\Models\Logbook;
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

    public function mount(): void
    {
        $userId = auth()->id();

        $latestApp = Application::where('intern_id', $userId)->latest()->first();
        $this->applicationStatus = $latestApp?->status ?? '-';

        $latestInternship = Internship::where('intern_id', $userId)->latest()->first();
        $this->internshipStatus = $latestInternship?->status ?? '-';

        if ($latestInternship) {
            $this->logbookToday = Logbook::where('intern_id', $userId)
                ->where('internship_id', $latestInternship->id)
                ->whereDate('activity_date', today())
                ->exists();

            $this->logbookThisMonth = Logbook::where('intern_id', $userId)
                ->where('internship_id', $latestInternship->id)
                ->whereMonth('activity_date', now()->month)
                ->whereYear('activity_date', now()->year)
                ->count();

            $report = FinalReport::where('internship_id', $latestInternship->id)->first();
            $this->reportStatus = $report?->supervisor_approval ?? '-';

            $cert = Certificate::where('intern_id', $userId)->where('internship_id', $latestInternship->id)->first();
            $this->hasCertificate = $cert !== null;
            $this->certificateId = $cert?->id ?? '';
        }
    }

    public function render()
    {
        return view('livewire.intern.dashboard-stats');
    }
}
