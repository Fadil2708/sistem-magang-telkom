<div>
    <div style="max-width:500px;margin:0 auto">
        @if(!$hasCompletedInternship)
            <div class="panel" style="text-align:center;padding:40px">
                <x-empty-state icon="ti-message-star" message="Testimoni" />
                <p class="text-body-sm" style="margin-top:8px">Testimoni hanya bisa diisi setelah magang selesai.</p>
            </div>
        @elseif($alreadySubmitted)
            <div class="banner banner-success" style="text-align:center">
                <i class="ti ti-circle-check" style="font-size:40px;color:#16A34A;margin-bottom:12px;display:block"></i>
                <h3 class="banner-title">Testimoni Terkirim</h3>
                <p class="banner-desc">Terima kasih! Testimoni Anda telah dikirim dan menunggu persetujuan admin untuk ditayangkan.</p>
            </div>
        @else
            <div class="panel form-card">
                <h3 class="text-h3" style="margin-bottom:4px">Berikan Testimoni</h3>
                <p class="text-body-sm" style="margin-bottom:24px">Bagikan pengalaman Anda mengikuti program magang/PKL di Telkom Sukabumi</p>

                <form wire:submit="save">
                    <div class="field">
                        <label>Rating <span class="required">*</span></label>
                        <div class="star-rating" style="margin-top:8px">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" wire:click="$set('rating', {{ $i }})" class="star-btn" style="font-size:28px">
                                    <i class="ti ti-star{{ $i <= $rating ? '-filled' : '' }}" style="color:{{ $i <= $rating ? '#F59E0B' : '#D0CEC9' }}"></i>
                                </button>
                            @endfor
                        </div>
                        @error('rating') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="field">
                        <label>Testimoni <span class="required">*</span></label>
                        <textarea wire:model="content" rows="5" class="input" placeholder="Ceritakan pengalaman Anda..."></textarea>
                        <p class="text-caption" style="margin-top:4px">Minimal 20, maksimal 1000 karakter. {{ Str::length($content) }}/1000</p>
                        @error('content') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                    <div style="display:flex;align-items:center;gap:16px;margin-top:24px">
                        <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait" class="btn-save">
                            <i wire:loading.remove class="ti ti-send"></i>
                            <span wire:loading.remove>Kirim Testimoni</span>
                            <span wire:loading class="inline-flex items-center gap-1">
                                <i class="ti ti-loader animate-spin"></i>
                                Mengirim...
                            </span>
                        </button>
                        <a href="{{ route('intern.dashboard') }}" wire:navigate class="link-cancel">Kembali</a>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
