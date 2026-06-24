@extends('emails.layouts.email')

@section('content')
    <h2 style="color:#16a34a">Logbook Disetujui</h2>
    <p>Halo <strong>{{ $intern_name }}</strong>,</p>
    <p>Logbook Anda untuk kegiatan tanggal <strong>{{ $activity_date }}</strong> telah disetujui oleh pembimbing.</p>

    <div class="info-box info-green">
        <table style="width:100%;border-collapse:collapse;font-size:13px">
            <tr><td style="padding:2px 0;color:#6b7280">Tanggal</td><td style="padding:2px 0;text-align:right;font-weight:600">{{ $activity_date }}</td></tr>
            <tr><td style="padding:2px 0;color:#6b7280">Status</td><td style="padding:2px 0;text-align:right;font-weight:600;color:#16a34a">Disetujui</td></tr>
        </table>
    </div>

    <p>Pertahankan konsistensi Anda dalam mengisi logbook. Terima kasih!</p>

    <hr class="divider">
    <p style="font-size:13px;color:#6b7280">Terus semangat! Setiap langkah berarti dalam perjalanan magang Anda.</p>
@endsection
