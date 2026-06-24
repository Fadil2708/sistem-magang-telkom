@extends('layouts.public')
@section('title', '419 — Sesi Berakhir')

@section('content')
<main class="error-page">
    <div class="error-icon error-icon-amber">
        <i class="ti ti-clock-off"></i>
    </div>
    <h1 class="error-code">419</h1>
    <p class="error-message">Sesi Anda telah berakhir karena terlalu lama tidak ada aktivitas.</p>
    <p class="error-hint">Silakan login kembali untuk melanjutkan. Data yang belum disimpan mungkin hilang.</p>
    <div class="error-actions">
        <a href="{{ url('/login') }}" class="btn-primary btn-lg">
            <i class="ti ti-login"></i> Login Kembali
        </a>
    </div>
</main>
@endsection
