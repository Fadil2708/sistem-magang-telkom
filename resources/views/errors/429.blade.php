@extends('layouts.public')
@section('title', '429 — Terlalu Banyak Permintaan')

@section('content')
<main class="error-page">
    <div class="error-icon error-icon-red">
        <i class="ti ti-alert-circle"></i>
    </div>
    <h1 class="error-code">429</h1>
    <p class="error-message">Terlalu banyak permintaan dalam waktu singkat. Silakan coba beberapa saat lagi.</p>
    <a href="{{ url('/') }}" class="btn-primary btn-lg">
        <i class="ti ti-arrow-left"></i> Kembali ke Beranda
    </a>
</main>
@endsection
