<div>
    <div class="filter-bar">
        <div class="search-box">
            <i class="ti ti-search"></i>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari peserta...">
        </div>
        <div class="filter-tabs">
            <button wire:click="$set('filterStatus', '')" class="filter-tab" :class="{ 'active': !filterStatus }">Semua Status</button>
            <button wire:click="$set('filterStatus', 'active')" class="filter-tab" :class="{ 'active': filterStatus === 'active' }">Aktif</button>
            <button wire:click="$set('filterStatus', 'completed')" class="filter-tab" :class="{ 'active': filterStatus === 'completed' }">Selesai</button>
            <button wire:click="$set('filterStatus', 'terminated')" class="filter-tab" :class="{ 'active': filterStatus === 'terminated' }">Terminasi</button>
        </div>
    </div>

    <div class="panel table-wrap">
        <table class="data">
            <thead>
                <tr>
                    <th>Peserta</th>
                    <th>Lowongan</th>
                    <th>Periode</th>
                    <th>Logbook</th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody wire:loading>
                @for($i = 0; $i < 5; $i++)
                <tr>
                    <td><div class="flex items-center gap-2.5"><div class="skeleton-avatar"></div><div><div class="skeleton-text skeleton-text-lg" style="width:140px"></div><div class="skeleton-text skeleton-text-sm" style="width:180px"></div></div></div></td>
                    <td><div class="skeleton-text skeleton-text-lg" style="width:160px"></div></td>
                    <td><div class="skeleton-text skeleton-text-sm" style="width:160px"></div></td>
                    <td><div class="skeleton" style="width:120px;height:18px;border-radius:4px"></div></td>
                    <td><div class="skeleton" style="width:70px;height:22px;border-radius:20px"></div></td>
                    <td><div class="skeleton" style="width:28px;height:28px;border-radius:6px;margin-left:auto"></div></td>
                </tr>
                @endfor
            </tbody>
            <tbody wire:loading.remove>
                @forelse($internships as $internship)
                <tr>
                    <td>
                        <div class="intern-row">
                            <x-avatar :name="$internship->intern->internProfile->full_name ?? $internship->intern->email ?? ''" :size="32" />
                            <div>
                                <div class="font-medium">{{ $internship->intern->internProfile->full_name ?? $internship->intern->email }}</div>
                                <div class="text-caption">{{ $internship->intern->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $internship->vacancy->title }}</td>
                    <td class="text-caption">{{ ($internship->actual_start_date ?? $internship->vacancy?->start_date)?->format('d M Y') ?? '—' }} - {{ ($internship->actual_end_date ?? $internship->vacancy?->end_date)?->format('d M Y') ?? '—' }}</td>
                    <td>
                        <div class="intern-row">
                            <div class="intern-row-cell">
                                <div class="progress-track">
                                    @php $pct = $internship->total_logbooks > 0 ? round(($internship->approved_logbooks / $internship->total_logbooks) * 100) : 0; @endphp
                                    <div class="progress-fill" style="width:{{ $pct }}%"></div>
                                </div>
                            </div>
                            <span class="progress-pct">{{ $internship->approved_logbooks }}/{{ $internship->total_logbooks }}</span>
                        </div>
                    </td>
                    <td><x-badge status="{{ $internship->status }}" /></td>
                    <td class="text-right">
                        <div class="action-btns justify-end">
                            <a href="{{ route('supervisor.logbooks', ['intern_id' => $internship->intern_id]) }}" class="action-btn" title="Lihat Logbook">
                                <i class="ti ti-notebook"></i>
                            </a>
                            <a href="{{ route('supervisor.evaluations.show', $internship->id) }}" class="action-btn" title="Beri Nilai">
                                <i class="ti ti-star"></i>
                            </a>
                            <a href="{{ route('supervisor.interns.show', $internship) }}" class="action-btn" title="Detail">
                                <i class="ti ti-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <x-empty-state icon="ti-users" message="Belum ada peserta." />
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{ $internships->links('components.pagination', ['paginator' => $internships]) }}
    </div>
</div>
