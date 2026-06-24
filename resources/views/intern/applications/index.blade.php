@extends('layouts.app')
@section('title', 'Lamaran Saya')
@php $pageTitle = 'Lamaran Saya'; @endphp

@section('content')

<div class="page-header">
    <div>
        <h2 class="page-title">Lamaran Saya</h2>
        <p class="page-sub">{{ $applications->total() }} total lamaran</p>
    </div>
    <a href="{{ route('intern.applications.create') }}" class="btn-primary">
        <i class="ti ti-plus"></i> Daftar Lowongan Baru
    </a>
</div>

<div class="filter-bar">
    <div class="filter-tabs hide-mobile">
        @foreach(['all' => 'Semua', 'pending' => 'Pending', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'] as $val => $label)
            <a href="{{ request()->fullUrlWithQuery(['status' => $val]) }}"
               class="filter-tab {{ request('status', 'all') === $val ? 'active' : '' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>
    <div class="filter-select-wrap show-mobile">
        <i class="ti ti-filter"></i>
        <select onchange="window.location = this.value" class="filter-select">
            @foreach(['all' => 'Semua', 'pending' => 'Pending', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'] as $val => $label)
                <option value="{{ request()->fullUrlWithQuery(['status' => $val]) }}"
                        {{ request('status', 'all') === $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>
    <form class="search-box" method="GET">
        <i class="ti ti-search"></i>
        <input type="text" name="search" placeholder="Cari lowongan..." value="{{ request('search') }}">
    </form>
</div>

<x-data-table :headers="['Lowongan', 'Perusahaan', 'Tanggal Daftar', 'Status', 'Aksi']" :paginator="$applications" empty-icon="ti-file-description" empty-message="Belum ada lamaran">
    @forelse($applications as $app)
    <tr>
        <td>
            <div class="text-h4" style="margin-bottom:1px">{{ $app->vacancy->title ?? '—' }}</div>
            <div class="text-caption">{{ $app->vacancy->division ?? '—' }}</div>
        </td>
        <td class="text-body-sm">Telkom Sukabumi</td>
        <td class="text-body-sm">{{ $app->created_at->format('d M Y') }}</td>
        <td><x-badge :status="$app->status" /></td>
        <td>
            <a href="{{ route('intern.applications.show', $app) }}" class="action-btn" title="Detail Lamaran">
                <i class="ti ti-eye"></i>
            </a>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="5">
            <x-empty-state icon="ti-file-description" message="Belum ada lamaran" />
        </td>
    </tr>
    @endforelse
</x-data-table>

@endsection
