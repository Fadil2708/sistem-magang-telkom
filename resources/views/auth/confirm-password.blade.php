<x-guest-layout>
    @section('title', 'Konfirmasi Password')

    <div class="text-center auth-form-header">
        <div class="icon-circle-brand">
            <i class="ti ti-shield-lock"></i>
        </div>
        <h2 class="auth-title">Konfirmasi Password</h2>
        <p class="auth-desc" style="line-height:1.5">
            Ini adalah area aman. Harap konfirmasi password Anda sebelum melanjutkan.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" x-data="{ showPassword: false, loading: false }" @submit="loading = true">
        @csrf

        <div class="field">
            <label for="password">Password</label>
            <div class="input-wrap">
                <input id="password" type="password" name="password" required autocomplete="current-password" class="input"
                       x-bind:type="showPassword ? 'text' : 'password'">
                <button type="button" @click="showPassword = !showPassword" class="password-toggle">
                    <i x-show="!showPassword" class="ti ti-eye"></i>
                    <i x-show="showPassword" class="ti ti-eye-off"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <button type="submit"
                class="btn-primary btn-full mt-20"
                x-bind:disabled="loading"
                x-bind:class="loading ? 'btn-loading' : ''">
            <i x-show="loading" class="ti ti-loader spin"></i>
            <i x-show="!loading" class="ti ti-shield-check"></i>
            <span x-show="!loading">Konfirmasi</span>
            <span x-show="loading">Memproses...</span>
        </button>
    </form>
</x-guest-layout>
