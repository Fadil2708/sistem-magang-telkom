<div>
    <div class="page-header">
        <div>
            <div class="breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <i class="ti ti-chevron-right"></i>
                <span>Testimonial</span>
            </div>
            <h2 class="page-title">Testimonial</h2>
            <p class="page-sub">Kelola testimonial peserta yang ditampilkan di halaman utama</p>
        </div>
    </div>
    <div class="filter-bar">
        <div class="filter-tabs" role="tablist" aria-label="Filter status">
            <button wire:click="$set('filterStatus', '')" class="filter-tab {{ $filterStatus === '' ? 'active' : '' }}">Semua</button>
            <button wire:click="$set('filterStatus', 'pending')" class="filter-tab {{ $filterStatus === 'pending' ? 'active' : '' }}">Menunggu</button>
            <button wire:click="$set('filterStatus', 'published')" class="filter-tab {{ $filterStatus === 'published' ? 'active' : '' }}">Ditayangkan</button>
        </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:16px">
        @forelse($testimonials as $testimonial)
        <div class="panel panel-hover" style="padding: 20px">
            <div style="display:flex;align-items:flex-start;justify-content:space-between">
                <div style="display:flex;align-items:center;gap:10px">
                    @php
                        $name = $testimonial->intern->internProfile->full_name ?? $testimonial->intern->email;
                    @endphp
                    <x-avatar name="{{ $name }}" size="40" type="r" />
                    <div>
                        <p class="font-medium" style="font-size:13px">{{ $name }}</p>
                        <p style="font-size:12px;color:#A8A5A0">{{ $testimonial->internship?->vacancy?->title ?? '-' }}</p>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:12px;flex-shrink:0">
                    <div class="star-rating" style="pointer-events:none">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="ti ti-star{{ $i <= $testimonial->rating ? '-filled' : '' }}" style="color:{{ $i <= $testimonial->rating ? '#F59E0B' : '#D0CEC9' }};font-size:14px"></i>
                        @endfor
                    </div>
                    <x-badge status="{{ $testimonial->is_published ? 'approved' : 'pending' }}" />
                </div>
            </div>
            <p style="margin-top:14px;color:#1E1C1A;font-size:13px;line-height:1.7">{{ $testimonial->content }}</p>
            <div style="margin-top:14px;display:flex;align-items:center;justify-content:space-between">
                <p style="font-size:11px;color:#A8A5A0">{{ $testimonial->created_at->format('d M Y H:i') }}</p>
                <button wire:click="confirmToggle('{{ $testimonial->id }}')"
                        wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait"
                        class="btn-sm-{{ $testimonial->is_published ? 'danger' : 'success' }}"
                        style="padding: 6px 14px; font-size: 11px;">
                    <i class="ti ti-{{ $testimonial->is_published ? 'eye-off' : 'eye' }}"></i>
                    {{ $testimonial->is_published ? 'Sembunyikan' : 'Setujui' }}
                </button>
            </div>
        </div>
        @empty
        <div class="panel" style="text-align:center;padding:40px">
            <x-empty-state icon="ti-message-star" message="Belum ada testimoni." />
        </div>
        @endforelse
    </div>

    <div class="pagination-wrap">
        {{ $testimonials->links('components.pagination', ['paginator' => $testimonials]) }}
    </div>

    @if($confirmingToggleId)
    <div class="modal-wrap" aria-labelledby="modal-title" role="dialog" aria-modal="true"
         x-data
         @keydown.escape.window="$wire.set('confirmingToggleId', null)">
        <div class="modal-backdrop" @click="$wire.set('confirmingToggleId', null)"></div>
        <div class="modal-center">
            <div class="modal-card modal-card-md">
                <div class="modal-header">
                    <h3 class="modal-title">Konfirmasi</h3>
                </div>
                <div class="modal-body">
                    <p style="font-size:13px;color:#5C5A55">Yakin ingin mengubah status penayangan testimoni ini?</p>
                </div>
                <div class="modal-footer">
                    <button wire:click="$set('confirmingToggleId', null)" class="btn-secondary">Batal</button>
                    <button wire:click="togglePublish"
                            wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait"
                            class="btn-save">
                        <span wire:loading.remove>Ya, Lanjutkan</span>
                        <span wire:loading class="inline-flex items-center gap-1">
                            <i class="ti ti-loader animate-spin"></i>
                            Memproses...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
