@extends('layouts.public')
@section('title', '503 — Dalam Perbaikan')

@section('content')
<main class="error-page">
    <div class="error-icon error-icon-amber">
        <i class="ti ti-tool"></i>
    </div>
    <h1 class="error-code">503</h1>
    <p class="error-message">Sistem sedang dalam perbaikan. Silakan coba beberapa saat lagi.</p>
    <a href="{{ url('/') }}" class="btn-primary btn-lg">
        <i class="ti ti-arrow-left"></i> Kembali ke Beranda
    </a>
</main>
@endsection
