@extends('layouts.public')
@section('title', '403 — Akses Ditolak')

@section('content')
<main class="error-page">
    <div class="error-icon error-icon-red">
        <i class="ti ti-lock"></i>
    </div>
    <h1 class="error-code">403</h1>
    <p class="error-message">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
    <p class="error-hint">Silakan <a href="{{ url('/login') }}">login</a> dengan akun yang memiliki akses atau hubungi admin.</p>
    <div class="error-actions">
        <a href="{{ url('/') }}" class="btn-primary btn-lg">
            <i class="ti ti-arrow-left"></i> Kembali ke Beranda
        </a>
    </div>
</main>
@endsection
