@extends('emails.layouts.email')
@section('content')
    <h2 style="color:#16a34a">Laporan Akhir Disetujui</h2>
    <p>Halo <strong>{{ $intern_name }}</strong>,</p>
    <p>Laporan akhir Anda berjudul <strong>"{{ $report_title }}"</strong> telah disetujui oleh pembimbing. Selamat!</p>

    <div class="info-box info-green">
        <table style="width:100%;border-collapse:collapse;font-size:13px">
            <tr><td style="padding:2px 0;color:#6b7280">Judul Laporan</td><td style="padding:2px 0;text-align:right;font-weight:600">{{ $report_title }}</td></tr>
            <tr><td style="padding:2px 0;color:#6b7280">Status</td><td style="padding:2px 0;text-align:right;font-weight:600;color:#16a34a">Disetujui</td></tr>
        </table>
    </div>

    <p>Anda dapat mengunduh laporan akhir melalui aplikasi. Pertahankan prestasi Anda!</p>

    <hr class="divider">
    <p style="font-size:13px;color:#6b7280">Langkah selanjutnya: lengkapi testimonial dan pantau sertifikat Anda.</p>
@endsection
