<div>
    <div class="filter-bar">
        <div class="filter-tabs">
            <button wire:click="$set('filterStatus', 'pending')" class="filter-tab" :class="{ 'active': filterStatus === 'pending' }">Perlu Review</button>
            <button wire:click="$set('filterStatus', 'approved')" class="filter-tab" :class="{ 'active': filterStatus === 'approved' }">Disetujui</button>
            <button wire:click="$set('filterStatus', 'rejected')" class="filter-tab" :class="{ 'active': filterStatus === 'rejected' }">Ditolak</button>
            <button wire:click="$set('filterStatus', '')" class="filter-tab" :class="{ 'active': !filterStatus }">Semua</button>
        </div>
    </div>

    <div wire:loading class="report-list">
        @for($i = 0; $i < 3; $i++)
        <div class="panel report-card">
            <div class="report-flex">
                <div class="report-body" style="flex:1">
                    <div class="report-intern">
                        <div class="skeleton-avatar"></div>
                        <div style="flex:1">
                            <div class="skeleton-text skeleton-text-lg" style="width:180px"></div>
                            <div class="skeleton-text skeleton-text-sm" style="width:140px;margin-top:4px"></div>
                        </div>
                    </div>
                    <div class="skeleton-text" style="width:300px;margin-top:12px"></div>
                    <div style="display:flex;gap:16px;margin-top:8px">
                        <div class="skeleton-text skeleton-text-sm" style="width:120px"></div>
                        <div class="skeleton-text skeleton-text-sm" style="width:80px"></div>
                    </div>
                </div>
                <div><div class="skeleton" style="width:70px;height:22px;border-radius:20px"></div></div>
            </div>
        </div>
        @endfor
    </div>
    <div wire:loading.remove class="report-list">
        @forelse($reports as $report)
        <div wire:key="{{ $report->id }}" class="panel report-card status-{{ $report->supervisor_approval }}">
            <div class="report-flex">
                <div class="report-body">
                    <div class="report-intern">
                        <x-avatar :name="$report->intern?->internProfile?->full_name ?? $report->intern?->email ?? ''" :size="28" />
                        <div>
                            <span class="font-medium">{{ $report->intern?->internProfile?->full_name ?? $report->intern?->email }}</span>
                            <span class="text-caption">{{ $report->internship?->vacancy?->title }}</span>
                        </div>
                    </div>
                    <h4 class="report-title">{{ $report->title }}</h4>
                    <div class="report-meta">
                        <span class="report-meta-item">
                            <i class="ti ti-calendar"></i>
                            {{ $report->submitted_at?->isoFormat('D MMMM Y HH:mm') ?? '-' }}
                        </span>
                        @if($report->file_size_kb)
                        <span class="report-meta-item">
                            <i class="ti ti-file"></i>
                            {{ number_format($report->file_size_kb / 1024, 1) }} MB
                        </span>
                        @endif
                        <a href="{{ route('private.serve', ['path' => $report->file_url]) }}" target="_blank" class="report-link">
                            <i class="ti ti-eye"></i> Lihat File
                        </a>
                    </div>
                    @if($report->approved_at)
                        <p class="report-approved-note">
                            <i class="ti ti-circle-check"></i> Disetujui: {{ $report->approved_at?->isoFormat('D MMMM Y HH:mm') ?? '—' }}
                        </p>
                    @endif
                </div>

                <div class="report-badge-wrap">
                    <x-badge status="{{ $report->supervisor_approval }}" />
                </div>
            </div>

            @if($report->supervisor_approval === 'pending')
                <div class="report-actions">
                    <button wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait"
                            @click="window.dispatchEvent(new CustomEvent('confirm', { detail: { message: 'Setujui laporan ini?', callback: () => $wire.approve('{{ $report->id }}') } }))"
                            class="btn-sm-success">
                        <i class="ti ti-check"></i> Setujui
                    </button>
                    <button wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait"
                            @click="window.dispatchEvent(new CustomEvent('confirm', { detail: { message: 'Tolak laporan ini?', callback: () => $wire.reject('{{ $report->id }}') } }))"
                            class="btn-sm-danger">
                        <i class="ti ti-x"></i> Tolak
                    </button>
                </div>
            @endif
        </div>
        @empty
        <div class="panel panel-empty">
            <x-empty-state icon="ti-file-description" message="Tidak ada laporan akhir {{ $filterStatus ? 'dengan status ini' : '' }}." />
        </div>
        @endforelse
    </div>

    <div class="pagination-wrap">
        {{ $reports->links('components.pagination', ['paginator' => $reports]) }}
    </div>
</div>
