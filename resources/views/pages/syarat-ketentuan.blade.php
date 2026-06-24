@extends('layouts.public')

@section('title', 'Syarat & Ketentuan')

@section('content')
<div class="public-page">
    <div class="page-header-center">
        <h1 class="page-title-lg">Syarat & Ketentuan</h1>
        <p class="welcome-section-sub">Ketentuan umum program magang dan PKL Telkom Sukabumi</p>
    </div>

    <div class="panel" style="padding:32px">
        <div class="vac-section">
            <h3>Persyaratan Pendaftar</h3>
            <ul style="list-style:disc;padding-left:20px;font-size:13px;color:#52504B;line-height:1.7">
                <li>Mahasiswa aktif minimal semester 4 dari jurusan relevan</li>
                <li>Siswa SMK kelas 11-12 untuk program PKL</li>
                <li>Memiliki surat rekomendasi dari institusi pendidikan</li>
                <li>Bersedia mematuhi peraturan yang berlaku di Telkom Sukabumi</li>
            </ul>
        </div>

        <div class="vac-section">
            <h3>Hak & Kewajiban Peserta</h3>
            <p class="vac-text">
                Peserta magang wajib mengisi logbook harian, mengikuti bimbingan, dan menyelesaikan
                laporan akhir. Peserta berhak mendapatkan bimbingan, evaluasi, dan sertifikat
                jika menyelesaikan program dengan baik.
            </p>
        </div>

        <div class="vac-section">
            <h3>Ketentuan Lain</h3>
            <p class="vac-text">
                Telkom Sukabumi berhak mengubah jadwal, membatalkan, atau menyesuaikan program
                magang sesuai kebutuhan operasional. Keputusan akhir mengenai kelulusan peserta
                berada di tangan tim evaluasi Telkom Sukabumi.
            </p>
        </div>

        <div style="text-align:center;margin-top:24px">
            <a href="{{ url('/') }}" class="btn-primary"><i class="ti ti-arrow-left"></i> Kembali ke Beranda</a>
        </div>
    </div>
</div>
@endsection
