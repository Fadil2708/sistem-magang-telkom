<div>
    <div class="filter-bar">
        <div class="filter-tabs hide-mobile">
            <button wire:click="$set('filterStatus', '')" class="filter-tab" :class="{ 'active': !$wire.filterStatus }">Semua</button>
            <button wire:click="$set('filterStatus', 'submitted')" class="filter-tab" :class="{ 'active': $wire.filterStatus === 'submitted' }">Submitted</button>
            <button wire:click="$set('filterStatus', 'under_review')" class="filter-tab" :class="{ 'active': $wire.filterStatus === 'under_review' }">Direview</button>
            <button wire:click="$set('filterStatus', 'interview_scheduled')" class="filter-tab" :class="{ 'active': $wire.filterStatus === 'interview_scheduled' }">Interview</button>
            <button wire:click="$set('filterStatus', 'accepted')" class="filter-tab" :class="{ 'active': $wire.filterStatus === 'accepted' }">Diterima</button>
            <button wire:click="$set('filterStatus', 'rejected')" class="filter-tab" :class="{ 'active': $wire.filterStatus === 'rejected' }">Ditolak</button>
            <button wire:click="$set('filterStatus', 'cancelled')" class="filter-tab" :class="{ 'active': $wire.filterStatus === 'cancelled' }">Dibatalkan</button>
        </div>
        <div class="filter-select-wrap show-mobile">
            <i class="ti ti-filter"></i>
            <select wire:change="$set('filterStatus', $event.target.value)" class="filter-select">
                <option value="">Semua</option>
                <option value="submitted">Submitted</option>
                <option value="under_review">Direview</option>
                <option value="interview_scheduled">Interview</option>
                <option value="accepted">Diterima</option>
                <option value="rejected">Ditolak</option>
                <option value="cancelled">Dibatalkan</option>
            </select>
        </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:16px">
        @forelse($applications as $app)
        <div class="panel app-card">
            <div class="app-card-row">
                <div class="app-card-info">
                    <div class="app-card-badges">
                        <x-badge status="{{ $app->status }}" />
                    </div>
                    <a href="{{ route('intern.applications.show', $app) }}" class="app-card-title">
                        <h3 style="font-size:15px;font-weight:700;color:#1E1C1A;margin:0">{{ $app->vacancy->title }}</h3>
                        <p class="app-card-meta">{{ $app->vacancy->division }} &mdash; Dikirim {{ $app->applied_at?->diffForHumans() ?? '—' }}</p>
                    </a>
                </div>
            </div>

            @if($app->status === 'submitted')
            <div class="app-card-footer">
                <button wire:click="confirmCancel('{{ $app->id }}')"
                        wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait"
                        class="btn-cancel">
                    <i class="ti ti-x"></i> Batalkan Lamaran
                </button>
            </div>
            @endif

            @if($app->status === 'interview_scheduled' && $app->interview_date)
            <div class="app-card-interview">
                <p style="font-size:13px;font-weight:600;color:#6B21A8;margin:0">Interview dijadwalkan: {{ $app->interview_date->format('d M Y H:i') }}</p>
            </div>
            @endif

            @if($app->status === 'rejected' && $app->rejection_reason)
            <div class="app-card-rejection">
                <p style="margin:0">Alasan: {{ $app->rejection_reason }}</p>
            </div>
            @endif

            @if($app->admin_notes)
            <div class="text-body-sm" style="margin-top:12px">
                <span class="font-medium">Catatan Admin:</span> {{ $app->admin_notes }}
            </div>
            @endif
        </div>
        @empty
        <div class="panel" style="text-align:center;padding:40px">
            <x-empty-state icon="ti-file-description" message="Belum ada lamaran." />
            <a href="{{ route('intern.vacancies') }}" class="link-brand" style="display:inline-block;margin-top:12px">Lihat Lowongan Tersedia</a>
        </div>
        @endforelse
    </div>

    <div class="pagination-wrap">
        {{ $applications->links('components.pagination', ['paginator' => $applications]) }}
    </div>

    @if($confirmingCancelId)
    <div class="modal-wrap" aria-labelledby="modal-title" role="dialog" aria-modal="true"
         x-data
         @keydown.escape.window="$wire.set('confirmingCancelId', null)">
        <div class="modal-backdrop" @click="$wire.set('confirmingCancelId', null)"></div>
        <div class="modal-center">
            <div class="modal-card modal-card-md">
                <div class="modal-header">
                    <h3 class="modal-title">Konfirmasi Pembatalan</h3>
                </div>
                <div class="modal-body">
                    <p class="text-body-sm" style="color:#5C5A55">Yakin ingin membatalkan lamaran ini? Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer">
                    <button wire:click="$set('confirmingCancelId', null)" class="btn-secondary">Batal</button>
                    <button wire:click="cancel"
                            wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait"
                            class="btn-save">
                        <span wire:loading.remove>Ya, Batalkan</span>
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
