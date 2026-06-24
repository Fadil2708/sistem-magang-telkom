@extends('layouts.app')
@section('title', 'Detail Magang')
@php $pageTitle = 'Detail Magang'; @endphp

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Detail Magang</h2>
        <p class="page-sub">Informasi magang Anda saat ini</p>
    </div>
</div>

@if(!isset($internship) || !$internship)
<div class="panel" style="padding:40px;text-align:center">
    <x-empty-state icon="ti-clipboard-list" message="Kamu belum memiliki magang aktif." />
    <p class="text-body-sm" style="margin-top:8px">
        Status lamaran kamu bisa dicek di halaman
        <a href="{{ route('intern.applications') }}" class="link-brand">Lamaran Saya</a>.
    </p>
</div>
@else
<div style="max-width:640px">
    <div class="panel" style="overflow:hidden">
        <div class="detail-header">
            <div class="detail-header-row">
                <div>
                    <h3 class="detail-header-title">
                        {{ $internship->vacancy->title ?? '—' }}
                    </h3>
                    <p class="detail-header-sub">
                        {{ $internship->vacancy->division ?? '—' }}
                    </p>
                </div>
                <x-badge :status="$internship->status" />
            </div>
        </div>

        <div class="detail-body">
            <table class="detail-table">
                <tr>
                    <td>Tanggal Mulai</td>
                    <td>{{ ($internship->actual_start_date ?? $internship->vacancy?->start_date)?->format('d M Y') ?? '—' }}</td>
                </tr>
                <tr>
                    <td>Tanggal Selesai</td>
                    <td>{{ ($internship->actual_end_date ?? $internship->vacancy?->end_date)?->format('d M Y') ?? '—' }}</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td><x-badge :status="$internship->status" /></td>
                </tr>
                @if($internship->supervisor)
                <tr>
                    <td>Pembimbing</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px">
                            <x-avatar :name="$internship->supervisor->supervisorProfile->full_name ?? $internship->supervisor->name ?? '—'" :size="28" :font-size="11" />
                            <span>{{ $internship->supervisor->supervisorProfile->full_name ?? $internship->supervisor->name ?? '—' }}</span>
                        </div>
                    </td>
                </tr>
                @endif
                @if($internship->vacancy)
                <tr>
                    <td>Posisi</td>
                    <td>{{ $internship->vacancy->title }} ({{ $internship->vacancy->division ?? '—' }})</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    <div style="margin-top:16px">
        <div class="text-h4" style="margin-bottom:10px">Menu Magang</div>
        <div class="detail-menu-grid">
            <a href="{{ route('intern.logbooks') }}" class="panel detail-menu-item">
                <i class="ti ti-notebook"></i>
                <span>Logbook</span>
            </a>
            <a href="{{ route('intern.reports') }}" class="panel detail-menu-item">
                <i class="ti ti-file-report"></i>
                <span>Laporan</span>
            </a>
            <a href="{{ route('intern.evaluation') }}" class="panel detail-menu-item">
                <i class="ti ti-star"></i>
                <span>Nilai</span>
            </a>
            <a href="{{ route('intern.certificate') }}" class="panel detail-menu-item">
                <i class="ti ti-certificate"></i>
                <span>Sertifikat</span>
            </a>
        </div>
    </div>
</div>
@endif
@endsection
