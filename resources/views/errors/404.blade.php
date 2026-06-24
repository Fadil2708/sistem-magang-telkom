@extends('layouts.public')
@section('title', '404 — Halaman Tidak Ditemukan')

@section('content')
<main class="error-page">
    <div class="error-icon error-icon-amber">
        <i class="ti ti-search-off"></i>
    </div>
    <h1 class="error-code">404</h1>
    <p class="error-message">Halaman yang Anda cari tidak ditemukan atau telah dipindahkan.</p>
    <p class="error-hint">Periksa kembali URL atau kunjungi halaman <a href="{{ url('/') }}">Beranda</a>.</p>
    <div class="error-actions">
        <a href="{{ url('/') }}" class="btn-primary btn-lg">
            <i class="ti ti-arrow-left"></i> Kembali ke Beranda
        </a>
    </div>
</main>
@endsection
