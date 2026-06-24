<div>
    <div class="filter-bar">
        <select wire:model.live="filterStatus" class="filter-tab">
            <option value="">Semua Status</option>
            <option value="draft">Draft</option>
            <option value="submitted">Terkirim</option>
            <option value="approved">Disetujui</option>
            <option value="revision_requested">Perlu Revisi</option>
        </select>
        @if($hasActiveInternship)
            <a href="{{ route('intern.logbooks.create') }}" wire:navigate class="btn-primary inline-flex items-center gap-1">
                <i class="ti ti-plus"></i> Logbook Baru
            </a>
        @endif
    </div>

    @if(!$hasActiveInternship)
        <div class="panel" style="text-align:center;padding:40px">
            <x-empty-state icon="ti-notebook" message="Anda belum memiliki magang aktif. Logbook hanya bisa diisi saat magang berlangsung." />
            <a href="{{ route('intern.vacancies') }}" class="link-brand" style="display:inline-block;margin-top:12px">Cari Lowongan</a>
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:16px">
            @forelse($logbooks as $logbook)
            <div class="panel lb-card">
                <div class="lb-card-top">
                    <div class="lb-card-body">
                        <div class="lb-card-date-row">
                            <span class="lb-card-date">{{ $logbook->activity_date?->isoFormat('dddd, D MMMM Y') ?? '—' }}</span>
                            <x-badge status="{{ $logbook->validation_status }}" />
                        </div>
                        <p class="lb-card-text"><span class="font-medium">Kegiatan:</span> {{ Str::limit($logbook->activities, 150) }}</p>
                        <p class="lb-card-text"><span class="font-medium">Output:</span> {{ Str::limit($logbook->output, 100) }}</p>
                        @if($logbook->supervisor_notes)
                            <div class="lb-card-notes">
                                <strong>Catatan Pembimbing:</strong>
                                <p>{{ $logbook->supervisor_notes }}</p>
                            </div>
                        @endif
                        @if($logbook->reviewed_at)
                            <p class="text-caption" style="margin-top:8px">Direview {{ $logbook->reviewed_at->diffForHumans() }}</p>
                        @endif
                    </div>
                </div>
                @if($logbook->validation_status === 'draft')
                    <div style="padding:12px 16px;border-top:1px solid #E8E6E1;display:flex;align-items:center;gap:8px">
                        <button @click="window.dispatchEvent(new CustomEvent('confirm', { detail: { message: 'Kirim logbook ini ke supervisor?', callback: () => $wire.submit('{{ $logbook->id }}') } }))"
                                class="btn-sm-success">
                            <i class="ti ti-send"></i> Kirim
                        </button>
                        <a href="{{ route('intern.logbooks.edit', $logbook->id) }}" wire:navigate style="display:inline-flex;align-items:center;gap:4px;padding:6px 12px;background:#F5F4F2;color:#5C5A55;border:1px solid #E8E6E1;border-radius:6px;font-size:11px;font-weight:600;text-decoration:none;transition:all 0.15s">
                            <i class="ti ti-pencil"></i> Edit
                        </a>
                        <button @click="window.dispatchEvent(new CustomEvent('confirm', { detail: { message: 'Hapus logbook ini?', callback: () => $wire.delete('{{ $logbook->id }}') } }))"
                                class="btn-sm-danger" style="margin-left:auto">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                @endif
            </div>
            @empty
            <div class="panel" style="text-align:center;padding:40px">
                <x-empty-state icon="ti-notebook" message="Belum ada logbook." />
                <a href="{{ route('intern.logbooks.create') }}" wire:navigate class="btn-primary inline-flex items-center gap-1" style="margin-top:12px">
                    <i class="ti ti-plus"></i> Buat Logbook Baru
                </a>
            </div>
            @endforelse
        </div>

        <div class="pagination-wrap">
            {{ $logbooks->links('components.pagination', ['paginator' => $logbooks]) }}
        </div>
    @endif
</div>
