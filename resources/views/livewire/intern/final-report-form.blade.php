<div>
    <div style="max-width:600px;margin:0 auto">
        @if(!$hasActiveInternship)
            <div class="panel" style="text-align:center;padding:40px">
                <x-empty-state icon="ti-file-description" message="Anda belum memiliki magang aktif. Laporan akhir hanya bisa diupload saat magang berlangsung." />
                <a href="{{ route('intern.vacancies') }}" class="link-brand" style="display:inline-block;margin-top:12px">Cari Lowongan</a>
            </div>
        @else
            @if($report && $report->supervisor_approval === 'approved')
                <div class="banner banner-success">
                    <div class="banner-flex">
                        <i class="ti ti-circle-check banner-icon" style="color:#16A34A"></i>
                        <div>
                            <h3 class="banner-title">Laporan Akhir Disetujui</h3>
                            <p class="banner-desc">Judul: {{ $report->title }}</p>
                        </div>
                    </div>
                </div>
            @elseif($report && $report->supervisor_approval === 'pending')
                <div class="banner banner-warning">
                    <div class="banner-flex">
                        <i class="ti ti-clock banner-icon"></i>
                        <div>
                            <h3 class="banner-title">Laporan Sedang Direview</h3>
                            <p class="banner-desc">Judul: {{ $report->title }} — Menunggu persetujuan pembimbing.</p>
                        </div>
                    </div>
                </div>
            @elseif($report && $report->supervisor_approval === 'rejected')
                <div class="banner banner-error">
                    <div class="banner-flex">
                        <i class="ti ti-alert-circle banner-icon" style="color:#DC2626"></i>
                        <div>
                            <h3 class="banner-title">Laporan Ditolak</h3>
                            <p class="banner-desc">Silakan upload ulang dengan perbaikan.</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="panel form-card">
                <h3 class="text-h3" style="margin-bottom:24px">
                    {{ $canUpload ? 'Upload Laporan Akhir' : 'Laporan Akhir' }}
                </h3>

                <form wire:submit="save">
                    <div class="field">
                        <label>Judul Laporan <span class="required">*</span></label>
                        <input wire:model="title" type="text" class="input" placeholder="Contoh: Laporan Praktik Kerja Lapangan di Telkom Sukabumi">
                        @error('title') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="field">
                        <label>File PDF <span class="required">*</span></label>
                        <input wire:model="file" type="file" accept=".pdf" class="field-input-file">
                        <p class="text-caption" style="margin-top:4px">Format PDF, maksimal 20MB</p>
                        @error('file') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    @if($canUpload)
                        <div style="display:flex;align-items:center;gap:16px;margin-top:24px">
                            <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait" class="btn-save">
                                <i wire:loading.remove class="ti ti-upload"></i>
                                <span wire:loading.remove>{{ $report ? 'Upload Ulang' : 'Upload Laporan' }}</span>
                                <span wire:loading class="inline-flex items-center gap-1">
                                    <i class="ti ti-loader animate-spin"></i>
                                    Mengupload...
                                </span>
                            </button>
                            <a href="{{ route('intern.dashboard') }}" wire:navigate class="link-cancel">Kembali</a>
                        </div>
                    @endif
                </form>
            </div>
        @endif
    </div>
</div>
