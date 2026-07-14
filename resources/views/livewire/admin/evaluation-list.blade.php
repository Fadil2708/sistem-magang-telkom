<div>
    <div class="filter-bar">
        <div class="filter-tabs" role="tablist" aria-label="Filter grade">
            <button wire:click="$set('filterGrade', '')" class="filter-tab {{ $filterGrade === '' ? 'active' : '' }}">Semua</button>
            <button wire:click="$set('filterGrade', 'A')" class="filter-tab {{ $filterGrade === 'A' ? 'active' : '' }}">A</button>
            <button wire:click="$set('filterGrade', 'B')" class="filter-tab {{ $filterGrade === 'B' ? 'active' : '' }}">B</button>
            <button wire:click="$set('filterGrade', 'C')" class="filter-tab {{ $filterGrade === 'C' ? 'active' : '' }}">C</button>
            <button wire:click="$set('filterGrade', 'D')" class="filter-tab {{ $filterGrade === 'D' ? 'active' : '' }}">D</button>
        </div>
        <a href="{{ route('admin.export.evaluations') }}" class="btn-secondary" style="margin-left:auto">
            <i class="ti ti-download"></i> Export
        </a>
    </div>

    <div class="panel overflow-x-auto">
        <table class="data">
            <thead>
                <tr>
                    <th>Peserta</th>
                    <th>Lowongan</th>
                    <th>Pembimbing</th>
                    <th>Nilai</th>
                    <th>Grade</th>
                    <th>Tgl Nilai</th>
                </tr>
            </thead>
            <tbody>
                @forelse($evaluations as $eva)
                <tr>
                    <td>
                        <div class="font-medium">{{ $eva->internship->intern->internProfile->full_name ?? $eva->internship->intern->email }}</div>
                        <div style="font-size:12px;color:#A8A5A0">{{ $eva->internship->intern->email }}</div>
                    </td>
                    <td>{{ $eva->internship->vacancy->title ?? '-' }}</td>
                    <td>{{ $eva->supervisor?->supervisorProfile?->full_name ?? $eva->supervisor?->email ?? '-' }}</td>
                    <td class="font-medium">{{ number_format($eva->final_score, 0) }}</td>
                    <td><span class="grade-pill grade-{{ $eva->grade }}">{{ $eva->grade }}</span></td>
                    <td>{{ $eva->evaluated_at?->format('d M Y') ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <x-empty-state icon="ti-star" message="Belum ada penilaian." />
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{ $evaluations->links('components.pagination', ['paginator' => $evaluations]) }}
    </div>
</div>
