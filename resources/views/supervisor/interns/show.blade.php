@extends('layouts.app')
@section('title', 'Detail Peserta')
@php $pageTitle = 'Detail Peserta'; @endphp

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('supervisor.interns.index') }}">Peserta</a>
            <i class="ti ti-chevron-right"></i>
            <span>Detail</span>
        </div>
        <h2 class="page-title">Detail Peserta</h2>
    </div>
</div>

<div class="form-layout">
    <div class="form-main">
        <div class="panel panel-detail">
            @php $name = $internship->intern->internProfile->full_name ?? $internship->intern->email ?? '—'; @endphp
            <div class="av-row">
                <x-avatar :name="$name" :size="40" />
                <div>
                    <div class="detail-name">{{ $name }}</div>
                    <div class="detail-email">{{ $internship->intern->email }}</div>
                </div>
            </div>
            <table class="info-table">
                <tr>
                    <td class="info-label">Institusi</td>
                    <td class="info-value">{{ $internship->intern->internProfile->institution_name ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Posisi</td>
                    <td class="info-value">{{ $internship->vacancy->title ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="info-label">Jenis Kelamin</td>
                    <td class="info-value">
                        @php $g = $internship->intern->internProfile->gender ?? null; @endphp
                        {{ $g === 'male' ? 'Laki-laki' : ($g === 'female' ? 'Perempuan' : '—') }}
                    </td>
                </tr>
                <tr>
                    <td class="info-label">Keahlian</td>
                    <td class="info-value">
                        @php $skills = $internship->intern->internProfile?->skills ?? collect(); @endphp
                        @forelse($skills as $skill)
                            <span class="skill-badge">{{ $skill->name }}</span>
                        @empty
                            —
                        @endforelse
                    </td>
                </tr>
                <tr>
                    <td class="info-label">Periode</td>
                    <td class="info-value">
                        {{ $internship->actual_start_date?->format('d M Y') }} —
                        {{ $internship->actual_end_date?->format('d M Y') }}
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="form-sidebar">
        <div class="panel detail-panel">
            <div class="form-section-title">Aksi</div>
            <a href="{{ route('supervisor.logbooks', ['intern_id' => $internship->intern_id]) }}"
               class="btn-primary btn-detail">
                <i class="ti ti-notebook"></i> Lihat Logbook
            </a>
            <a href="{{ route('supervisor.evaluations.show', $internship->id) }}"
               class="btn-primary btn-detail">
                <i class="ti ti-star"></i> Beri Nilai
            </a>
        </div>
    </div>
</div>
@endsection
