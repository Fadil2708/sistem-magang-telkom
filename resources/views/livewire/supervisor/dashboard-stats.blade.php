<div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <x-stat-card icon="ti-users" value="{{ $totalInterns }}" label="Peserta Bimbingan" color="red" />
        <x-stat-card icon="ti-notebook" value="{{ $pendingLogbooks }}" label="Logbook Perlu Review" color="amber" />
        <x-stat-card icon="ti-file-description" value="{{ $pendingReports }}" label="Laporan Akhir Menunggu" color="blue" />
        <x-stat-card icon="ti-circle-check" value="{{ $approvedLogbooks }}" label="Logbook Disetujui" color="green" />
    </div>

    @if($totalLogbooks > 0)
    <div class="panel p-4 mt-5">
        <h3 class="text-h4 mb-3">Progress Logbook</h3>
        @php
            $approvedPct = round(($approvedLogbooks / $totalLogbooks) * 100);
            $pendingPct = round(($pendingLogbooks / $totalLogbooks) * 100);
            $revisionPct = round(($revisionLogbooks / $totalLogbooks) * 100);
            $otherPct = 100 - $approvedPct - $pendingPct - $revisionPct;
        @endphp
        <div class="flex gap-1 mb-3" style="height:24px;border-radius:6px;overflow:hidden">
            @if($approvedPct > 0) <div style="width:{{ $approvedPct }}%;background:#16A34A;transition:width 0.5s"></div> @endif
            @if($pendingPct > 0) <div style="width:{{ $pendingPct }}%;background:#D97706;transition:width 0.5s"></div> @endif
            @if($revisionPct > 0) <div style="width:{{ $revisionPct }}%;background:#DC2626;transition:width 0.5s"></div> @endif
            @if($otherPct > 0) <div style="width:{{ $otherPct }}%;background:#E2E8F0;transition:width 0.5s"></div> @endif
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
            <div><span class="chart-legend"><span class="chart-legend-dot" style="background:#16A34A"></span> Disetujui</span><span class="text-caption block mt-0.5">{{ $approvedLogbooks }} ({{ $approvedPct }}%)</span></div>
            <div><span class="chart-legend"><span class="chart-legend-dot" style="background:#D97706"></span> Perlu Review</span><span class="text-caption block mt-0.5">{{ $pendingLogbooks }} ({{ $pendingPct }}%)</span></div>
            <div><span class="chart-legend"><span class="chart-legend-dot" style="background:#DC2626"></span> Revisi</span><span class="text-caption block mt-0.5">{{ $revisionLogbooks }} ({{ $revisionPct }}%)</span></div>
            <div><span class="chart-legend"><span class="chart-legend-dot" style="background:#E2E8F0"></span> Draft/Lainnya</span><span class="text-caption block mt-0.5">{{ $totalLogbooks - $approvedLogbooks - $pendingLogbooks - $revisionLogbooks }} ({{ $otherPct }}%)</span></div>
        </div>
    </div>
    @endif
</div>
