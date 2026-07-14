<div x-data="{ selected: $wire.$entangle('selectedLogbooks') }">
    <div class="filter-bar">
        <div class="filter-tabs">
            <button wire:click="$set('filterStatus', 'submitted')" class="filter-tab" :class="{ 'active': filterStatus === 'submitted' }">Perlu Review</button>
            <button wire:click="$set('filterStatus', 'approved')" class="filter-tab" :class="{ 'active': filterStatus === 'approved' }">Disetujui</button>
            <button wire:click="$set('filterStatus', 'revision_requested')" class="filter-tab" :class="{ 'active': filterStatus === 'revision_requested' }">Perlu Revisi</button>
            <button wire:click="$set('filterStatus', '')" class="filter-tab" :class="{ 'active': !filterStatus }">Semua</button>
        </div>
    </div>

    @if($filterStatus === 'submitted')
    <div class="bulk-bar">
        <label>
            <input type="checkbox" wire:click="toggleSelectAll"
                   {{ count($selectedLogbooks) > 0 && count($selectedLogbooks) === $totalSubmitted ? 'checked' : '' }}>
            Pilih Semua
        </label>
        @if(count($selectedLogbooks) > 0)
        <button @click="window.dispatchEvent(new CustomEvent('confirm', { detail: { message: 'Setujui {{ count($selectedLogbooks) }} logbook terpilih?', callback: () => $wire.bulkApprove() } }))"
                wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait"
                class="btn-sm-success">
            <i class="ti ti-check"></i> Setujui Terpilih ({{ count($selectedLogbooks) }})
        </button>
        @endif
    </div>
    @endif

    <div wire:loading style="display:flex;flex-direction:column;gap:16px">
        @for($i = 0; $i < 4; $i++)
        <div class="panel lb-card">
            <div style="display:flex;align-items:flex-start;gap:12px">
                <div class="skeleton" style="width:16px;height:16px;border-radius:2px;margin-top:4px;flex-shrink:0"></div>
                <div style="flex:1">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between">
                        <div style="flex:1">
                            <div class="flex items-center gap-2.5" style="margin-bottom:8px">
                                <div class="skeleton-avatar"></div>
                                <div>
                                    <div class="skeleton-text skeleton-text-lg" style="width:180px"></div>
                                    <div class="skeleton-text skeleton-text-sm" style="width:140px"></div>
                                </div>
                            </div>
                            <div class="skeleton-text skeleton-text-sm" style="width:160px;margin-bottom:8px"></div>
                            <div class="skeleton-text" style="width:100%;margin-bottom:4px"></div>
                            <div class="skeleton-text" style="width:70%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endfor
    </div>
    <div wire:loading.remove style="display:flex;flex-direction:column;gap:16px">
        @forelse($logbooks as $logbook)
        <div wire:key="{{ $logbook->id }}" class="panel lb-card">
            <div style="display:flex;align-items:flex-start;gap:12px">
                @if($logbook->validation_status === 'submitted')
                <input type="checkbox" x-model="selected" value="{{ $logbook->id }}"
                       style="width:16px;height:16px;accent-color:#2563EB;margin-top:4px;flex-shrink:0">
                @endif
                <div style="flex:1">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between">
                        <div style="flex:1">
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px">
                                <x-avatar :name="$logbook->intern?->internProfile?->full_name ?? $logbook->intern?->email ?? ''" :size="28" />
                                <div>
                                    <span class="font-medium">{{ $logbook->intern?->internProfile?->full_name ?? $logbook->intern?->email }}</span>
                                    <span class="text-caption" style="margin-left:8px">{{ $logbook->internship?->vacancy?->title }}</span>
                                </div>
                            </div>
                            <p class="text-caption" style="margin-bottom:12px">{{ $logbook->activity_date?->isoFormat('dddd, D MMMM Y') ?? '—' }}</p>

                            <div style="display:flex;flex-direction:column;gap:8px">
                                <div class="review-activity">
                                    <strong>Kegiatan:</strong>
                                    <span> {{ $logbook->activities }}</span>
                                </div>
                                <div class="review-activity">
                                    <strong>Output:</strong>
                                    <span> {{ $logbook->output }}</span>
                                </div>
                            </div>

                            @if($logbook->supervisor_notes)
                                <div class="lb-card-notes" style="margin-top:12px">
                                    <strong>Catatan Review:</strong>
                                    <p>{{ $logbook->supervisor_notes }}</p>
                                </div>
                            @endif
                        </div>

                        <div style="display:flex;align-items:flex-start;gap:8px;margin-left:16px;flex-shrink:0">
                            <x-badge status="{{ $logbook->validation_status }}" />
                        </div>
                    </div>

                    @if($logbook->validation_status === 'submitted')
                        <div style="margin-top:16px;padding-top:16px;border-top:1px solid #E8E6E1;display:flex;align-items:center;gap:10px">
                            <button wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait"
                                    @click="window.dispatchEvent(new CustomEvent('confirm', { detail: { message: 'Setujui logbook ini?', callback: () => $wire.approve('{{ $logbook->id }}') } }))"
                                    class="btn-sm-success">
                                <i class="ti ti-check"></i> Setujui
                            </button>
                            <button wire:click="openRevision('{{ $logbook->id }}')"
                                    wire:loading.attr="disabled"
                                    class="btn-sm-danger">
                                <i class="ti ti-arrow-back"></i> Minta Revisi
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="panel" style="text-align:center;padding:40px">
            <x-empty-state icon="ti-notebook" message="Tidak ada logbook {{ $filterStatus ? 'dengan status ini' : '' }}." />
        </div>
        @endforelse
    </div>

    <div class="pagination-wrap">
        {{ $logbooks->links('components.pagination', ['paginator' => $logbooks]) }}
    </div>

    @if($showRevisionModal)
    <div class="modal-wrap" aria-labelledby="revision-modal-title" role="dialog" aria-modal="true"
         x-data
         @keydown.escape.window="$wire.set('showRevisionModal', false)">
        <div class="modal-backdrop" @click="$wire.set('showRevisionModal', false)"></div>
        <div class="modal-center">
            <div class="modal-card modal-card-lg">
                <div class="modal-header">
                    <h3 id="revision-modal-title" class="modal-title">Minta Revisi Logbook</h3>
                    <button wire:click="$set('showRevisionModal', false)" class="action-btn" aria-label="Tutup modal">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="field">
                        <label>Catatan Revisi <span class="required">*</span></label>
                        <textarea wire:model="revisionNotes" rows="4" class="input" placeholder="Jelaskan apa yang perlu diperbaiki..."></textarea>
                        @error('revisionNotes') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="$set('showRevisionModal', false)" class="btn-secondary">Batal</button>
                    <button wire:click="requestRevision" wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait"
                            class="btn-save">
                        <span wire:loading.remove>Kirim Revisi</span>
                        <span wire:loading class="inline-flex items-center gap-1">
                            <i class="ti ti-loader animate-spin"></i>
                            Mengirim...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
