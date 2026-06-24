<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Certificate;
use App\Models\FinalReport;
use App\Models\Internship;
use App\Models\Logbook;
use App\Models\Vacancy;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    use ApiResponse;

    public function stats(): JsonResponse
    {
        $user = auth()->user();

        return match ($user->role) {
            'admin' => $this->success(Cache::remember('dashboard.stats.admin', 60, fn() => [
                'total_intern_aktif' => Internship::where('status', 'active')->count(),
                'total_lowongan_buka' => Vacancy::where('status', 'open')->count(),
                'lamaran_pending_review' => Application::where('status', 'submitted')->count(),
                'logbook_pending_approval' => Logbook::where('validation_status', 'submitted')->count(),
                'sertifikat_bulan_ini' => Certificate::whereMonth('issued_at', now()->month)
                    ->whereYear('issued_at', now()->year)
                    ->count(),
            ])),

            'supervisor' => $this->success(Cache::remember('dashboard.stats.supervisor.'.$user->id, 60, fn() => [
                'peserta_aktif' => Internship::where('supervisor_id', $user->id)
                    ->where('status', 'active')
                    ->count(),
                'logbook_pending_review' => Logbook::whereHas('internship', fn($q) =>
                    $q->where('supervisor_id', $user->id)
                )->where('validation_status', 'submitted')->count(),
                'laporan_pending_approval' => FinalReport::whereHas('internship', fn($q) =>
                    $q->where('supervisor_id', $user->id)
                )->where('supervisor_approval', 'pending')->count(),
            ])),

            'intern' => $this->success(Cache::remember('dashboard.stats.intern.'.$user->id, 60, fn() => [
                'status_lamaran' => Application::where('intern_id', $user->id)
                    ->latest()
                    ->first()?->status,
                'status_magang' => Internship::where('intern_id', $user->id)
                    ->latest()
                    ->first()?->status,
                'logbook_hari_ini' => Logbook::where('intern_id', $user->id)
                    ->whereDate('activity_date', today())
                    ->exists(),
                'status_laporan_akhir' => FinalReport::whereHas('internship', fn($q) =>
                    $q->where('intern_id', $user->id)
                )->first()?->supervisor_approval,
                'ada_sertifikat' => Certificate::where('intern_id', $user->id)->exists(),
            ])),

            default => $this->error('Role tidak dikenal.', 403),
        };
    }
}
