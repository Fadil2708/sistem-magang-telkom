@extends('emails.layouts.email')

@section('content')
    <h2 style="color:#C0392B">Jadwal Wawancara</h2>
    <p>Halo <strong>{{ $intern_name }}</strong>,</p>
    <p>Selamat! Anda telah dijadwalkan untuk wawancara untuk posisi <strong>{{ $vacancy_title }}</strong>.</p>

    <div class="info-box info-blue">
        <table style="width:100%;border-collapse:collapse;font-size:13px">
            <tr><td style="padding:2px 0;color:#6b7280">Tanggal Wawancara</td><td style="padding:2px 0;text-align:right;font-weight:600">{{ $interview_date }}</td></tr>
            <tr><td style="padding:2px 0;color:#6b7280">Posisi</td><td style="padding:2px 0;text-align:right;font-weight:600">{{ $vacancy_title }}</td></tr>
        </table>
    </div>

    <p>Harap hadir tepat waktu dan persiapkan diri Anda dengan baik. Semoga sukses!</p>

    <hr class="divider">
    <p style="font-size:13px;color:#6b7280">Ada perubahan jadwal? Hubungi admin melalui aplikasi.</p>
@endsection
