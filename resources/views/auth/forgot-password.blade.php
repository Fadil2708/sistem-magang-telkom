<x-guest-layout>
    @section('title', 'Lupa Password')

    <div class="text-center auth-form-header">
        <div class="icon-circle-brand">
            <i class="ti ti-lock-question"></i>
        </div>
        <h2 class="auth-title">Lupa Password?</h2>
        <p class="auth-desc" style="line-height:1.5">
            Masukkan email terdaftar dan kami akan kirimkan tautan reset password.
        </p>
    </div>

    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" x-data="{ loading: false }" @submit="loading = true">
        @csrf

        <div class="field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="input" placeholder="nama@email.com">
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <button type="submit"
                class="btn-primary btn-full mt-20"
                x-bind:disabled="loading"
                x-bind:class="loading ? 'btn-loading' : ''">
            <i x-show="!loading" class="ti ti-send"></i>
            <i x-show="loading" class="ti ti-loader spin"></i>
            <span x-show="!loading">Kirim Tautan Reset</span>
            <span x-show="loading">Mengirim...</span>
        </button>

        <p class="auth-footer">
            <a href="{{ route('login') }}" class="link-brand">
                <i class="ti ti-arrow-left"></i> Kembali ke login
            </a>
        </p>
    </form>
</x-guest-layout>
