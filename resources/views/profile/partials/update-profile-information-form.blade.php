<section>
    <div class="form-section-title">Informasi Profil</div>
    <p style="font-size:13px;color:#A8A5A0;margin-bottom:20px">Perbarui informasi akun dan email Anda.</p>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="field">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" class="input" value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email') <div class="field-error">{{ $message }}</div> @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div style="margin-top:8px">
                    <p style="font-size:13px;color:#1E1C1A">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification" style="color:#C0392B;text-decoration:underline;font-size:13px">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p style="margin-top:4px;font-size:12px;color:#16A34A">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
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
            @if (session('status') === 'profile-updated')
                <p x-data="timedHide" x-show="show" x-transition
                   style="font-size:13px;color:#16A34A;font-weight:600">
                    <i class="ti ti-circle-check"></i> {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
