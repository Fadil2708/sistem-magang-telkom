@extends('layouts.public')

@section('title', 'Kebijakan Privasi')

@section('content')
<div class="public-page">
    <div class="page-header-center">
        <h1 class="page-title-lg">Kebijakan Privasi</h1>
        <p class="welcome-section-sub">Bagaimana kami melindungi data pribadi Anda</p>
    </div>

    <div class="panel" style="padding:32px">
        <div class="vac-section">
            <h3>Pengumpulan Data</h3>
            <p class="vac-text">
                Kami mengumpulkan data pribadi yang Anda berikan saat mendaftar, termasuk nama,
                email, nomor telepon, institusi pendidikan, dan dokumen pendukung seperti CV
                dan surat lamaran.
            </p>
        </div>

        <div class="vac-section">
            <h3>Penggunaan Data</h3>
            <p class="vac-text">
                Data Anda digunakan untuk keperluan administrasi program magang, proses seleksi,
                evaluasi, dan penerbitan sertifikat. Data tidak akan digunakan untuk tujuan lain
                tanpa persetujuan Anda.
            </p>
        </div>

        <div class="vac-section">
            <h3>Perlindungan Data</h3>
            <p class="vac-text">
                Seluruh data disimpan dalam sistem yang aman dengan akses terbatas. File pribadi
                seperti KTP, CV, dan sertifikat disimpan di penyimpanan privat yang tidak bisa
                diakses publik.
            </p>
        </div>

        <div class="vac-section">
            <h3>Cookie</h3>
            <p class="vac-text">
                Platform ini menggunakan cookie untuk meningkatkan pengalaman pengguna.
                Dengan melanjutkan menggunakan platform ini, Anda menyetujui penggunaan
                cookie sesuai kebijakan ini.
            </p>
        </div>

        <div style="text-align:center;margin-top:24px">
            <a href="{{ url('/') }}" class="btn-primary"><i class="ti ti-arrow-left"></i> Kembali ke Beranda</a>
        </div>
    </div>
</div>
@endsection
