@extends('layouts.app')
@section('title', 'Logbook Saya')
@php $pageTitle = 'Logbook'; @endphp

@section('content')

<div class="logbook-header">
    <div class="logbook-today">
        <div class="today-date">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
        @if($todayLogbook ?? false)
            <x-badge :status="$todayLogbook->validation_status ?? 'draft'" />
        @else
            <a href="{{ route('intern.logbooks.create') }}" class="btn-primary" style="font-size:12px;padding:7px 14px">
                <i class="ti ti-plus"></i> Isi Logbook Hari Ini
            </a>
        @endif
    </div>
    <div class="logbook-stats-mini">
        <div><span class="lsm-num">{{ $stats['total'] ?? 0 }}</span><span class="lsm-lbl">Total</span></div>
        <div><span class="lsm-num" style="color:#16A34A">{{ $stats['approved'] ?? 0 }}</span><span class="lsm-lbl" style="color:#16A34A">Approved</span></div>
        <div><span class="lsm-num" style="color:#D97706">{{ $stats['pending'] ?? 0 }}</span><span class="lsm-lbl" style="color:#D97706">Pending</span></div>
    </div>
</div>

<div class="filter-bar" x-data="{ status: '{{ request('status', 'all') }}' }">
    <div class="filter-tabs">
        @foreach(['all' => 'Semua', 'draft' => 'Draft', 'pending' => 'Pending', 'approved' => 'Approved', 'revision_requested' => 'Revisi'] as $val => $label)
            <a href="{{ request()->fullUrlWithQuery(['status' => $val]) }}"
               class="filter-tab {{ request('status', 'all') === $val ? 'active' : '' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>
    <form class="search-box" method="GET">
        <i class="ti ti-search"></i>
        <input type="text" name="search" placeholder="Cari kegiatan..." value="{{ request('search') }}">
    </form>
</div>

<div class="panel">
    @forelse($logbooks as $logbook)
    <div class="lb-entry">
        <div class="lb-date-row">
            <div>
                <span class="lb-date">{{ \Carbon\Carbon::parse($logbook->activity_date)->translatedFormat('l, d F Y') }}</span>
                @if(($logbook->validation_status ?? '') === 'revision_requested')
                    <div style="font-size:10px;color:#D97706;margin-top:2px">
                        ↩ {{ $logbook->supervisor_notes ?? '' }}
                    </div>
                @endif
            </div>
            <div style="display:flex;gap:6px;align-items:center">
                <x-badge :status="$logbook->validation_status ?? 'draft'" />
                @if(in_array($logbook->validation_status ?? 'draft', ['draft', 'revision_requested']))
                    <a href="{{ route('intern.logbooks.edit', $logbook) }}" class="action-btn" title="Edit">
                        <i class="ti ti-pencil"></i>
                    </a>
                @endif
            </div>
        </div>
        <div class="lb-content">{{ Str::limit($logbook->activities, 150) }}</div>
        @if($logbook->output)
            <div style="margin-top:4px;font-size:10px;color:#5C5A55">
                <span style="font-weight:700">Output:</span> {{ Str::limit($logbook->output, 100) }}
            </div>
        @endif
    </div>
    @empty
    <x-empty-state icon="ti-notebook" message="Belum ada logbook. Mulai isi hari ini!" />
    @endforelse
</div>

<div class="pagination-wrap">
    {{ $logbooks->withQueryString()->links('components.pagination', ['paginator' => $logbooks]) }}
</div>

@endsection
