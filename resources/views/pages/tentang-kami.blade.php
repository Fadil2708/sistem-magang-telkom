@extends('layouts.public')

@section('title', 'Tentang Kami')

@section('content')
<div class="public-page">
    <div class="page-header-center">
        <h1 class="page-title-lg">Tentang Kami</h1>
        <p class="welcome-section-sub">Sistem Informasi Pengelolaan Magang & PKL Telkom Sukabumi</p>
    </div>

    <div class="panel" style="padding:32px">
        <div class="vac-section">
            <h3>Latar Belakang</h3>
            <p class="vac-text">
                Sistem Informasi Pengelolaan Magang & PKL Telkom Sukabumi adalah platform digital yang dikembangkan
                oleh Tim IT Telkom Sukabumi untuk memudahkan proses pendaftaran, monitoring, dan evaluasi program
                magang dan Praktik Kerja Lapangan (PKL) di lingkungan Telkom Sukabumi.
            </p>
        </div>

        <div class="vac-section">
            <h3>Tujuan</h3>
            <p class="vac-text">Platform ini bertujuan untuk:</p>
            <ul style="list-style:disc;padding-left:20px;font-size:13px;color:#52504B;line-height:1.7">
                <li>Mempermudah pendaftaran magang secara digital</li>
                <li>Memantau status lamaran secara real-time</li>
                <li>Mencatat logbook kegiatan harian</li>
                <li>Memudahkan evaluasi oleh pembimbing</li>
                <li>Menerbitkan sertifikat digital dengan QR code terverifikasi</li>
            </ul>
        </div>

        <div class="vac-section">
            <h3>Kontak</h3>
            <p class="vac-text">
                Jl. Masjid No.1, Gunungparang, Kec. Cikole, Kota Sukabumi, Jawa Barat 43111<br>
                Email: magang@telkomsukabumi.co.id<br>
                Telepon: +62 858-8168-3025
            </p>
        </div>

        <div style="text-align:center;margin-top:24px">
            <a href="{{ url('/') }}" class="btn-primary"><i class="ti ti-arrow-left"></i> Kembali ke Beranda</a>
        </div>
    </div>
</div>
@endsection
