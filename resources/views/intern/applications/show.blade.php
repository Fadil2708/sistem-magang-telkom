@extends('layouts.app')
@section('title', 'Detail Lamaran')
@php $pageTitle = 'Detail Lamaran'; @endphp

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('intern.applications') }}">Lamaran</a>
            <i class="ti ti-chevron-right"></i>
            <span>Detail</span>
        </div>
        <h2 class="page-title">Detail Lamaran</h2>
    </div>
</div>

<div style="max-width:640px">
    <div class="panel" style="overflow:hidden">
        <div class="detail-header">
            <div class="detail-header-row">
                <div>
                    <h3 class="detail-header-title">
                        {{ $application->vacancy->title ?? '—' }}
                    </h3>
                    <p class="detail-header-sub">
                        {{ $application->vacancy->division ?? '—' }}
                    </p>
                </div>
                <x-badge :status="$application->status" />
            </div>
        </div>

        <div class="detail-body">
            <table class="detail-table">
                <tr>
                    <td>Tanggal Daftar</td>
                    <td>{{ $application->applied_at?->format('d M Y, H:i') ?? '—' }}</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td><x-badge :status="$application->status" /></td>
                </tr>
                @if($application->interview_date)
                <tr>
                    <td>Jadwal Interview</td>
                    <td style="color:#6B21A8">
                        <i class="ti ti-calendar"></i>
                        {{ $application->interview_date->format('d M Y, H:i') }}
                    </td>
                </tr>
                @endif
                @if($application->rejection_reason)
                <tr>
                    <td>Alasan Ditolak</td>
                    <td style="color:#991B1B">{{ $application->rejection_reason }}</td>
                </tr>
                @endif
                @if($application->admin_notes)
                <tr>
                    <td>Catatan Admin</td>
                    <td style="color:#52504B">{{ $application->admin_notes }}</td>
                </tr>
                @endif
            </table>

            @if($application->status === 'accepted' && $application->internship)
            <div class="detail-banner-success">
                <i class="ti ti-circle-check"></i>
                Selamat! Kamu diterima.
                <a href="{{ route('intern.internship') }}">Lihat detail magang</a>
            </div>
            @endif
        </div>
    </div>

    <div style="margin-top:16px">
        <a href="{{ route('intern.applications') }}" class="btn-primary" style="padding:8px 20px;font-size:12px">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection
