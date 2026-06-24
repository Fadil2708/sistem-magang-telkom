@extends('layouts.public')
@section('title', 'Lowongan Magang / PKL')

@section('content')
<main class="public-page">
    <div class="vac-hero">
        <div class="vac-hero-content">
            <h1 class="vac-hero-title">Temukan Magang Impianmu</h1>
            <p class="vac-hero-sub">Jelajahi lowongan magang & PKL dari berbagai divisi di Telkom Sukabumi</p>
        </div>
        <form class="vac-search-form" method="GET" action="{{ route('public.vacancies') }}">
            <div class="vac-search-wrap">
                <i class="ti ti-search vac-search-icon"></i>
                <input type="text" name="search" class="vac-search-input"
                       placeholder="Cari lowongan berdasarkan judul atau divisi..."
                       value="{{ request('search') }}">
                <select name="division" class="vac-search-select">
                    <option value="">Semua Divisi</option>
                    @foreach($divisions as $div)
                        <option value="{{ $div }}" {{ request('division') === $div ? 'selected' : '' }}>{{ $div }}</option>
                    @endforeach
                </select>
                <button type="submit" class="vac-search-btn">Cari</button>
                @if(request('search') || request('division'))
                    <a href="{{ route('public.vacancies') }}" class="vac-search-reset">
                        <i class="ti ti-x"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="vac-stats">
        Menampilkan <strong>{{ $vacancies->total() }}</strong> lowongan magang tersedia
        @if(request('search'))
            untuk pencarian "<strong>{{ request('search') }}</strong>"
        @endif
        @if(request('division'))
            di divisi <strong>{{ request('division') }}</strong>
        @endif
    </div>

    @if($vacancies->count() > 0)
        <div class="vac-grid">
            @foreach($vacancies as $vacancy)
            <div class="panel vac-card">
                <div class="vac-card-head">
                    <div>
                        @if($vacancy->division)
                            <span class="vac-div-badge">{{ $vacancy->division }}</span>
                        @endif
                        <h2 class="vac-card-title">{{ $vacancy->title }}</h2>
                    </div>
                </div>
                <p class="vac-card-desc">{{ Str::limit(strip_tags($vacancy->description), 120) }}</p>
                <div class="vac-card-meta">
                    <span class="vac-meta-item">
                        <i class="ti ti-users"></i>
                        Kuota: {{ $vacancy->quota }} orang
                    </span>
                    <span class="vac-meta-item vac-meta-deadline">
                        <i class="ti ti-calendar"></i>
                        Deadline: {{ $vacancy->application_deadline->format('d M Y') }}
                    </span>
                </div>
                <div class="vac-card-footer">
                    @php
                        $daysLeft = now()->diffInDays($vacancy->application_deadline, false);
                    @endphp
                    @if($daysLeft >= 0 && $daysLeft <= 7)
                        <span class="vac-card-urgent">
                            <i class="ti ti-alert-triangle"></i>
                            {{ $daysLeft == 0 ? 'Hari terakhir!' : $daysLeft . ' hari lagi' }}
                        </span>
                    @elseif($daysLeft > 7)
                        <span class="vac-card-normal">
                            <i class="ti ti-clock"></i>
                            {{ number_format($daysLeft) }} hari lagi
                        </span>
                    @endif
                    <a href="{{ route('public.vacancies.show', $vacancy) }}" class="btn-primary btn-sm">
                        Lihat Detail
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        @if(method_exists($vacancies, 'links'))
        <div class="pagination-wrap pagination-wrap-center">
            {{ $vacancies->withQueryString()->links('components.pagination', ['paginator' => $vacancies]) }}
        </div>
        @endif
    @else
    <div class="vac-empty">
        <i class="ti ti-briefcase"></i>
        <p>Tidak ada lowongan yang sesuai dengan kriteria pencarian.</p>
        <a href="{{ route('public.vacancies') }}" class="btn-primary" style="margin-top:16px">
            <i class="ti ti-refresh"></i> Tampilkan Semua
        </a>
    </div>
    @endif
</main>
@endsection