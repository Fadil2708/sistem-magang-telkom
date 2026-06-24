<x-guest-layout>
    @section('title', 'Email Terverifikasi')

    <div class="text-center auth-form-header" x-init="setTimeout(() => window.location.href = '{{ route('dashboard') }}', 2000)">
        <div class="icon-circle-green">
            <i class="ti ti-circle-check"></i>
        </div>
        <h2 class="auth-title">Email Berhasil Diverifikasi!</h2>
        <p class="auth-desc" style="line-height:1.6">
            Akun Anda sudah aktif. Anda akan dialihkan ke halaman dashboard dalam beberapa detik...
        </p>
        <div class="spinner-brand" style="margin:16px auto 0">
            <i class="ti ti-loader spin"></i>
        </div>
        <p style="margin-top:16px;font-size:13px;color:#A8A5A0">
            Jika tidak dialihkan, <a href="{{ route('dashboard') }}" class="link-brand">klik di sini</a>.
        </p>
    </div>
</x-guest-layout>
