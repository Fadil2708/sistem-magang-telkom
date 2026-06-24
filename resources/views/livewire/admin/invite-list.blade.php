<div>
    <div class="page-header" style="margin-bottom:16px">
        <div>
            <div class="breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <i class="ti ti-chevron-right"></i>
                <span>Kode Undangan</span>
            </div>
            <h2 class="page-title">Kode Undangan Pembimbing</h2>
            <p class="page-sub">Generate kode undangan untuk registrasi akun pembimbing</p>
        </div>
    </div>

    <div style="display:flex;gap:12px;margin-bottom:16px;align-items:flex-start;flex-wrap:wrap">
        <button wire:click="generate" wire:loading.attr="disabled" class="btn-save">
            <i wire:loading.remove class="ti ti-plus"></i>
            <span wire:loading.remove>Buat Kode Baru</span>
            <span wire:loading class="inline-flex items-center gap-1">
                <i class="ti ti-loader animate-spin"></i> Membuat...
            </span>
        </button>
    </div>

    @if(session()->has('inviteCode'))
    <div style="padding:16px;background:#ECFDF5;border:1px solid #A7F3D0;border-radius:10px;margin-bottom:16px">
        <p style="font-size:13px;font-weight:600;color:#065F46;margin-bottom:4px">Kode berhasil dibuat!</p>
        <p style="font-size:24px;font-weight:700;color:#065F46;letter-spacing:4px;font-family:monospace">{{ session('inviteCode') }}</p>
        <p style="font-size:11px;color:#065F46;margin-top:4px">Salin kode ini dan kirimkan ke pembimbing yang akan mendaftar.</p>
    </div>
    @endif

    <div class="panel" style="overflow-x:auto">
        <table class="data">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th>Kadaluarsa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invites as $invite)
                <tr>
                    <td><span style="font-family:monospace;font-weight:700;letter-spacing:2px">{{ $invite->code }}</span></td>
                    <td><span class="chip chip-brand">{{ $invite->role }}</span></td>
                    <td style="font-size:12px;color:#5C5A55">
                        @if($invite->used_at && $invite->email)
                            {{ $invite->email }}
                        @elseif($invite->used_at)
                            <span style="color:#16A34A;font-weight:600">Terpakai</span>
                        @else
                            {{ $invite->email ?? '—' }}
                        @endif
                    </td>
                    <td>
                        @if($invite->used_at)
                            <span class="badge accepted">Terpakai</span>
                        @elseif($invite->expires_at && $invite->expires_at->isPast())
                            <span class="badge rejected">Kadaluarsa</span>
                        @else
                            <span class="badge draft">Aktif</span>
                        @endif
                    </td>
                    <td style="font-size:12px;color:#5C5A55">{{ $invite->created_at->format('d M Y H:i') }}</td>
                    <td style="font-size:12px;color:#5C5A55">
                        @php $expiry = $invite->expires_at ?? $invite->created_at->addHour(); @endphp
                        {{ $expiry->format('d M Y H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <x-empty-state icon="ti-link" message="Belum ada kode undangan." />
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
