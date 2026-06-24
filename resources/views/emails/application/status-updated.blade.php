@extends('emails.layouts.email')

@section('content')
    <h2>Status Lamaran Diperbarui</h2>
    <p>Halo <strong>{{ $intern_name }}</strong>,</p>
    <p>Status lamaran Anda untuk posisi <strong>{{ $vacancy_title }}</strong> telah diperbarui.</p>

    <div class="info-box info-amber">
        <table style="width:100%;border-collapse:collapse;font-size:13px">
            <tr><td style="padding:2px 0;color:#6b7280">Status</td><td style="padding:2px 0;text-align:right;font-weight:600;color:#d97706">{{ ucfirst(str_replace('_', ' ', $status)) }}</td></tr>
            <tr><td style="padding:2px 0;color:#6b7280">Posisi</td><td style="padding:2px 0;text-align:right;font-weight:600">{{ $vacancy_title }}</td></tr>
        </table>
    </div>

    <p>Silakan cek aplikasi untuk detail lebih lanjut.</p>

    <hr class="divider">
    <p style="font-size:13px;color:#6b7280">Butuh bantuan? Hubungi admin melalui aplikasi.</p>
@endsection
