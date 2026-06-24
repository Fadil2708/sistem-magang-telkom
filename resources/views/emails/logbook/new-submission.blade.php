@extends('emails.layouts.email')

@section('content')
    <h2 style="color:#2563eb">Logbook Baru dari Peserta</h2>
    <p>Halo <strong>{{ $supervisor_name }}</strong>,</p>
    <p>Peserta <strong>{{ $intern_name }}</strong> telah mengirimkan logbook baru untuk ditinjau.</p>

    <div class="info-box info-blue">
        <table style="width:100%;border-collapse:collapse;font-size:13px">
            <tr><td style="padding:2px 0;color:#6b7280">Peserta</td><td style="padding:2px 0;text-align:right;font-weight:600">{{ $intern_name }}</td></tr>
            <tr><td style="padding:2px 0;color:#6b7280">Tanggal Kegiatan</td><td style="padding:2px 0;text-align:right;font-weight:600">{{ $activity_date }}</td></tr>
            <tr><td style="padding:2px 0;color:#6b7280">Status</td><td style="padding:2px 0;text-align:right;font-weight:600;color:#d97706">Menunggu Review</td></tr>
        </table>
    </div>

    <hr class="divider">
    <p style="font-size:13px;color:#6b7280">Anda menerima email ini karena logbook peserta magang Anda membutuhkan review.</p>
@endsection
