<div>
    <div class="filter-bar">
        <div class="search-box">
            <i class="ti ti-search"></i>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari lowongan...">
        </div>
        <select wire:model.live="filterStatus" class="filter-tab">
            <option value="">Semua Status</option>
            <option value="draft">Draft</option>
            <option value="open">Open</option>
            <option value="closed">Closed</option>
        </select>
        <a href="{{ route('admin.vacancies.create') }}" class="btn-primary btn-sm">
            <i class="ti ti-plus"></i> Lowongan Baru
        </a>
    </div>

    <div class="panel" style="overflow-x:auto">
        <table class="data">
            <thead>
                <tr>
                    <th wire:click="sortBy('title')" class="cursor-pointer">
                        Judul @if($sortField === 'title') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                    </th>
                    <th>Divisi</th>
                    <th>Kuota</th>
                    <th>Batas Daftar</th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody wire:loading>
                @for($i = 0; $i < 5; $i++)
                <tr>
                    <td><div class="skeleton-text skeleton-text-lg" style="width:180px"></div></td>
                    <td><div class="skeleton-text skeleton-text-sm" style="width:100px"></div></td>
                    <td><div class="skeleton-text skeleton-text-sm" style="width:60px"></div></td>
                    <td><div class="skeleton-text skeleton-text-sm" style="width:100px"></div></td>
                    <td><div class="skeleton" style="width:70px;height:22px;border-radius:20px"></div></td>
                    <td><div class="skeleton" style="width:28px;height:28px;border-radius:6px;margin-left:auto"></div></td>
                </tr>
                @endfor
            </tbody>
            <tbody wire:loading.remove>
                @forelse($vacancies as $v)
                <tr>
                    <td><span class="font-medium">{{ $v->title }}</span></td>
                    <td>{{ $v->division ?? '-' }}</td>
                    <td>
                        {{ $v->accepted_applications_count ?? 0 }} / {{ $v->quota }}
                        @if(($v->accepted_applications_count ?? 0) >= $v->quota)
                            <i class="ti ti-circle-check" style="color:#16A34A;font-size:14px;margin-left:4px"></i>
                        @endif
                    </td>
                    <td>{{ $v->application_deadline->format('d M Y') }}</td>
                    <td><x-badge status="{{ $v->status }}" /></td>
                    <td class="text-right">
                        <div class="action-btns justify-end">
                            <a href="{{ route('admin.vacancies.edit', $v->id) }}" class="action-btn" title="Edit">
                                <i class="ti ti-pencil"></i>
                            </a>
                            <button wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait"
                                    @click="window.dispatchEvent(new CustomEvent('confirm', { detail: { message: 'Hapus lowongan ini?', callback: () => $wire.deleteVacancy('{{ $v->id }}') } }))"
                                    class="action-btn danger" title="Hapus">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <x-empty-state icon="ti-building" message="Belum ada lowongan." />
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{ $vacancies->links('components.pagination', ['paginator' => $vacancies]) }}
    </div>
</div>
