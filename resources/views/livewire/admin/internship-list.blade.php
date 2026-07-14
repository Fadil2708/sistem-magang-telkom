<div>
    <div class="filter-bar">
        <div class="filter-tabs" role="tablist" aria-label="Filter status">
            <button wire:click="$set('filterStatus', '')" class="filter-tab {{ $filterStatus === '' ? 'active' : '' }}">Semua</button>
            <button wire:click="$set('filterStatus', 'active')" class="filter-tab {{ $filterStatus === 'active' ? 'active' : '' }}">Aktif</button>
            <button wire:click="$set('filterStatus', 'completed')" class="filter-tab {{ $filterStatus === 'completed' ? 'active' : '' }}">Selesai</button>
            <button wire:click="$set('filterStatus', 'terminated')" class="filter-tab {{ $filterStatus === 'terminated' ? 'active' : '' }}">Terminasi</button>
        </div>
        <a href="{{ route('admin.export.internships') }}" class="btn-secondary" style="margin-left:auto">
            <i class="ti ti-download"></i> Export
        </a>
    </div>

    @php
        $internships->each(function($internship) {
            $internship->participantName = $internship->intern->internProfile->full_name ?? $internship->intern->email;
        });
    @endphp

    <div class="panel overflow-x-auto">
        <table class="data">
            <thead>
                <tr>
                    <th>Peserta</th>
                    <th>Lowongan</th>
                    <th>Pembimbing</th>
                    <th>Tgl Mulai</th>
                    <th>Tgl Selesai</th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody wire:loading>
                @for($i = 0; $i < 5; $i++)
                <tr>
                    <td><div class="flex items-center gap-2.5"><div class="skeleton-avatar"></div><div><div class="skeleton-text skeleton-text-lg" style="width:140px"></div><div class="skeleton-text skeleton-text-sm" style="width:180px"></div></div></div></td>
                    <td><div class="skeleton-text skeleton-text-lg" style="width:160px"></div></td>
                    <td><div class="skeleton-text skeleton-text-sm" style="width:120px"></div></td>
                    <td><div class="skeleton-text skeleton-text-sm" style="width:100px"></div></td>
                    <td><div class="skeleton-text skeleton-text-sm" style="width:100px"></div></td>
                    <td><div class="skeleton" style="width:80px;height:22px;border-radius:20px"></div></td>
                    <td><div class="skeleton" style="width:28px;height:28px;border-radius:6px;margin-left:auto"></div></td>
                </tr>
                @endfor
            </tbody>
            <tbody wire:loading.remove>
                @forelse($internships as $internship)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <x-avatar name="{{ $internship->participantName }}" size="32" type="r" />
                            <div>
                                <div class="font-medium">{{ $internship->participantName }}</div>
                                <div style="font-size:12px;color:#A8A5A0">{{ $internship->intern->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $internship->vacancy->title ?? '-' }}</td>
                    <td>{{ $internship->supervisor?->supervisorProfile?->full_name ?? ($internship->supervisor?->email ?? '-') }}</td>
                    <td>{{ $internship->actual_start_date?->format('d M Y') ?? '-' }}</td>
                    <td>{{ $internship->actual_end_date?->format('d M Y') ?? '-' }}</td>
                    <td><x-badge status="{{ $internship->status }}" /></td>
                    <td class="text-right">
                        @if($internship->status === 'active')
                        <div class="action-btns justify-end">
                            <button wire:click="editDates('{{ $internship->id }}')"
                                    wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait"
                                    class="action-btn" title="Atur Tanggal">
                                <i class="ti ti-calendar"></i>
                            </button>
                            <button wire:click="confirmAction('{{ $internship->id }}', 'complete')"
                                    wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait"
                                    class="action-btn success" title="Selesaikan">
                                <i class="ti ti-check"></i>
                            </button>
                            <button wire:click="confirmAction('{{ $internship->id }}', 'terminate')"
                                    wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait"
                                    class="action-btn danger" title="Terminasi">
                                <i class="ti ti-x"></i>
                            </button>
                        </div>
                        @elseif($internship->status === 'completed' && $internship->evaluation && !$internship->evaluation->evaluated_at)
                        <button wire:click="confirmLock('{{ $internship->id }}')"
                                wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait"
                                class="action-btn" title="Kunci Penilaian">
                            <i class="ti ti-lock"></i>
                        </button>
                        @elseif($internship->status === 'completed' && $internship->evaluation && $internship->evaluation->evaluated_at)
                        <span style="font-size:11px;color:#16A34A;font-weight:600">Penilaian terkunci</span>
                        @else
                        <span style="font-size:11px;color:#A8A5A0;font-style:italic">Tidak ada aksi</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <x-empty-state icon="ti-users" message="Belum ada peserta magang." />
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{ $internships->links('components.pagination', ['paginator' => $internships]) }}
    </div>

    @if($confirmingAction)
    <div class="modal-wrap" aria-labelledby="modal-title" role="dialog" aria-modal="true"
         x-data
         @keydown.escape.window="$wire.set('confirmingAction', null)">
        <div class="modal-backdrop" @click="$wire.set('confirmingAction', null)"></div>
        <div class="modal-center">
            <div class="modal-card modal-card-md">
                <div class="modal-header">
                    <h3 class="modal-title">Konfirmasi</h3>
                </div>
                <div class="modal-body">
                    <p style="font-size:13px;color:#5C5A55">
                        Yakin ingin <span class="font-semibold">{{ $actionType === 'terminate' ? 'menerminasi' : 'menyelesaikan' }}</span> magang peserta ini?
                    </p>
                </div>
                <div class="modal-footer">
                    <button wire:click="$set('confirmingAction', null)" class="btn-secondary">Batal</button>
                    <button wire:click="executeAction"
                            wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait"
                            class="btn-save">
                        <span wire:loading.remove>Ya, {{ $actionType === 'terminate' ? 'Terminasi' : 'Selesaikan' }}</span>
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

    @if($confirmingLockId)
    <div class="modal-wrap" aria-labelledby="modal-title" role="dialog" aria-modal="true"
         x-data
         @keydown.escape.window="$wire.set('confirmingLockId', null)">
        <div class="modal-backdrop" @click="$wire.set('confirmingLockId', null)"></div>
        <div class="modal-center">
            <div class="modal-card modal-card-md">
                <div class="modal-header">
                    <h3 class="modal-title">Kunci Penilaian</h3>
                </div>
                <div class="modal-body">
                    <p style="font-size:13px;color:#5C5A55">
                        Yakin ingin mengunci penilaian peserta ini? Setelah dikunci, pembimbing tidak bisa mengedit penilaian.
                    </p>
                </div>
                <div class="modal-footer">
                    <button wire:click="$set('confirmingLockId', null)" class="btn-secondary">Batal</button>
                    <button wire:click="lockEvaluation"
                            wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait"
                            class="btn-save">
                        <span wire:loading.remove>Ya, Kunci</span>
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

    @if($showDatesModal)
    <div class="modal-wrap" aria-labelledby="modal-title" role="dialog" aria-modal="true"
         x-data
         @keydown.escape.window="$wire.set('showDatesModal', false)">
        <div class="modal-backdrop" @click="$wire.set('showDatesModal', false)"></div>
        <div class="modal-center">
            <div class="modal-card modal-card-md">
                <div class="modal-header">
                    <h3 class="modal-title">Atur Tanggal Aktual Magang</h3>
                </div>
                <div class="modal-body">
                    <div class="field" style="margin-bottom:16px">
                        <label>Tanggal Mulai Aktual</label>
                        <input wire:model="actual_start_date" type="date" class="input">
                        @error('actual_start_date') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="field">
                        <label>Tanggal Selesai Aktual</label>
                        <input wire:model="actual_end_date" type="date" class="input">
                        @error('actual_end_date') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="$set('showDatesModal', false)" class="btn-secondary">Batal</button>
                    <button wire:click="saveDates"
                            wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait"
                            class="btn-save">
                        <span wire:loading.remove>Simpan</span>
                        <span wire:loading class="inline-flex items-center gap-1">
                            <i class="ti ti-loader animate-spin"></i>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
