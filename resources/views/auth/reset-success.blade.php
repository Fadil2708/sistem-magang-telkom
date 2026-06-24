<x-guest-layout>
    @section('title', 'Password Direset')

    <div class="text-center auth-form-header" x-init="setTimeout(() => window.location.href = '{{ route('login') }}', 2000)">
        <div class="icon-circle-green">
            <i class="ti ti-shield-check"></i>
        </div>
        <h2 class="auth-title">Password Berhasil Direset!</h2>
        <p class="auth-desc" style="line-height:1.6">
            Password Anda telah berhasil diperbarui. Silakan login dengan password baru Anda.
            Anda akan dialihkan ke halaman login dalam beberapa detik...
        </p>
        <div class="spinner-brand" style="margin:16px auto 0">
            <i class="ti ti-loader spin"></i>
        </div>
        <p style="margin-top:16px;font-size:13px;color:#A8A5A0">
            Jika tidak dialihkan, <a href="{{ route('login') }}" class="link-brand">klik di sini</a>.
        </p>
    </div>
</x-guest-layout>
