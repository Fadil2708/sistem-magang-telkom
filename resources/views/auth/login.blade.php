<x-guest-layout>
    @section('title', 'Masuk')

    <div class="auth-form-header">
        <h2 class="auth-title">Masuk ke Akun Anda</h2>
        <p class="auth-desc">Masuk untuk mengakses dashboard Anda</p>
    </div>

    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" x-data="{ showPassword: false, loading: false }" @submit="loading = true">
        @csrf

        <div class="field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" class="input">
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="field field-group">
            <div class="label-row">
                <label for="password">Password</label>
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Lupa password?</a>
                @endif
            </div>
            <div class="input-wrap">
                <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password" class="input"
                       x-bind:type="showPassword ? 'text' : 'password'">
                <button type="button" @click="showPassword = !showPassword" class="password-toggle">
                    <i x-show="!showPassword" class="ti ti-eye"></i>
                    <i x-show="showPassword" class="ti ti-eye-off"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <label class="checkbox-wrap">
            <input id="remember_me" type="checkbox" name="remember">
            <span>Ingat saya</span>
        </label>

        <button type="submit"
                class="btn-primary btn-full mt-20"
                x-bind:disabled="loading"
                x-bind:class="loading ? 'btn-loading' : ''">
            <i x-show="loading" class="ti ti-loader spin"></i>
            <span x-show="!loading">Masuk</span>
            <span x-show="loading">Memproses...</span>
        </button>

        <p class="auth-footer">
            Belum punya akun?
            <a href="{{ route('register') }}" class="link-brand">Daftar akun baru</a>
        </p>
    </form>
</x-guest-layout>
