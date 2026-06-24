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
