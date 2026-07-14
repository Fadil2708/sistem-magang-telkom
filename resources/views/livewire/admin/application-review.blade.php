<div>
    <div class="filter-bar">
        <div class="filter-tabs hide-mobile" role="tablist" aria-label="Filter status">
            <button wire:click="$set('filterStatus', '')" class="filter-tab {{ $filterStatus === '' ? 'active' : '' }}">Semua</button>
            <button wire:click="$set('filterStatus', 'submitted')" class="filter-tab {{ $filterStatus === 'submitted' ? 'active' : '' }}">Submitted</button>
            <button wire:click="$set('filterStatus', 'under_review')" class="filter-tab {{ $filterStatus === 'under_review' ? 'active' : '' }}">Under Review</button>
            <button wire:click="$set('filterStatus', 'interview_scheduled')" class="filter-tab {{ $filterStatus === 'interview_scheduled' ? 'active' : '' }}">Interview</button>
            <button wire:click="$set('filterStatus', 'accepted')" class="filter-tab {{ $filterStatus === 'accepted' ? 'active' : '' }}">Diterima</button>
            <button wire:click="$set('filterStatus', 'rejected')" class="filter-tab {{ $filterStatus === 'rejected' ? 'active' : '' }}">Ditolak</button>
            <button wire:click="$set('filterStatus', 'cancelled')" class="filter-tab {{ $filterStatus === 'cancelled' ? 'active' : '' }}">Dibatalkan</button>
        </div>
        <div class="filter-select-wrap show-mobile">
            <i class="ti ti-filter"></i>
            <select wire:change="$set('filterStatus', $event.target.value)" class="filter-select">
                <option value="">Semua</option>
                <option value="submitted">Submitted</option>
                <option value="under_review">Under Review</option>
                <option value="interview_scheduled">Interview</option>
                <option value="accepted">Diterima</option>
                <option value="rejected">Ditolak</option>
                <option value="cancelled">Dibatalkan</option>
            </select>
        </div>
        <a href="{{ route('admin.export.applications') }}" class="btn-secondary" style="margin-left:auto">
            <i class="ti ti-download"></i> Export
        </a>
    </div>

    <div class="panel overflow-x-auto">
        <table class="data">
            <thead>
                <tr>
                    <th>Pelamar</th>
                    <th>Lowongan</th>
                    <th>Tgl Daftar</th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody wire:loading>
                @for($i = 0; $i < 5; $i++)
                <tr>
                    <td><div class="flex items-center gap-2"><div class="skeleton-avatar"></div><div><div class="skeleton-text skeleton-text-lg" style="width:150px"></div><div class="skeleton-text skeleton-text-sm" style="width:100px"></div></div></div></td>
                    <td><div class="skeleton-text skeleton-text-lg" style="width:180px"></div></td>
                    <td><div class="skeleton-text skeleton-text-sm" style="width:100px"></div></td>
                    <td><div class="skeleton" style="width:80px;height:22px;border-radius:20px"></div></td>
                    <td><div class="skeleton" style="width:28px;height:28px;border-radius:6px;margin-left:auto"></div></td>
                </tr>
                @endfor
            </tbody>
            <tbody wire:loading.remove>
                @forelse($applications as $app)
                <tr>
                    <td>
                        <div class="flex items-center gap-2">
                            <x-avatar :user="$app->intern" size="sm" />
                            <div>
                                <div class="fw-600">{{ $app->intern?->internProfile?->full_name ?? $app->intern->email }}</div>
                                <div class="text-body-sm">{{ $app->intern?->internProfile?->institution_name ?? '—' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $app->vacancy?->title ?? '—' }}</td>
                    <td><span class="text-body-sm">{{ $app->applied_at?->format('d M Y') ?? '—' }}</span></td>
                    <td><x-badge :status="$app->status" /></td>
                    <td class="text-right">
                        <button wire:click="openReview('{{ $app->id }}')" class="action-btn">
                            <i class="ti ti-eye"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        @if($filterStatus || $filterVacancy)
                            <x-empty-state icon="ti-filter-off" message="Tidak ada lamaran dengan filter ini." />
                        @else
                            <x-empty-state icon="ti-inbox" message="Belum ada lamaran masuk." />
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        {{ $applications->links('components.pagination', ['paginator' => $applications]) }}
    </div>

    @if($showReviewModal && $selectedApplication)
    @php $profile = $selectedApplication->intern->internProfile ?? null; @endphp
    <div class="modal-wrap" aria-labelledby="modal-title" role="dialog" aria-modal="true"
         x-data
         @keydown.escape.window="$wire.set('showReviewModal', false)">
        <div class="modal-backdrop" @click="$wire.set('showReviewModal', false)"></div>
        <div class="modal-center" style="align-items:flex-start;padding-top:48px">
            <div class="modal-card modal-card-xl" style="max-height:90vh;overflow-y:auto">
            <div class="modal-header">
                <h3 id="modal-title" class="modal-title">Review Lamaran</h3>
                <button wire:click="$set('showReviewModal', false)" class="action-btn" aria-label="Tutup modal">
                    <i class="ti ti-x"></i>
                </button>
            </div>

            <div class="detail-body">
                <div style="display:flex;gap:24px;flex-wrap:wrap">
                    <div style="flex:0 0 auto;text-align:center">
                        @if($profile && $profile->photo_url)
                            <img src="{{ route('admin.applications.file', [$selectedApplicationId, 'photo']) }}"
                                 alt="Foto {{ $profile->full_name }}" loading="lazy" width="128" height="128"
                                 class="review-photo">
                        @else
                            <x-avatar name="{{ $profile->full_name ?? $selectedApplication->intern->email }}" size="128" type="r" />
                        @endif
                        <div style="margin-top:8px">
                            <p class="text-h3" style="margin:0">{{ $profile->full_name ?? $selectedApplication->intern->email }}</p>
                            <p class="text-caption" style="margin-top:2px">{{ $selectedApplication->intern->email }}</p>
                        </div>
                    </div>

                    <div style="flex:1;min-width:280px">
                        @if($profile)
                        <div class="review-info-grid">
                            <div>
                                <span class="review-label">Institusi</span>
                                <p class="review-value">{{ $profile->institution_name ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="review-label">Jurusan</span>
                                <p class="review-value">{{ $profile->major ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="review-label">NIM/NIS</span>
                                <p class="review-value">{{ $profile->student_id ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="review-label">Jenis Kelamin</span>
                                <p class="review-value">
                                    @if($profile->gender === 'male') Laki-laki
                                    @elseif($profile->gender === 'female') Perempuan
                                    @else -
                                    @endif
                                </p>
                            </div>
                            <div>
                                <span class="review-label">No. HP</span>
                                <p class="review-value">{{ $profile->phone ?? '-' }}</p>
                            </div>
                            <div style="grid-column:span 2">
                                <span class="review-label">Keahlian</span>
                                <p class="review-value">
                                    @if($profile->relationLoaded('skills') || $profile->skills()->exists())
                                        @foreach($profile->skills as $skill)
                                            <span class="skill-badge">{{ $skill->name }}</span>
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>
                        @endif
                        <div class="review-section">
                            <span class="review-label">Lowongan</span>
                            <p class="review-value">{{ $selectedApplication->vacancy->title }}</p>
                        </div>
                        <div class="action-btn-group">
                            @if($profile && $profile->cv_url)
                                <a href="{{ route('admin.applications.file', [$selectedApplicationId, 'cv']) }}" target="_blank"
                                   class="file-btn success">
                                    <i class="ti ti-file-text"></i> Lihat CV
                                </a>
                            @else
                                <span class="file-btn disabled">
                                    <i class="ti ti-file-x"></i> CV tidak tersedia
                                </span>
                            @endif
                            @if($profile && $profile->cover_letter_url)
                                <a href="{{ route('admin.applications.file', [$selectedApplicationId, 'cover-letter']) }}" target="_blank"
                                   class="file-btn success">
                                    <i class="ti ti-mail"></i> Lihat Cover Letter
                                </a>
                            @else
                                <span class="file-btn disabled">
                                    <i class="ti ti-mail-x"></i> Cover Letter tidak tersedia
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <hr class="divider">

            <div class="detail-body" style="padding-top:0">
                <div class="field">
                    <label>Ubah Status</label>
                    <select wire:model.live="reviewStatus" class="input">
                        <option value="under_review">Under Review</option>
                        <option value="interview_scheduled">Jadwalkan Interview</option>
                        <option value="accepted">Terima</option>
                        <option value="rejected">Tolak</option>
                    </select>
                    @php
                        $statusLabels = [
                            'submitted' => 'Submitted',
                            'under_review' => 'Under Review',
                            'interview_scheduled' => 'Interview Scheduled',
                            'accepted' => 'Accepted',
                            'rejected' => 'Rejected',
                        ];
                    @endphp
                    <p class="text-caption" style="margin-top:4px">
                        Status saat ini: <span class="font-medium">{{ $statusLabels[$selectedApplication->status] ?? $selectedApplication->status }}</span>
                    </p>
                </div>

                @if($reviewStatus === 'interview_scheduled')
                <div class="field field-group">
                    <label>Tanggal Interview</label>
                    <input wire:model="interviewDate" type="datetime-local" class="input">
                </div>
                @endif

                @if($reviewStatus === 'rejected')
                <div class="field field-group">
                    <label>Alasan Penolakan <span class="required">*</span></label>
                    <textarea wire:model="rejectionReason" rows="3" class="input"></textarea>
                </div>
                @endif

                <div class="field field-group">
                    <label>Catatan Admin</label>
                    <textarea wire:model="adminNotes" rows="2" class="input"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button wire:click="$set('showReviewModal', false)" class="btn-secondary">Batal</button>
                <button wire:click="updateStatus"
                        wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait"
                        class="btn-save">
                    <i wire:loading.remove class="ti ti-device-floppy"></i>
                    <span wire:loading.remove>Simpan</span>
                    <span wire:loading class="inline-flex items-center gap-1">
                        <i class="ti ti-loader animate-spin"></i>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
    @endif
</div>
