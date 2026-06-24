@extends('emails.layouts.email')

@section('content')
    <h2>Reset Password</h2>
    <p>Halo <strong>{{ $user ?? 'Pengguna' }}</strong>,</p>
    <p>Kami menerima permintaan reset password untuk akun <strong>Sistem Magang & PKL Telkom Sukabumi</strong> Anda.</p>
    <p>Klik tombol di bawah untuk mengatur ulang password:</p>

    <div style="text-align:center;margin:24px 0">
        <a href="{{ $url }}" class="btn" style="display:inline-block;padding:12px 28px;background:#dc2626;color:#fff;text-decoration:none;border-radius:6px;font-size:14px;font-weight:600">
            Reset Password
        </a>
    </div>

    <div class="info-box" style="background:#fef2f2;border-left:4px solid #dc2626;padding:12px 15px;margin:15px 0;border-radius:4px">
        <p style="margin:3px 0;font-size:13px;color:#374151">
            <strong>Penting:</strong> Tautan ini akan kedaluwarsa dalam <strong>{{ $expire }} menit</strong>.
        </p>
    </div>

    <p style="font-size:13px;color:#6b7280;margin-top:16px">
        Jika Anda tidak meminta reset password, abaikan email ini.
    </p>
@endsection
