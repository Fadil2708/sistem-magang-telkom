<div>
    @if($pendingInternships->isNotEmpty())
    <div style="margin-bottom:24px;padding:16px;background:#FFFBEB;border:1px solid #FDE68A;border-radius:10px">
        <h3 style="font-size:12px;font-weight:700;color:#92400E;margin-bottom:12px">Magang Selesai — Menunggu Penerbitan Sertifikat</h3>
        <div style="display:flex;flex-direction:column;gap:8px">
            @foreach($pendingInternships as $internship)
            <div style="display:flex;align-items:center;justify-content:space-between;background:#fff;padding:12px;border-radius:8px">
                <div style="font-size:13px">
                    <span class="font-medium">{{ $internship->intern->internProfile->full_name ?? $internship->intern->email }}</span>
                    <span style="color:#A8A5A0;margin:0 8px">—</span>
                    <span style="color:#5C5A55">{{ $internship->vacancy->title }}</span>
                    @if($internship->evaluation)
                    <span style="margin-left:8px;font-size:11px;color:#A8A5A0">(Nilai: {{ number_format($internship->evaluation->final_score, 0) }})</span>
                    @else
                    <span style="margin-left:8px;font-size:11px;color:#DC2626">(Belum dinilai)</span>
                    @endif
                </div>
                <button wire:click="confirmIssue('{{ $internship->id }}')"
                        wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait"
                        @disabled(!$internship->evaluation)
                        class="btn-primary" style="padding:6px 12px;font-size:11px">
                    Terbitkan
                </button>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="filter-bar">
        <div class="search-box">
            <i class="ti ti-search"></i>
            <input wire:model.live="search" type="text" placeholder="Cari nama atau nomor sertifikat...">
        </div>
        <div class="filter-tabs" role="tablist" aria-label="Filter grade">
            <button wire:click="$set('filterGrade', '')" class="filter-tab {{ $filterGrade === '' ? 'active' : '' }}">Semua</button>
            <button wire:click="$set('filterGrade', 'A')" class="filter-tab {{ $filterGrade === 'A' ? 'active' : '' }}">A</button>
            <button wire:click="$set('filterGrade', 'B')" class="filter-tab {{ $filterGrade === 'B' ? 'active' : '' }}">B</button>
            <button wire:click="$set('filterGrade', 'C')" class="filter-tab {{ $filterGrade === 'C' ? 'active' : '' }}">C</button>
            <button wire:click="$set('filterGrade', 'D')" class="filter-tab {{ $filterGrade === 'D' ? 'active' : '' }}">D</button>
        </div>
        <a href="{{ route('admin.export.certificates') }}" class="btn-secondary" style="margin-left:auto">
            <i class="ti ti-download"></i> Export
        </a>
    </div>

    <div class="panel" style="overflow-x:auto">
        <table class="data">
            <thead>
                <tr>
                    <th>Peserta</th>
                    <th>No. Sertifikat</th>
                    <th>Nilai</th>
                    <th>Grade</th>
                    <th>Tgl Terbit</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody wire:loading>
                @for($i = 0; $i < 5; $i++)
                <tr>
                    <td><div><div class="skeleton-text skeleton-text-lg" style="width:140px"></div><div class="skeleton-text skeleton-text-sm" style="width:180px"></div></div></td>
                    <td><div class="skeleton-text skeleton-text-sm" style="width:130px"></div></td>
                    <td><div class="skeleton-text skeleton-text-sm" style="width:40px"></div></td>
                    <td><div class="skeleton" style="width:30px;height:22px;border-radius:4px"></div></td>
                    <td><div class="skeleton-text skeleton-text-sm" style="width:100px"></div></td>
                    <td><div class="skeleton" style="width:28px;height:28px;border-radius:6px;margin-left:auto"></div></td>
                </tr>
                @endfor
            </tbody>
            <tbody wire:loading.remove>
                @forelse($certificates as $cert)
                <tr wire:key="cert-{{ $cert->id }}">
                    <td>
                        <div class="font-medium">{{ $cert->intern->internProfile->full_name ?? $cert->intern->email }}</div>
                        <div style="font-size:12px;color:#A8A5A0">{{ $cert->intern->email }}</div>
                    </td>
                    <td style="font-size:12px">{{ $cert->certificate_number }}</td>
                    <td>{{ number_format($cert->final_score, 0) }}</td>
                    <td><span class="grade-pill grade-{{ $cert->grade }}">{{ $cert->grade }}</span></td>
                    <td>{{ $cert->issued_at ? $cert->issued_at->format('d M Y') : '-' }}</td>
                    <td class="text-right">
                        @if($cert->certificate_file_url)
                        <a href="{{ route('admin.certificates.download', $cert->id) }}" class="action-btn success" title="Download">
                            <i class="ti ti-download"></i>
                        </a>
                        @else
                        <span style="font-size:11px;color:#A8A5A0;font-style:italic">Menunggu PDF</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <x-empty-state icon="ti-certificate" message="Belum ada sertifikat." />
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{ $certificates->links('components.pagination', ['paginator' => $certificates]) }}
    </div>

    @if($confirmingIssueId)
    <div class="modal-wrap" aria-labelledby="modal-title" role="dialog" aria-modal="true"
         x-data
         @keydown.escape.window="$wire.set('confirmingIssueId', null)">
        <div class="modal-backdrop" @click="$wire.set('confirmingIssueId', null)"></div>
        <div class="modal-center">
            <div class="modal-card modal-card-md">
                <div class="modal-header">
                    <h3 class="modal-title">Konfirmasi Penerbitan</h3>
                </div>
                <div class="modal-body">
                    <p style="font-size:13px;color:#5C5A55">Yakin ingin menerbitkan sertifikat untuk peserta ini?</p>
                </div>
                <div class="modal-footer">
                    <button wire:click="$set('confirmingIssueId', null)" class="btn-secondary">Batal</button>
                    <button wire:click="issue"
                            wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait"
                            class="btn-save">
                        <span wire:loading.remove>Ya, Terbitkan</span>
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
