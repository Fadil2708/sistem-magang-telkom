@extends('emails.layouts.email')

@section('content')
    <h2 style="color:#dc2626">Laporan Akhir Ditolak</h2>
    <p>Halo <strong>{{ $intern_name }}</strong>,</p>
    <p>Laporan akhir Anda dengan judul <strong>{{ $report_title }}</strong> perlu diperbaiki.</p>

    <div class="info-box">
        <table style="width:100%;border-collapse:collapse;font-size:13px">
            <tr><td style="padding:2px 0;color:#6b7280">Judul Laporan</td><td style="padding:2px 0;text-align:right;font-weight:600">{{ $report_title }}</td></tr>
            <tr><td style="padding:2px 0;color:#6b7280">Status</td><td style="padding:2px 0;text-align:right;font-weight:600;color:#dc2626">Ditolak</td></tr>
        </table>
    </div>

    <p>Silakan perbaiki laporan Anda sesuai masukan pembimbing dan kirimkan kembali melalui aplikasi.</p>

    <hr class="divider">
    <p style="font-size:13px;color:#6b7280">Jangan menyerah! Setiap revisi membuat laporan Anda lebih baik.</p>
@endsection
