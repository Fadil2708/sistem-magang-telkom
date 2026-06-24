@extends('layouts.public')
@section('title', '500 — Terjadi Kesalahan')

@section('content')
<main class="error-page">
    <div class="error-icon error-icon-red">
        <i class="ti ti-alert-triangle"></i>
    </div>
    <h1 class="error-code">500</h1>
    <p class="error-message">Terjadi kesalahan pada server. Tim kami telah diberitahu.</p>
    <p class="error-hint">Silakan coba beberapa saat lagi. Jika masalah berlanjut, hubungi <a href="mailto:support@telkomsukabumi.co.id">support@telkomsukabumi.co.id</a>.</p>
    <div class="error-actions">
        <a href="{{ url('/') }}" class="btn-primary btn-lg">
            <i class="ti ti-arrow-left"></i> Kembali ke Beranda
        </a>
    </div>
</main>
@endsection
