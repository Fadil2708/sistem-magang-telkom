@if(isset($vacancies) && $vacancies->isNotEmpty())
<div class="welcome-vacancies" id="section-vacancies" data-reveal>
    <div class="welcome-vacancies-header">
        <h2 class="welcome-section-title">Lowongan Tersedia</h2>
        <p class="welcome-section-sub">Temukan posisi magang yang sesuai dengan minatmu</p>
    </div>

    {{-- Search Filter Bar (seperti magentaku.id) --}}
    <div class="search-filter-bar" style="margin-bottom:24px">
        <i class="ti ti-search search-filter-icon"></i>
        <input type="text" class="search-filter-input" placeholder="Cari lowongan..." aria-label="Cari lowongan">
        <select class="search-filter-select" aria-label="Filter divisi">
            <option value="">Semua Divisi</option>
            @if(isset($vacancies))
                @foreach($vacancies->pluck('division')->unique()->filter() as $div)
                <option value="{{ $div }}">{{ $div }}</option>
                @endforeach
            @endif
        </select>
        <button class="search-filter-btn"><i class="ti ti-search"></i> Cari</button>
    </div>

    <div class="vacancy-grid" data-reveal-stagger>
        @foreach($vacancies as $vacancy)
        @php
            $isNew = $vacancy->created_at->diffInDays(now()) <= 7;
            $isUrgent = $vacancy->application_deadline && $vacancy->application_deadline->isFuture() && $vacancy->application_deadline->diffInDays(now()) <= 7;
        @endphp
        <div class="panel vacancy-card">
            <div class="vacancy-card-head">
                <div>
                    <h3 class="vacancy-card-title">{{ $vacancy->title }}</h3>
                    @if($vacancy->division)
                    <span class="badge badge-neutral vacancy-card-badge" style="margin-top:4px;display:inline-block">{{ $vacancy->division }}</span>
                    @endif
                </div>
                <div style="display:flex;gap:4px;flex-wrap:wrap;flex-shrink:0">
                    @if($isNew)
                    <span class="vacancy-featured-badge new"><i class="ti ti-sparkles"></i> Baru</span>
                    @endif
                    @if($isUrgent)
                    <span class="vacancy-featured-badge urgent"><i class="ti ti-alert-triangle"></i> Deadline</span>
                    @endif
                </div>
            </div>
            <p class="vacancy-card-desc">{{ Str::limit($vacancy->description, 120) }}</p>
            <div class="vacancy-card-footer">
                <span class="vacancy-card-deadline"
                      x-data="{ left: {{ $vacancy->application_deadline ? $vacancy->application_deadline->isPast() ? 0 : $vacancy->application_deadline->diffInDays(now()) : 0 }} }"
                      x-text="left > 0 ? left + ' hari lagi' : 'Berakhir hari ini'">
                    @if($vacancy->application_deadline)
                    Deadline {{ $vacancy->application_deadline->format('d M Y') }}
                    @endif
                </span>
                <a href="{{ route('public.vacancies.show', $vacancy) }}" class="btn-primary vacancy-card-btn">Lihat</a>
            </div>
        </div>
        @endforeach
    </div>
    <div class="welcome-vacancies-link">
        <a href="{{ route('public.vacancies') }}">
            Lihat Semua Lowongan <i class="ti ti-arrow-right"></i>
        </a>
    </div>
</div>
@else
<div class="welcome-vacancies" data-reveal>
    <div class="welcome-vacancies-header">
        <h2 class="welcome-section-title">Lowongan Tersedia</h2>
        <p class="welcome-section-sub">Temukan posisi magang yang sesuai dengan minatmu</p>
    </div>
    <div class="vacancy-empty">
        <i class="ti ti-briefcase-off"></i>
        <p>Belum ada lowongan saat ini. Pantau terus halaman ini untuk update terbaru.</p>
    </div>
</div>
@endif
