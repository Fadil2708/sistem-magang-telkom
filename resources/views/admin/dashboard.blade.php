@extends('layouts.app')
@section('title', 'Dashboard Admin')
@php $pageTitle = 'Dashboard Admin'; @endphp

@section('content')

{{-- Hero Greeting --}}
<div class="panel panel-accent p-5 mb-5">
    <div class="flex-between">
        <div>
            <h2 class="text-hero">Selamat datang, {{ auth()->user()->displayName() ?? 'Admin' }}</h2>
            <p class="text-caption mt-1">
                {{ now()->translatedFormat('l, d F Y') }} &middot;
                Pantau dan kelola seluruh aktivitas magang dari sini.
            </p>
        </div>
        <div class="flex gap-2 shrink-0">
            <a href="{{ route('admin.vacancies.create') }}" class="btn-primary text-xs px-3.5 py-2">
                <i class="ti ti-plus"></i> Lowongan Baru
            </a>
            <a href="{{ route('admin.applications.index') }}" class="btn-secondary text-xs px-3.5 py-2">
                <i class="ti ti-eye"></i> Review Lamaran
            </a>
        </div>
    </div>
</div>

{{-- Stats Cards --}}
<livewire:admin.dashboard-stats />

{{-- Quick Actions + Info Grid --}}
<div class="grid grid-cols-2 gap-4 mt-5">

    {{-- Quick Actions --}}
    <div class="panel p-5">
        <div class="flex-between mb-3.5">
            <h3 class="text-h4">Aksi Cepat</h3>
        </div>
        <div class="flex flex-col gap-2">
            <a href="{{ route('admin.vacancies.create') }}" class="quick-action">
                <div class="quick-action-icon icon-wrap-brand">
                    <i class="ti ti-plus"></i>
                </div>
                <div class="quick-action-text">
                    <div class="quick-action-title">Buat Lowongan Baru</div>
                    <div class="quick-action-desc">Tambahkan posisi magang baru</div>
                </div>
                <i class="ti ti-chevron-right quick-action-arrow"></i>
            </a>

            <a href="{{ route('admin.applications.index') }}" class="quick-action">
                <div class="quick-action-icon icon-wrap-amber">
                    <i class="ti ti-file-search"></i>
                </div>
                <div class="quick-action-text">
                    <div class="quick-action-title">Review Lamaran Masuk</div>
                    <div class="quick-action-desc">Lihat dan proses lamaran peserta</div>
                </div>
                <i class="ti ti-chevron-right quick-action-arrow"></i>
            </a>

            <a href="{{ route('admin.supervisors.index') }}" class="quick-action">
                <div class="quick-action-icon icon-wrap-blue">
                    <i class="ti ti-user-plus"></i>
                </div>
                <div class="quick-action-text">
                    <div class="quick-action-title">Mapping Pembimbing</div>
                    <div class="quick-action-desc">Assign supervisor ke peserta magang</div>
                </div>
                <i class="ti ti-chevron-right quick-action-arrow"></i>
            </a>

            <a href="{{ route('admin.certificates') }}" class="quick-action">
                <div class="quick-action-icon icon-wrap-green">
                    <i class="ti ti-certificate"></i>
                </div>
                <div class="quick-action-text">
                    <div class="quick-action-title">Terbitkan Sertifikat</div>
                    <div class="quick-action-desc">Generate sertifikat untuk magang selesai</div>
                </div>
                <i class="ti ti-chevron-right quick-action-arrow"></i>
            </a>

            <a href="{{ route('admin.export.internships') }}" class="quick-action">
                <div class="quick-action-icon icon-wrap-red">
                    <i class="ti ti-download"></i>
                </div>
                <div class="quick-action-text">
                    <div class="quick-action-title">Export Data Magang</div>
                    <div class="quick-action-desc">Download rekap data dalam Excel</div>
                </div>
                <i class="ti ti-chevron-right quick-action-arrow"></i>
            </a>
        </div>
    </div>

    {{-- Right Column --}}
    <div class="flex flex-col gap-4">

        {{-- System Info --}}
        <div class="panel panel-accent-blue p-5">
            <h3 class="text-h4 mb-3.5">Informasi Sistem</h3>
            <div class="flex flex-col gap-0">
                <div class="info-row">
                    <span class="info-label">Versi</span>
                    <span class="info-value">1.0.0</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Framework</span>
                    <span class="info-value">Laravel 11 + Livewire</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Database</span>
                    <span class="info-value">MySQL 8+</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Waktu Server</span>
                    <span class="info-value">{{ now()->format('H:i') }} WIB</span>
                </div>
            </div>
        </div>

        {{-- Status Ringkasan --}}
        <div class="panel panel-accent-green p-5">
            <h3 class="text-h4 mb-3.5">Menu Admin</h3>
            <div class="flex flex-col gap-2">
                <a href="{{ route('admin.users') }}" class="quick-action px-3.5 py-2.5">
                    <i class="ti ti-users text-[#5C5A55] text-base"></i>
                    <span class="text-[13px]">Manajemen Pengguna</span>
                    <i class="ti ti-chevron-right quick-action-arrow ml-auto"></i>
                </a>
                <a href="{{ route('admin.logbooks') }}" class="quick-action px-3.5 py-2.5">
                    <i class="ti ti-notebook text-[#5C5A55] text-base"></i>
                    <span class="text-[13px]">Monitor Logbook</span>
                    <i class="ti ti-chevron-right quick-action-arrow ml-auto"></i>
                </a>
                <a href="{{ route('admin.evaluations') }}" class="quick-action px-3.5 py-2.5">
                    <i class="ti ti-star text-[#5C5A55] text-base"></i>
                    <span class="text-[13px]">Lihat Penilaian</span>
                    <i class="ti ti-chevron-right quick-action-arrow ml-auto"></i>
                </a>
                <a href="{{ route('admin.reports') }}" class="quick-action px-3.5 py-2.5">
                    <i class="ti ti-file-report text-[#5C5A55] text-base"></i>
                    <span class="text-[13px]">Laporan Akhir</span>
                    <i class="ti ti-chevron-right quick-action-arrow ml-auto"></i>
                </a>
                <a href="{{ route('admin.testimonials') }}" class="quick-action px-3.5 py-2.5">
                    <i class="ti ti-message-star text-[#5C5A55] text-base"></i>
                    <span class="text-[13px]">Atur Testimoni</span>
                    <i class="ti ti-chevron-right quick-action-arrow ml-auto"></i>
                </a>
            </div>
        </div>

    </div>
</div>

@endsection
