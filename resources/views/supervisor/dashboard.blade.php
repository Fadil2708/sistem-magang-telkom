@extends('layouts.app')
@section('title', 'Dashboard Pembimbing')
@php $pageTitle = 'Dashboard Pembimbing'; @endphp

@php
    use App\Models\Internship;
    use App\Models\Logbook;
    use App\Models\FinalReport;

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
@endphp

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Dashboard Pembimbing</h2>
        <p class="page-sub">Selamat datang, {{ $name }}</p>
    </div>
</div>

<livewire:supervisor.dashboard-stats />

<div class="stat-card-grid">
    <a href="{{ route('supervisor.logbooks') }}" class="stat-card red">
        <div class="stat-card-icon red">
            <i class="ti ti-notebook"></i>
        </div>
        <div>
            <div class="stat-card-value red">{{ $pendingLogbooksCount }}</div>
            <div class="stat-card-label red">Logbook Perlu Review</div>
        </div>
    </a>
    <a href="{{ route('supervisor.reports') }}" class="stat-card blue">
        <div class="stat-card-icon blue">
            <i class="ti ti-file-description"></i>
        </div>
        <div>
            <div class="stat-card-value blue">{{ $pendingReportsCount }}</div>
            <div class="stat-card-label blue">Laporan Menunggu</div>
        </div>
    </a>
</div>

<div class="panel activity-wrap">
    <div class="activity-header">
        <h3>Aktivitas Terbaru</h3>
        @if($pendingLogbooksCount > 0)
        <a href="{{ route('supervisor.logbooks') }}">Lihat Semua</a>
        @endif
    </div>
    <div>
        @forelse($recentLogbooks as $logbook)
        <a href="{{ route('supervisor.logbooks') }}" class="activity-item">
            <div class="activity-icon-box">
                <i class="ti ti-notebook"></i>
            </div>
            <div class="activity-text-wrap">
                <div class="activity-text">
                    {{ $logbook->intern?->internProfile?->full_name ?? $logbook->intern?->email ?? '' }}
                </div>
                <div class="activity-meta">
                    {{ $logbook->activity_date?->isoFormat('D MMM') ?? '—' }} &middot; {{ Str::limit($logbook->activities, 50) }}
                </div>
            </div>
            <span class="badge submitted">Terkirim</span>
        </a>
        @empty
        <div class="activity-empty">
            <i class="ti ti-circle-check activity-empty-icon"></i>
            <p class="activity-empty-text">Semua logbook sudah direview. Tidak ada aktivitas tertunda.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
