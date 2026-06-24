@extends('emails.layouts.email')

@section('content')
    <h2 style="color:#16a34a">Sertifikat Diterbitkan</h2>
    <p>Halo <strong>{{ $intern_name }}</strong>,</p>
    <p>Selamat! Sertifikat Anda telah diterbitkan. Ini adalah bukti resmi bahwa Anda telah menyelesaikan program magang/PKL di <strong>Telkom Sukabumi</strong>.</p>

    <div class="info-box info-green">
        <table style="width:100%;border-collapse:collapse;font-size:13px">
            <tr><td style="padding:2px 0;color:#6b7280">Nomor Sertifikat</td><td style="padding:2px 0;text-align:right;font-weight:600;font-family:monospace">{{ $certificate_number }}</td></tr>
        </table>
    </div>

    <p>Anda dapat mengunduh sertifikat melalui aplikasi. Simpan sertifikat ini dengan baik sebagai bukti kompetensi Anda.</p>

    <hr class="divider">
    <p style="font-size:13px;color:#6b7280">Terima kasih telah mengikuti program magang/PKL di Telkom Sukabumi. Sukses selalu!</p>
@endsection
