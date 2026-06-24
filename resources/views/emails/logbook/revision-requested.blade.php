@extends('emails.layouts.email')

@section('content')
    <h2 style="color:#d97706">Logbook Perlu Revisi</h2>
    <p>Halo <strong>{{ $intern_name }}</strong>,</p>
    <p>Logbook Anda untuk kegiatan tanggal <strong>{{ $activity_date }}</strong> perlu direvisi sesuai catatan pembimbing.</p>

    <div class="info-box info-amber">
        <table style="width:100%;border-collapse:collapse;font-size:13px">
            <tr><td style="padding:2px 0;color:#6b7280">Tanggal</td><td style="padding:2px 0;text-align:right;font-weight:600">{{ $activity_date }}</td></tr>
        </table>
    </div>

    @if ($supervisor_notes)
    <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:6px;padding:12px 14px;margin:12px 0">
        <p style="margin:0 0 4px;font-size:12px;color:#92400e;font-weight:600">Catatan Pembimbing:</p>
        <p style="margin:0;font-size:13px;color:#78350f;line-height:1.5">{{ $supervisor_notes }}</p>
    </div>
    @endif

    <p>Silakan lakukan revisi sesuai catatan di atas dan kirimkan kembali melalui aplikasi.</p>

    <hr class="divider">
    <p style="font-size:13px;color:#6b7280">Butuh bantuan? Hubungi pembimbing melalui aplikasi.</p>
@endsection
