<div>
    <div class="filter-bar">
        <div class="search-box">
            <i class="ti ti-search"></i>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari lowongan...">
        </div>
        <select wire:model.live="filterDivision" class="filter-tab">
            <option value="">Semua Divisi</option>
            @foreach($divisions as $div)
                <option value="{{ $div }}">{{ $div }}</option>
            @endforeach
        </select>
    </div>

    <div wire:loading class="vacancy-grid">
        @for($i = 0; $i < 4; $i++)
        <div class="panel vacancy-card-inner">
            <div style="padding:20px;flex:1">
                <div class="skeleton-text skeleton-text-lg" style="width:200px;margin-bottom:8px"></div>
                <div class="skeleton-text skeleton-text-sm" style="width:100px;margin-bottom:12px"></div>
                <div class="skeleton-text" style="width:100%;margin-bottom:4px"></div>
                <div class="skeleton-text" style="width:80%"></div>
                <div style="display:flex;justify-content:space-between;margin-top:16px">
                    <div class="skeleton-text skeleton-text-sm" style="width:100px"></div>
                    <div class="skeleton-text skeleton-text-sm" style="width:120px"></div>
                </div>
            </div>
            <div style="padding:12px 20px;border-top:1px solid #E8E6E1">
                <div class="skeleton" style="width:100%;height:36px;border-radius:10px"></div>
            </div>
        </div>
        @endfor
    </div>
    <div wire:loading.remove class="vacancy-grid">
        @forelse($vacancies as $v)
        <div class="panel vacancy-card-inner">
            <div style="padding:20px;flex:1">
                <h3 class="text-h3" style="margin-bottom:4px">{{ $v->title }}</h3>
                <p class="text-body-sm" style="margin-bottom:12px">{{ $v->division }}</p>
                <p class="text-body" style="margin-bottom:16px;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden">{{ Str::limit($v->description, 120) }}</p>
                <div style="display:flex;align-items:center;justify-content:space-between;font-size:12px;color:#A8A5A0">
                    <span><i class="ti ti-users" style="font-size:14px"></i> Kuota: {{ $v->quota }}
                        @if($v->accepted_applications_count >= $v->quota)
                            <span class="badge badge-danger" style="font-size:11px;margin-left:6px">Penuh</span>
                        @endif
                    </span>
                    <span><i class="ti ti-calendar" style="font-size:14px"></i> {{ $v->application_deadline?->format('d M Y') ?? '—' }}</span>
                </div>
            </div>
            <div style="padding:12px 20px;border-top:1px solid #E8E6E1">
                @if($v->accepted_applications_count >= $v->quota)
                    <button class="btn-secondary" disabled style="display:block;text-align:center;width:100%;cursor:not-allowed">
                        <i class="ti ti-x-circle"></i> Kuota Penuh
                    </button>
                @else
                    <a href="{{ route('intern.applications.create', $v->id) }}" class="btn-primary" style="display:block;text-align:center">
                        Lamar Sekarang
                    </a>
                @endif
            </div>
        </div>
        @empty
        <div style="grid-column:1/-1;text-align:center;padding:40px">
            <x-empty-state icon="ti-building" message="Belum ada lowongan tersedia." />
        </div>
        @endforelse
    </div>

    <div class="pagination-wrap">
        {{ $vacancies->links('components.pagination', ['paginator' => $vacancies]) }}
    </div>
</div>
