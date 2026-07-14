<div>
    <div class="page-header">
        <div>
            <div class="breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <i class="ti ti-chevron-right"></i>
                <span>Laporan</span>
            </div>
            <h2 class="page-title">Kelola Laporan</h2>
            <p class="page-sub">Tinjau laporan akhir peserta magang</p>
        </div>
        <a href="{{ route('admin.export.reports') }}" class="btn-secondary">
            <i class="ti ti-download"></i> Export
        </a>
    </div>
    <div class="filter-bar">
        <div class="filter-tabs" role="tablist" aria-label="Filter status">
            <button wire:click="$set('filterStatus', '')" class="filter-tab {{ $filterStatus === '' ? 'active' : '' }}">Semua</button>
            <button wire:click="$set('filterStatus', 'pending')" class="filter-tab {{ $filterStatus === 'pending' ? 'active' : '' }}">Pending</button>
            <button wire:click="$set('filterStatus', 'approved')" class="filter-tab {{ $filterStatus === 'approved' ? 'active' : '' }}">Disetujui</button>
            <button wire:click="$set('filterStatus', 'rejected')" class="filter-tab {{ $filterStatus === 'rejected' ? 'active' : '' }}">Ditolak</button>
        </div>
        <a href="{{ route('admin.export.reports') }}" class="btn-secondary" style="margin-left:auto">
            <i class="ti ti-download"></i> Export
        </a>
    </div>

    <div class="panel overflow-x-auto">
        <table class="data">
            <thead>
                <tr>
                    <th>Peserta</th>
                    <th>Judul</th>
                    <th>Pembimbing</th>
                    <th>Tgl Upload</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody wire:loading>
                @for($i = 0; $i < 5; $i++)
                <tr>
                    <td><div class="flex items-center gap-2.5"><div class="skeleton-avatar"></div><div><div class="skeleton-text skeleton-text-lg" style="width:140px"></div><div class="skeleton-text skeleton-text-sm" style="width:180px"></div></div></div></td>
                    <td><div class="skeleton-text" style="width:200px"></div></td>
                    <td><div class="skeleton-text skeleton-text-sm" style="width:120px"></div></td>
                    <td><div class="skeleton-text skeleton-text-sm" style="width:100px"></div></td>
                    <td><div class="skeleton" style="width:80px;height:22px;border-radius:20px"></div></td>
                </tr>
                @endfor
            </tbody>
            <tbody wire:loading.remove>
                @forelse($reports as $report)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            @php $reportName = $report->intern->internProfile->full_name ?? $report->intern->email; @endphp
                            <x-avatar name="{{ $reportName }}" size="32" type="r" />
                            <div>
                                <div class="font-medium">{{ $reportName }}</div>
                                <div style="font-size:12px;color:#A8A5A0">{{ $report->intern->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $report->title }}</td>
                    <td>{{ $report->internship->supervisor?->supervisorProfile?->full_name ?? $report->internship->supervisor?->email ?? '-' }}</td>
                    <td>{{ $report->submitted_at?->format('d M Y') ?? '-' }}</td>
                    <td><x-badge status="{{ $report->supervisor_approval }}" /></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <x-empty-state icon="ti-file-description" message="Belum ada laporan akhir." />
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{ $reports->links('components.pagination', ['paginator' => $reports]) }}
    </div>
</div>
