@extends('layouts.app')
@section('title', 'Dashboard Peserta')
@php $pageTitle = 'Dashboard'; @endphp

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Dashboard Peserta</h2>
        <p class="page-sub">Selamat datang, {{ auth()->user()->internProfile?->full_name ?? 'Peserta' }}</p>
    </div>
</div>

<livewire:intern.dashboard-stats />

<div style="margin-top:16px">
    <div class="text-h3" style="margin-bottom:10px">Menu Cepat</div>
    <div class="detail-menu-grid">
        <a href="{{ route('intern.internship') }}" class="panel detail-menu-item">
            <i class="ti ti-briefcase"></i>
            <span>Magang Saya</span>
        </a>
        <a href="{{ route('intern.logbooks') }}" class="panel detail-menu-item">
            <i class="ti ti-notebook"></i>
            <span>Logbook</span>
        </a>
        <a href="{{ route('intern.reports') }}" class="panel detail-menu-item">
            <i class="ti ti-file-report"></i>
            <span>Laporan</span>
        </a>
        <a href="{{ route('intern.applications') }}" class="panel detail-menu-item">
            <i class="ti ti-file-description"></i>
            <span>Lamaran</span>
        </a>
        <a href="{{ route('intern.evaluation') }}" class="panel detail-menu-item">
            <i class="ti ti-star"></i>
            <span>Nilai</span>
        </a>
        <a href="{{ route('intern.certificate') }}" class="panel detail-menu-item">
            <i class="ti ti-certificate"></i>
            <span>Sertifikat</span>
        </a>
        <a href="{{ route('intern.testimonials.create') }}" class="panel detail-menu-item">
            <i class="ti ti-message-star"></i>
            <span>Testimoni</span>
        </a>
        <a href="{{ route('intern.profile') }}" class="panel detail-menu-item">
            <i class="ti ti-user"></i>
            <span>Profile</span>
        </a>
    </div>
</div>
@endsection
