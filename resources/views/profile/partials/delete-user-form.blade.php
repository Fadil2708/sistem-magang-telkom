<section class="space-y-6">
    <div class="form-section-title" style="color:#DC2626">Hapus Akun</div>
    <p style="font-size:13px;color:#A8A5A0;margin-bottom:16px">
        Setelah akun dihapus, semua data akan terhapus permanen. Unduh data yang ingin Anda simpan sebelum melanjutkan.
    </p>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="btn-primary" style="background:#DC2626;display:inline-flex;align-items:center;gap:6px"
    ><i class="ti ti-trash"></i> {{ __('Delete Account') }}</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" style="padding:24px">
            @csrf
            @method('delete')

            <h3 style="font-size:16px;font-weight:700;color:#1E1C1A;margin-bottom:8px">{{ __('Are you sure you want to delete your account?') }}</h3>

            <p style="font-size:13px;color:#5C5A55;margin-bottom:24px">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="field">
                <label for="password">{{ __('Password') }}</label>
                <input id="password" name="password" type="password" class="input" placeholder="{{ __('Password') }}" style="max-width:300px">
                @error('password', 'userDeletion') <div class="field-error">{{ $message }}</div> @enderror
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:24px"
                  x-data="{ loading: false }"
                  @submit="loading = true">
                <button type="button" x-on:click="$dispatch('close')" class="btn-secondary">Batal</button>
                <button type="submit"
                        class="btn-primary"
                        style="background:#DC2626;display:inline-flex;align-items:center;gap:6px"
                        x-bind:disabled="loading"
                        x-bind:class="loading ? 'btn-loading' : ''">
                    <i x-show="!loading" class="ti ti-trash"></i>
                    <i x-show="loading" class="ti ti-loader spin"></i>
                    <span x-show="!loading">Hapus Akun</span>
                    <span x-show="loading">Memproses...</span>
                </button>
            </div>
        </form>
    </x-modal>
</section>
