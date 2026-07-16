<div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
    <div wire:loading class="col-span-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        @for($i = 0; $i < 6; $i++)
        <div class="stat-card">
            <div class="skeleton" style="width:40px;height:40px;border-radius:10px;flex-shrink:0"></div>
            <div class="stat-card-body">
                <div class="skeleton-text skeleton-text-lg" style="width:60%"></div>
                <div class="skeleton-text skeleton-text-sm"></div>
            </div>
        </div>
        @endfor
    </div>
    <div wire:loading.remove class="contents">
    <div class="stat-card anim-stagger">
        <div class="stat-card-icon icon-wrap-brand">
            <i class="ti ti-users"></i>
        </div>
        <div class="stat-card-body">
            <div class="stat-card-value">{{ $totalInternsActive }}</div>
            <div class="stat-card-label">Magang Aktif</div>
            <div class="stat-card-footer">
                <div class="progress-mini">
                    <div class="progress-mini-bar brand" style="width:{{ min($totalInternsActive * 10, 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="stat-card anim-stagger">
        <div class="stat-card-icon icon-wrap-blue">
            <i class="ti ti-building"></i>
        </div>
        <div class="stat-card-body">
            <div class="stat-card-value">{{ $totalVacanciesOpen }}</div>
            <div class="stat-card-label">Lowongan Buka</div>
            <div class="stat-card-footer">
                <div class="progress-mini">
                    <div class="progress-mini-bar blue" style="width:{{ min($totalVacanciesOpen * 20, 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="stat-card anim-stagger">
        <div class="stat-card-icon icon-wrap-amber">
            <i class="ti ti-file-description"></i>
        </div>
        <div class="stat-card-body">
            <div class="stat-card-value">{{ $totalApplicationsPending }}</div>
            <div class="stat-card-label">Lamaran Baru</div>
            <div class="stat-card-footer">
                <div class="progress-mini">
                    <div class="progress-mini-bar amber" style="width:{{ min($totalApplicationsPending * 15, 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="stat-card anim-stagger">
        <div class="stat-card-icon icon-wrap-blue">
            <i class="ti ti-notebook"></i>
        </div>
        <div class="stat-card-body">
            <div class="stat-card-value">{{ $totalLogbooksPending }}</div>
            <div class="stat-card-label">Logbook Pending</div>
            <div class="stat-card-footer">
                <div class="progress-mini">
                    <div class="progress-mini-bar amber" style="width:{{ min($totalLogbooksPending * 15, 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="stat-card anim-stagger">
        <div class="stat-card-icon icon-wrap-green">
            <i class="ti ti-certificate"></i>
        </div>
        <div class="stat-card-body">
            <div class="stat-card-value">{{ $certificatesThisMonth }}</div>
            <div class="stat-card-label">Sertifikat Bulan Ini</div>
            <div class="stat-card-footer">
                <div class="progress-mini">
                    <div class="progress-mini-bar green" style="width:{{ min($certificatesThisMonth * 25, 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="stat-card anim-stagger">
        <div class="stat-card-icon icon-wrap-brand">
            <i class="ti ti-clipboard-list"></i>
        </div>
        <div class="stat-card-body">
            <div class="stat-card-value">{{ $totalInternsActive + $certificatesThisMonth }}</div>
            <div class="stat-card-label">Total Aktivitas</div>
            <div class="stat-card-footer">
                <div class="progress-mini">
                    <div class="progress-mini-bar brand" style="width:{{ min(($totalInternsActive + $certificatesThisMonth) * 8, 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

{{-- Monthly Trend Chart --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-5" wire:loading.remove>
    <div class="panel p-4 lg:col-span-2">
        <div class="flex-between mb-3">
            <h3 class="text-h4">Tren Lamaran (6 Bulan)</h3>
            <span class="text-caption">Total: {{ array_sum($monthlyApplications) }} lamaran</span>
        </div>
        @php $maxVal = max(1, max($monthlyApplications)); @endphp
        <div class="chart-bar-group">
            @foreach($monthlyLabels as $idx => $label)
            <div class="chart-bar-wrap">
                <span class="chart-bar-value">{{ $monthlyApplications[$idx] }}</span>
                <div class="chart-bar" style="height: {{ max(4, ($monthlyApplications[$idx] / $maxVal) * 100) }}%"></div>
                <span class="chart-bar-label">{{ $label }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <div class="panel p-4">
        <h3 class="text-h4 mb-3">Status Magang</h3>
        @php
            $totalStat = max(1, $totalInternships);
            $activePct = round(($totalInternsActive / $totalStat) * 100);
            $completedPct = round(($completedInternships / $totalStat) * 100);
            $terminatedPct = round(($terminatedInternships / $totalStat) * 100);
        @endphp
        <div class="flex flex-col gap-3">
            <div>
                <div class="flex-between mb-1">
                    <span class="chart-legend"><span class="chart-legend-dot" style="background:#C0392B"></span> Aktif</span>
                    <span class="text-caption">{{ $totalInternsActive }} ({{ $activePct }}%)</span>
                </div>
                <div class="chart-hbar-track"><div class="chart-hbar" style="width:{{ $activePct }}%;background:#C0392B"></div></div>
            </div>
            <div>
                <div class="flex-between mb-1">
                    <span class="chart-legend"><span class="chart-legend-dot" style="background:#16A34A"></span> Selesai</span>
                    <span class="text-caption">{{ $completedInternships }} ({{ $completedPct }}%)</span>
                </div>
                <div class="chart-hbar-track"><div class="chart-hbar" style="width:{{ $completedPct }}%;background:#16A34A"></div></div>
            </div>
            <div>
                <div class="flex-between mb-1">
                    <span class="chart-legend"><span class="chart-legend-dot" style="background:#DC2626"></span> Terminasi</span>
                    <span class="text-caption">{{ $terminatedInternships }} ({{ $terminatedPct }}%)</span>
                </div>
                <div class="chart-hbar-track"><div class="chart-hbar" style="width:{{ $terminatedPct }}%;background:#DC2626"></div></div>
            </div>
        </div>
        <div class="mt-4 pt-3" style="border-top:1px solid #E2E8F0">
            <div class="flex-between mb-1">
                <span class="chart-legend"><span class="chart-legend-dot" style="background:#F59E0B"></span> Kuota Terisi</span>
                <span class="text-caption">{{ $quotaUsed }} / {{ $quotaTotal }}</span>
            </div>
            <div class="chart-hbar-track"><div class="chart-hbar" style="width:{{ $quotaTotal > 0 ? min(round(($quotaUsed / $quotaTotal) * 100), 100) : 0 }}%;background:#F59E0B"></div></div>
        </div>
    </div>
</div>
</div>
