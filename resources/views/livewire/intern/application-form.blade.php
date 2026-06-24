<div>
    <div style="max-width:600px;margin:0 auto">
        <div class="panel form-card" style="margin-bottom:20px">
            <h2 class="text-hero" style="margin:0 0 4px">{{ $vacancy->title }}</h2>
            <p class="text-body-sm" style="margin:0">{{ $vacancy->division }}</p>
            <p class="text-body" style="margin-top:12px">{{ $vacancy->description }}</p>
            @if($vacancy->qualifications)
                <div style="margin-top:16px">
                    <h4 class="text-h4">Kualifikasi:</h4>
                    <p class="text-body" style="white-space:pre-line">{{ $vacancy->qualifications }}</p>
                </div>
            @endif
        </div>

        @if($errorMessage)
            <div class="banner banner-error">
                <p class="banner-desc" style="margin:0">{{ $errorMessage }}</p>
            </div>
        @endif

        @if($applicationStatus === 'rejected')
            <div class="banner banner-error">
                <div class="banner-flex">
                    <i class="ti ti-x-circle banner-icon-sm" style="color:#DC2626"></i>
                    <div>
                        <h3 class="banner-title" style="color:#991B1B">Lamaran Ditolak</h3>
                        <p class="banner-desc" style="color:#991B1B">Lamaran Anda untuk lowongan ini telah ditolak. Silakan cari lowongan lain yang tersedia.</p>
                    </div>
                </div>
                <a href="{{ route('intern.vacancies') }}" class="banner-action" style="color:#DC2626">
                    <i class="ti ti-arrow-left"></i> Cari Lowongan Lain
                </a>
            </div>
        @elseif($applicationStatus === 'cancelled')
            <div class="banner banner-muted">
                <div class="banner-flex">
                    <i class="ti ti-circle-minus banner-icon-sm" style="color:#5C5A55"></i>
                    <div>
                        <h3 class="banner-title" style="color:#5C5A55">Lamaran Dibatalkan</h3>
                        <p class="banner-desc" style="color:#5C5A55">Lamaran Anda untuk lowongan ini telah dibatalkan.</p>
                    </div>
                </div>
                <a href="{{ route('intern.vacancies') }}" class="banner-action" style="color:#5C5A55">
                    <i class="ti ti-arrow-left"></i> Cari Lowongan Lain
                </a>
            </div>
        @elseif($hasApplied)
            <div class="banner banner-info">
                <i class="ti ti-circle-check banner-icon-sm"></i>
                <h3 class="banner-title">Lamaran Terkirim</h3>
                <p class="banner-desc">Anda sudah melamar lowongan ini. Silakan cek status di halaman lamaran saya.</p>
                <a href="{{ route('intern.applications') }}" class="banner-action">Lihat Lamaran</a>
            </div>
        @elseif(!$profileComplete)
            <div class="banner banner-warning">
                <i class="ti ti-alert-triangle banner-icon-sm"></i>
                <h3 class="banner-title">Profil Belum Lengkap</h3>
                <p class="banner-desc">Lengkapi profil Anda (Nama, Institusi, Jurusan, NIS/NPM, CV) sebelum melamar.</p>
                <a href="{{ route('intern.profile') }}" class="btn-primary" style="display:inline-block;margin-top:12px;font-size:13px;padding:8px 16px">
                    Lengkapi Profil
                </a>
            </div>
        @else
            <div class="panel form-card">
                <h3 class="text-h3" style="margin-bottom:8px">Siap Melamar?</h3>
                <p class="text-body-sm" style="margin-bottom:16px">Pastikan data profil Anda sudah benar sebelum melamar.</p>
                <div style="display:flex;align-items:center;gap:16px">
                    <button wire:click="apply" wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait" class="btn-save">
                        <i wire:loading.remove class="ti ti-send"></i>
                        <span wire:loading.remove>Lamar Sekarang</span>
                        <span wire:loading class="inline-flex items-center gap-1">
                            <i class="ti ti-loader animate-spin"></i>
                            Memproses...
                        </span>
                    </button>
                    <a href="{{ route('intern.vacancies') }}" class="link-cancel">Batal</a>
                </div>
            </div>
        @endif
    </div>
</div>
