<x-guest-layout>
    @section('title', 'Verifikasi Email')

    <div class="text-center auth-form-header">
        <div class="icon-circle-brand-lg">
            <i class="ti ti-mail"></i>
        </div>
        <h2 class="auth-title">Verifikasi Email</h2>
        <p class="auth-desc" style="line-height:1.6">
            Kami telah mengirimkan tautan verifikasi ke email <strong style="color:#312F2D">{{ auth()->user()->email ?? 'Anda' }}</strong>.
            Klik tautan tersebut untuk mengaktifkan akun.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
    <div class="alert-success">
        <i class="ti ti-circle-check"></i>
        Tautan verifikasi baru telah dikirim. Cek inbox/spam email Anda.
    </div>
    @endif

    <div class="alert-warning">
        <i class="ti ti-info-circle"></i>
        Tidak menerima email? Periksa folder spam atau klik tombol di bawah untuk kirim ulang.
    </div>

    <div style="display:flex;gap:12px;margin-top:24px">
        <form method="POST" action="{{ route('verification.send') }}" style="flex:1">
            @csrf
            <button type="submit" class="btn-primary btn-full" style="padding:10px;font-size:13px">
                <i class="ti ti-send"></i> Kirim Ulang
            </button>
        </form>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-secondary" style="padding:10px 16px;font-size:13px">
                <i class="ti ti-logout"></i> Keluar
            </button>
        </form>
    </div>
</x-guest-layout>
