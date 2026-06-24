<div>
    <div class="filter-bar">
        <div class="search-box">
            <i class="ti ti-search"></i>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama atau email...">
        </div>
        <div class="filter-tabs" role="tablist" aria-label="Filter role">
            <button wire:click="$set('filterRole', '')" class="filter-tab {{ $filterRole === '' ? 'active' : '' }}">Semua</button>
            <button wire:click="$set('filterRole', 'admin')" class="filter-tab {{ $filterRole === 'admin' ? 'active' : '' }}">Admin</button>
            <button wire:click="$set('filterRole', 'supervisor')" class="filter-tab {{ $filterRole === 'supervisor' ? 'active' : '' }}">Pembimbing</button>
            <button wire:click="$set('filterRole', 'intern')" class="filter-tab {{ $filterRole === 'intern' ? 'active' : '' }}">Peserta</button>
        </div>
        <a href="{{ route('admin.users.create') }}" wire:navigate class="btn-primary" style="display:inline-flex;align-items:center;gap:6px;margin-left:auto">
            <i class="ti ti-user-plus"></i> Tambah Pengguna
        </a>
    </div>

    <div class="panel" style="overflow-x:auto">
        <table class="data">
            <thead>
                <tr>
                    <th>Pengguna</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Tgl Daftar</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody wire:loading>
                @for($i = 0; $i < 5; $i++)
                <tr>
                    <td><div class="flex items-center gap-2.5"><div class="skeleton-avatar"></div><div><div class="skeleton-text skeleton-text-lg" style="width:140px"></div><div class="skeleton-text skeleton-text-sm" style="width:180px"></div></div></div></td>
                    <td><div class="skeleton" style="width:90px;height:22px;border-radius:20px"></div></td>
                    <td><div class="skeleton-text skeleton-text-sm" style="width:60px"></div></td>
                    <td><div class="skeleton-text skeleton-text-sm" style="width:80px"></div></td>
                    <td><div class="skeleton" style="width:28px;height:28px;border-radius:6px;margin-left:auto"></div></td>
                </tr>
                @endfor
            </tbody>
            <tbody wire:loading.remove>
                @forelse($users as $user)
                @php
                    $displayName = $user->internProfile?->full_name ?? $user->supervisorProfile?->full_name ?? $user->email;
                @endphp
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <x-avatar name="{{ $displayName }}" size="32" type="r" />
                            <div>
                                <div class="font-medium">{{ $displayName }}</div>
                                <div style="font-size:12px;color:#A8A5A0">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="role-chip role-{{ $user->role }}">
                            {{ $user->role === 'admin' ? 'Admin' : ($user->role === 'supervisor' ? 'Pembimbing' : 'Peserta') }}
                        </span>
                    </td>
                    <td>
                        <span style="display:inline-flex;align-items:center;gap:6px;font-size:12px">
                            <span style="width:6px;height:6px;border-radius:50%;display:inline-block;background:{{ $user->is_active ? '#16A34A' : '#DC2626' }}"></span>
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>{{ $user->created_at->format('d M Y') }}</td>
                    <td class="text-right">
                        <div class="action-btns justify-end">
                            <a href="{{ route('admin.users.edit', $user->id) }}" wire:navigate class="action-btn" title="Edit">
                                <i class="ti ti-pencil"></i>
                            </a>
                            <button wire:click="confirmDeactivate('{{ $user->id }}')"
                                    wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait"
                                    class="action-btn danger" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                <i class="ti ti-{{ $user->is_active ? 'user-x' : 'user-check' }}"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <x-empty-state icon="ti-users" message="Belum ada pengguna." />
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{ $users->links('components.pagination', ['paginator' => $users]) }}
    </div>

    @if($confirmingDeactivateId)
    <div class="modal-wrap" aria-labelledby="modal-title" role="dialog" aria-modal="true"
         x-data
         @keydown.escape.window="$wire.set('confirmingDeactivateId', null)">
        <div class="modal-backdrop" @click="$wire.set('confirmingDeactivateId', null)"></div>
        <div class="modal-center">
            <div class="modal-card modal-card-md">
                <div class="modal-header">
                    <h3 id="modal-title" class="modal-title">Konfirmasi</h3>
                </div>
                <div class="modal-body">
                    <p style="font-size:13px;color:#5C5A55">Yakin ingin mengubah status akun pengguna ini?</p>
                </div>
                <div class="modal-footer">
                    <button wire:click="$set('confirmingDeactivateId', null)" class="btn-secondary">Batal</button>
                    <button wire:click="deactivate"
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
