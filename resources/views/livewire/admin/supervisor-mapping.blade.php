<div>
    <div class="filter-bar">
        <select wire:model.live="filterStatus" class="filter-tab">
            <option value="active">Magang Aktif</option>
            <option value="completed">Selesai</option>
            <option value="terminated">Terminasi</option>
            <option value="">Semua</option>
        </select>
    </div>

    <div class="panel overflow-x-auto">
        <table class="data">
            <thead>
                <tr>
                    <th>Peserta</th>
                    <th>Lowongan</th>
                    <th>Pembimbing</th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($internships as $internship)
                <tr>
                    <td>
                        <div class="font-medium">{{ $internship->intern->internProfile->full_name ?? $internship->intern->email }}</div>
                        <div style="font-size:12px;color:#A8A5A0">{{ $internship->intern->email }}</div>
                    </td>
                    <td>{{ $internship->vacancy->title }}</td>
                    <td>
                        @if($internship->supervisor)
                            <span>{{ $internship->supervisor->supervisorProfile->full_name ?? $internship->supervisor->email }}</span>
                        @else
                            <span style="color:#D97706;font-style:italic">Belum ditugaskan</span>
                        @endif
                    </td>
                    <td><x-badge status="{{ $internship->status }}" /></td>
                    <td class="text-right">
                        @if($internship->status === 'active')
                        <select wire:change="assignSupervisor('{{ $internship->id }}', $event.target.value)"
                                wire:loading.attr="disabled" wire:loading.class="opacity-60"
                                class="input" style="width:auto;font-size:12px;padding:4px 8px">
                            <option value="">Pilih Pembimbing</option>
                            @foreach($supervisors as $s)
                                <option value="{{ $s->id }}" @selected($internship->supervisor_id === $s->id)>
                                    {{ $s->supervisorProfile->full_name ?? $s->email }}
                                </option>
                            @endforeach
                        </select>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <x-empty-state icon="ti-users" message="Belum ada data magang." />
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{ $internships->links('components.pagination', ['paginator' => $internships]) }}
    </div>
</div>
