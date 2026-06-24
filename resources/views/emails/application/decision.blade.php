@extends('emails.layouts.email')

@section('content')
    <h2 style="color:{{ $status === 'accepted' ? '#16a34a' : '#dc2626' }}">
        {{ $status === 'accepted' ? 'Lamaran Diterima' : 'Lamaran Ditolak' }}
    </h2>
    <p>Halo <strong>{{ $intern_name }}</strong>,</p>
    <p>Keputusan untuk lamaran Anda pada posisi <strong>{{ $vacancy_title }}</strong> telah ditentukan.</p>

    <div class="info-box {{ $status === 'accepted' ? 'info-green' : '' }}">
        <table style="width:100%;border-collapse:collapse;font-size:13px">
            <tr><td style="padding:2px 0;color:#6b7280">Keputusan</td><td style="padding:2px 0;text-align:right;font-weight:600;color:{{ $status === 'accepted' ? '#16a34a' : '#dc2626' }}">{{ $status === 'accepted' ? 'Diterima' : 'Ditolak' }}</td></tr>
            <tr><td style="padding:2px 0;color:#6b7280">Posisi</td><td style="padding:2px 0;text-align:right;font-weight:600">{{ $vacancy_title }}</td></tr>
            @if ($rejection_reason)
            <tr><td style="padding:2px 0;color:#6b7280">Alasan</td><td style="padding:2px 0;text-align:right;font-weight:600;color:#dc2626">{{ $rejection_reason }}</td></tr>
            @endif
        </table>
    </div>

    @if ($status === 'accepted')
        <p>Selamat! Anda diterima. Tim kami akan menghubungi Anda untuk langkah selanjutnya.</p>
    @else
        <p>Terima kasih atas partisipasi Anda. Jangan menyerah dan coba kesempatan lainnya.</p>
    @endif

    <hr class="divider">
    <p style="font-size:13px;color:#6b7280">Butuh bantuan? Hubungi admin melalui aplikasi.</p>
@endsection
