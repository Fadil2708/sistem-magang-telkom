<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\FinalReport;
use App\Models\Internship;
use App\Models\Logbook;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $supervisorId = auth()->id();
        $internshipIds = Internship::where('supervisor_id', $supervisorId)->pluck('id');

        $pendingLogbooksCount = Logbook::whereIn('internship_id', $internshipIds)
            ->where('validation_status', 'submitted')
            ->count();

        $pendingReportsCount = FinalReport::whereIn('internship_id', $internshipIds)
            ->where('supervisor_approval', 'pending')
            ->count();

        $recentLogbooks = Logbook::whereIn('internship_id', $internshipIds)
            ->where('validation_status', 'submitted')
            ->with('intern.internProfile', 'internship.vacancy')
            ->latest('activity_date')
            ->limit(5)
            ->get();

        $name = auth()->user()->supervisorProfile->full_name ?? auth()->user()->email ?? 'Pembimbing';

        return view('supervisor.dashboard', compact(
            'pendingLogbooksCount', 'pendingReportsCount', 'recentLogbooks', 'name'
        ));
    }
}
