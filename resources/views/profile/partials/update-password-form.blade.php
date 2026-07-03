<section>
    <div class="form-section-title">Ubah Password</div>
    <p style="font-size:13px;color:#A8A5A0;margin-bottom:20px">Pastikan akun Anda menggunakan password yang kuat dan acak.</p>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div class="field">
            <label for="update_password_current_password">Password Saat Ini</label>
            <input id="update_password_current_password" name="current_password" type="password" class="input" autocomplete="current-password">
            @error('current_password', 'updatePassword') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="update_password_password">Password Baru</label>
            <input id="update_password_password" name="password" type="password" class="input" autocomplete="new-password">
            @error('password', 'updatePassword') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label for="update_password_password_confirmation">Konfirmasi Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="input" autocomplete="new-password">
            @error('password_confirmation', 'updatePassword') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div style="display:flex;align-items:center;gap:12px">
            <button type="submit"
                    class="btn-save"
                    x-data="{ loading: false }"
                    x-on:click="loading = true"
                    x-bind:disabled="loading"
                    x-bind:class="loading ? 'btn-loading' : ''">
                <i x-show="!loading" class="ti ti-device-floppy"></i>
                <i x-show="loading" class="ti ti-loader spin"></i>
                <span x-show="!loading">Simpan</span>
                <span x-show="loading">Menyimpan...</span>
            </button>
            @if (session('status') === 'password-updated')
                <p x-data="timedHide" x-show="show" x-transition
                   style="font-size:13px;color:#16A34A;font-weight:600">
                    <i class="ti ti-circle-check"></i> {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
