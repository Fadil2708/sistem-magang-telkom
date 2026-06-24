<x-guest-layout>
    @section('title', 'Reset Password')

    <div class="text-center auth-form-header">
        <div class="icon-circle-brand">
            <i class="ti ti-shield-check"></i>
        </div>
        <h2 class="auth-title">Reset Password</h2>
        <p class="auth-desc">Masukkan password baru Anda</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" x-data="{ showPassword: false, showConfirm: false, loading: false }" @submit="loading = true">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" class="input">
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="field field-group">
            <label for="password">Password Baru</label>
            <div class="input-wrap">
                <input id="password" type="password" name="password" required autocomplete="new-password" class="input" placeholder="Minimal 8 karakter"
                       x-bind:type="showPassword ? 'text' : 'password'">
                <button type="button" @click="showPassword = !showPassword" class="password-toggle">
                    <i x-show="!showPassword" class="ti ti-eye"></i>
                    <i x-show="showPassword" class="ti ti-eye-off"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="field field-group">
            <label for="password_confirmation">Konfirmasi Password Baru</label>
            <div class="input-wrap">
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="input" placeholder="Ulangi password"
                       x-bind:type="showConfirm ? 'text' : 'password'">
                <button type="button" @click="showConfirm = !showConfirm" class="password-toggle">
                    <i x-show="!showConfirm" class="ti ti-eye"></i>
                    <i x-show="showConfirm" class="ti ti-eye-off"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <button type="submit"
                class="btn-primary btn-full mt-20"
                x-bind:disabled="loading"
                x-bind:class="loading ? 'btn-loading' : ''">
            <i x-show="!loading" class="ti ti-shield-check"></i>
            <i x-show="loading" class="ti ti-loader spin"></i>
            <span x-show="!loading">Reset Password</span>
            <span x-show="loading">Memproses...</span>
        </button>
    </form>
</x-guest-layout>
