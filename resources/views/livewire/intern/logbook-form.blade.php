<div>
    <div style="max-width:600px;margin:0 auto">
        @if(!$hasActiveInternship)
            <div class="panel" style="text-align:center;padding:40px">
                <x-empty-state icon="ti-notebook" message="Anda tidak memiliki magang aktif. Tidak dapat membuat logbook." />
                <a href="{{ route('intern.vacancies') }}" class="link-brand" style="display:inline-block;margin-top:12px">Cari Lowongan</a>
            </div>
        @else
            <div class="panel form-card">
                <h3 style="font-size:15px;font-weight:700;color:#1E1C1A;margin-bottom:24px">
                    {{ $logbookId ? 'Edit Logbook' : 'Buat Logbook Baru' }}
                </h3>

                @if($validationStatus === 'revision_requested')
                    <div class="banner banner-warning" style="padding:12px;margin-bottom:20px">
                        <p style="font-size:13px;color:#92400E;font-weight:600;margin:0">Logbook ini perlu direvisi. Silakan perbaiki dan kirim ulang.</p>
                    </div>
                @endif

                <form wire:submit="save">
                    <div class="field">
                        <label>Tanggal Kegiatan <span class="required">*</span></label>
                        <input wire:model="activity_date" type="date" max="{{ date('Y-m-d') }}" class="input">
                        @error('activity_date') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="field">
                        <label>Uraian Kegiatan <span class="required">*</span></label>
                        <textarea wire:model="activities" rows="5" class="input" placeholder="Jelaskan kegiatan yang dilakukan hari ini..."></textarea>
                        <p class="text-caption" style="margin-top:4px">Minimal 20 karakter</p>
                        @error('activities') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="field">
                        <label>Hasil/Output <span class="required">*</span></label>
                        <textarea wire:model="output" rows="3" class="input" placeholder="Apa hasil atau output dari kegiatan hari ini..."></textarea>
                        <p class="text-caption" style="margin-top:4px">Minimal 10 karakter</p>
                        @error('output') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                    <div style="display:flex;align-items:center;gap:16px;margin-top:24px">
                        <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait" class="btn-save">
                            <i wire:loading.remove class="ti ti-device-floppy"></i>
                            <span wire:loading.remove>{{ $logbookId ? 'Simpan Perubahan' : 'Simpan sebagai Draft' }}</span>
                            <span wire:loading class="inline-flex items-center gap-1">
                                <i class="ti ti-loader animate-spin"></i>
                                Menyimpan...
                            </span>
                        </button>
                        <a href="{{ route('intern.logbooks') }}" wire:navigate class="link-cancel">Batal</a>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
