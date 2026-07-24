<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Certificate;
use App\Models\FinalReport;
use App\Models\Internship;
use App\Models\Logbook;
use App\Models\Vacancy;

class DashboardService
{
    public function getAdminStats(): array
    {
        $internsActive = Internship::where('status', 'active')->count();
        $vacanciesOpen = Vacancy::where('status', 'open')->count();
        $applicationsPending = Application::where('status', 'submitted')->count();
        $logbooksPending = Logbook::where('validation_status', 'submitted')->count();
        $certificatesThisMonth = Certificate::whereMonth('issued_at', now()->month)
            ->whereYear('issued_at', now()->year)
            ->count();

        $totalInternships = Internship::count();
        $completedInternships = Internship::where('status', 'completed')->count();
        $terminatedInternships = Internship::where('status', 'terminated')->count();

        $totalQuota = Vacancy::sum('quota');
        $activeInternships = Internship::whereIn('status', ['active', 'extended'])->count();

        $monthlyApplications = Application::where('created_at', '>=', now()->subMonths(12))
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as total')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $monthlyLabels = collect();
        $monthlyData = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $label = $date->format('M Y');
            $monthlyLabels->push($label);

            $found = $monthlyApplications->firstWhere(function ($item) use ($date) {
                return $item->year == $date->year && $item->month == $date->month;
            });
            $monthlyData->push($found ? (int) $found->total : 0);
        }

        return [
            'totalInternsActive' => $internsActive,
            'totalVacanciesOpen' => $vacanciesOpen,
            'totalApplicationsPending' => $applicationsPending,
            'totalLogbooksPending' => $logbooksPending,
            'certificatesThisMonth' => $certificatesThisMonth,
            'totalInternships' => $totalInternships,
            'completedInternships' => $completedInternships,
            'terminatedInternships' => $terminatedInternships,
            'totalQuota' => $totalQuota,
            'activeInternships' => $activeInternships,
            'monthlyLabels' => $monthlyLabels->toArray(),
            'monthlyData' => $monthlyData->toArray(),
        ];
    }

    public function getInternStats(string $userId): array
    {
        $application = Application::where('intern_id', $userId)->latest()->first();
        $internship = Internship::where('intern_id', $userId)->latest()->first();

        $logbookToday = false;
        $logbookThisMonth = 0;
        $report = null;
        $certificate = null;

        if ($internship) {
            $logbookToday = Logbook::where('intern_id', $userId)
                ->where('activity_date', today())
                ->exists();
            $logbookThisMonth = Logbook::where('intern_id', $userId)
                ->whereMonth('activity_date', now()->month)
                ->whereYear('activity_date', now()->year)
                ->count();
            $report = FinalReport::where('intern_id', $userId)->latest()->first();
            $certificate = Certificate::where('intern_id', $userId)->latest()->first();
        }

        return [
            'applicationStatus' => $application?->status ?? '-',
            'internshipStatus' => $internship?->status ?? '-',
            'logbookToday' => $logbookToday,
            'logbookThisMonth' => $logbookThisMonth,
            'reportStatus' => $report?->supervisor_approval ?? '-',
            'hasCertificate' => $certificate !== null,
            'certificateId' => $certificate?->id ?? '',
        ];
    }

    public function getSupervisorStats(string $userId): array
    {
        $totalInterns = Internship::where('supervisor_id', $userId)
            ->whereIn('status', ['active', 'extended'])
            ->count();

        $pendingLogbooks = Logbook::whereHas('internship', fn($q) => $q->where('supervisor_id', $userId))
            ->where('validation_status', 'submitted')
            ->count();

        $pendingReports = FinalReport::whereHas('internship', fn($q) => $q->where('supervisor_id', $userId))
            ->where('supervisor_approval', 'pending')
            ->count();

        $pendingEvaluations = \App\Models\Evaluation::where('supervisor_id', $userId)
            ->whereNull('evaluated_at')
            ->count();

        $activeInternships = Internship::with(['intern.internProfile', 'vacancy'])
            ->where('supervisor_id', $userId)
            ->whereIn('status', ['active', 'extended'])
            ->get();

        return [
            'totalInterns' => $totalInterns,
            'pendingLogbooks' => $pendingLogbooks,
            'pendingReports' => $pendingReports,
            'pendingEvaluations' => $pendingEvaluations,
            'activeInternships' => $activeInternships,
        ];
    }
}