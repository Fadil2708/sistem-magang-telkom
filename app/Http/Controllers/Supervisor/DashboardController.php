<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(DashboardService $dashboardService): View
    {
        $stats = $dashboardService->getSupervisorStats(auth()->id());

        return view('supervisor.dashboard', [
            'totalInterns' => $stats['totalInterns'],
            'pendingLogbooksCount' => $stats['pendingLogbooks'],
            'pendingReportsCount' => $stats['pendingReports'],
            'pendingEvaluationsCount' => $stats['pendingEvaluations'],
            'activeInternships' => $stats['activeInternships'],
        ]);
    }
}