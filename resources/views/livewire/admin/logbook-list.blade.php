<div>
    <div class="filter-bar">
        <div class="search-box">
            <i class="ti ti-search"></i>
            <input wire:model.live="search" type="text" placeholder="Cari nama peserta...">
        </div>
        <div class="filter-tabs" role="tablist" aria-label="Filter status">
            <button wire:click="$set('filterStatus', '')" class="filter-tab {{ $filterStatus === '' ? 'active' : '' }}">Semua</button>
            <button wire:click="$set('filterStatus', 'draft')" class="filter-tab {{ $filterStatus === 'draft' ? 'active' : '' }}">Draft</button>
            <button wire:click="$set('filterStatus', 'submitted')" class="filter-tab {{ $filterStatus === 'submitted' ? 'active' : '' }}">Submitted</button>
            <button wire:click="$set('filterStatus', 'approved')" class="filter-tab {{ $filterStatus === 'approved' ? 'active' : '' }}">Disetujui</button>
            <button wire:click="$set('filterStatus', 'revision_requested')" class="filter-tab {{ $filterStatus === 'revision_requested' ? 'active' : '' }}">Revisi</button>
        </div>
        <a href="{{ route('admin.export.logbooks') }}" class="btn-secondary" style="margin-left:auto">
            <i class="ti ti-download"></i> Export
        </a>
    </div>

    <div class="panel" style="overflow-x:auto">
        <table class="data">
            <thead>
                <tr>
                    <th>Peserta</th>
                    <th>Lowongan</th>
                    <th>Tanggal</th>
                    <th>Kegiatan</th>
                    <th>Status</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody wire:loading>
                @for($i = 0; $i < 5; $i++)
                <tr>
                    <td><div class="flex items-center gap-2.5"><div class="skeleton-avatar"></div><div><div class="skeleton-text skeleton-text-lg" style="width:140px"></div><div class="skeleton-text skeleton-text-sm" style="width:180px"></div></div></div></td>
                    <td><div class="skeleton-text skeleton-text-lg" style="width:160px"></div></td>
                    <td><div class="skeleton-text skeleton-text-sm" style="width:100px"></div></td>
                    <td><div class="skeleton-text" style="width:200px"></div></td>
                    <td><div class="skeleton" style="width:80px;height:22px;border-radius:20px"></div></td>
                    <td><div class="skeleton-text skeleton-text-sm" style="width:100px"></div></td>
                </tr>
                @endfor
            </tbody>
            <tbody wire:loading.remove>
                @forelse($logbooks as $log)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            @php $logName = $log->intern->internProfile->full_name ?? $log->intern->email; @endphp
                            <x-avatar name="{{ $logName }}" size="32" type="r" />
                            <div>
                                <div class="font-medium">{{ $logName }}</div>
                                <div style="font-size:11px;color:#A8A5A0">{{ $log->intern->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $log->internship->vacancy->title ?? '-' }}</td>
                    <td>{{ $log->activity_date->format('d M Y') }}</td>
                    <td style="max-width:240px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ Str::limit($log->activities, 80) }}</td>
                    <td><x-badge status="{{ $log->validation_status }}" /></td>
                    <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:12px;color:#A8A5A0">
                        {{ $log->supervisor_notes ? Str::limit($log->supervisor_notes, 60) : '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <x-empty-state icon="ti-notebook" message="Belum ada logbook." />
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{ $logbooks->links('components.pagination', ['paginator' => $logbooks]) }}
    </div>
</div>
