@extends('layouts.public')
@section('title', $vacancy->title . ' — Lowongan Magang')

@section('content')
<main class="public-page-narrow">
    <a href="{{ route('public.vacancies') }}" class="back-link">
        <i class="ti ti-arrow-left"></i> Kembali ke daftar
    </a>

    <div class="panel" style="overflow:hidden">
        <div class="vacancy-header">
            <div class="vacancy-header-text">
                <h1>{{ $vacancy->title }}</h1>
                @if($vacancy->division)
                    <p><i class="ti ti-building"></i> {{ $vacancy->division }}</p>
                @endif
            </div>
            <span class="badge badge-{{ $vacancy->status === 'open' ? 'success' : 'neutral' }}">
                {{ $vacancy->status === 'open' ? 'OPEN' : strtoupper($vacancy->status) }}
            </span>
        </div>

        <div class="vacancy-body">
            <div class="stat-grid">
                <div class="stat-card"><div class="stat-num">{{ $vacancy->quota }}</div><div class="stat-lbl">Kuota</div></div>
                <div class="stat-card"><div class="stat-date">{{ $vacancy->start_date->format('d M Y') }}</div><div class="stat-lbl">Mulai</div></div>
                <div class="stat-card"><div class="stat-date">{{ $vacancy->end_date->format('d M Y') }}</div><div class="stat-lbl">Selesai</div></div>
                <div class="stat-card"><div class="stat-date stat-warn">{{ $vacancy->application_deadline->format('d M Y') }}</div><div class="stat-lbl">Deadline</div></div>
            </div>

            <section class="vac-section">
                <h3>Deskripsi</h3>
                <div class="vac-text">{!! nl2br(e($vacancy->description)) !!}</div>
            </section>

            <section class="vac-section">
                <h3>Kualifikasi</h3>
                <div class="vac-text">{!! nl2br(e($vacancy->qualifications)) !!}</div>
            </section>

            @if($vacancy->creator)
            <div class="vac-meta">
                Dibuat oleh {{ $vacancy->creator->name ?? '—' }} · {{ $vacancy->created_at->format('d M Y') }}
            </div>
            @endif
        </div>
    </div>

    <div class="vac-cta">
        @auth
            @if(auth()->user()->role === 'intern' && $vacancy->status === 'open')
                <a href="{{ route('intern.applications.create', $vacancy) }}" class="btn-primary btn-lg">
                    <i class="ti ti-send"></i> Daftar Sekarang
                </a>
            @else
                <a href="{{ url('/dashboard') }}" class="btn-primary btn-lg">
                    <i class="ti ti-layout-dashboard"></i> Dashboard
                </a>
            @endif
        @else
            <a href="{{ route('login') }}" class="btn-primary btn-lg">
                <i class="ti ti-login"></i> Masuk untuk Daftar
            </a>
        @endauth
    </div>
</main>
@endsection
