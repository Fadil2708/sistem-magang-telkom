@extends('emails.layouts.email')

@section('content')
    <h2 style="color:#C0392B">Lamaran Terkirim</h2>
    <p>Halo <strong>{{ $intern_name }}</strong>,</p>
    <p>Lamaran Anda untuk posisi <strong>{{ $vacancy_title }}</strong> telah berhasil dikirim dan sedang menunggu proses review oleh admin.</p>

    <div class="info-box info-blue">
        <table style="width:100%;border-collapse:collapse;font-size:13px">
            <tr><td style="padding:2px 0;color:#6b7280">Status</td><td style="padding:2px 0;text-align:right;font-weight:600;color:#C0392B">Dikirim</td></tr>
            <tr><td style="padding:2px 0;color:#6b7280">Posisi</td><td style="padding:2px 0;text-align:right;font-weight:600">{{ $vacancy_title }}</td></tr>
        </table>
    </div>

    <p>Kami akan memberitahu Anda jika ada perkembangan lebih lanjut. Pantau status lamaran Anda melalui aplikasi.</p>

    <hr class="divider">
    <p style="font-size:13px;color:#6b7280">Butuh bantuan? Hubungi admin melalui aplikasi.</p>
@endsection
