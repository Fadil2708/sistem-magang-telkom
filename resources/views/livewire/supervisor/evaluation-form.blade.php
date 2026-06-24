<div>
    <div class="form-wrap">
        @if(!$internshipId)
            <div style="display:flex;flex-direction:column;gap:16px">
                <h3 class="text-h3" style="margin-bottom:16px">Pilih Peserta yang Akan Dinilai</h3>
                @forelse($internships as $item)
                <a href="{{ route('supervisor.evaluations.show', $item->id) }}" wire:navigate
                   class="panel eval-student-item">
                    <div class="eval-student-row">
                        <x-avatar :name="$item->intern?->internProfile?->full_name ?? $item->intern?->email ?? ''" :size="36" />
                        <div class="eval-student-info">
                            <p class="eval-student-name">{{ $item->intern?->internProfile?->full_name ?? $item->intern?->email }}</p>
                            <p class="eval-student-meta">{{ $item->vacancy?->title }}</p>
                        </div>
                        <div class="eval-student-score">
                            @if($item->evaluation)
                                <div class="grade-display grade-{{ $item->evaluation->grade }}">{{ $item->evaluation->grade }}</div>
                                <div class="text-caption" style="margin-top:2px">{{ number_format($item->evaluation->final_score, 0) }}</div>
                            @else
                                <span class="eval-unrated">Belum dinilai</span>
                            @endif
                        </div>
                        <i class="ti ti-chevron-right eval-student-arrow"></i>
                    </div>
                </a>
                @empty
                <div class="panel" style="text-align:center;padding:40px">
                    <x-empty-state icon="ti-star" message="Belum ada magang selesai yang perlu dinilai." />
                </div>
                @endforelse
            </div>
        @elseif(!$internship)
            <div class="panel" style="text-align:center;padding:40px">
                <x-empty-state icon="ti-alert-circle" message="Data tidak ditemukan atau Anda tidak berhak mengaksesnya." />
            </div>
        @elseif($isLocked)
            <div class="eval-locked">
                <h3>Penilaian Terkunci</h3>
                <p>Penilaian tidak bisa diubah karena sertifikat sudah diterbitkan.</p>
                <a href="{{ route('supervisor.evaluations.create', '') }}" wire:navigate>Kembali ke daftar</a>
            </div>
        @else
            @php
                $s = floatval($soft_skill_score ?? 0);
                $h = floatval($hard_skill_score ?? 0);
                $att = floatval($attendance_score ?? 0);
                $ati = floatval($attitude_score ?? 0);
                $final = ($s * 0.25) + ($h * 0.35) + ($att * 0.20) + ($ati * 0.20);
                $hasAny = $s > 0 || $h > 0 || $att > 0 || $ati > 0;
                if ($final >= 85) { $grade = 'A'; } elseif ($final >= 70) { $grade = 'B'; } elseif ($final >= 55) { $grade = 'C'; } else { $grade = 'D'; }
                $fields = [
                    ['key' => 'soft_skill_score', 'label' => 'Soft Skill', 'desc' => 'Komunikasi, kerjasama, inisiatif', 'weight' => '25%', 'val' => $s],
                    ['key' => 'hard_skill_score', 'label' => 'Hard Skill', 'desc' => 'Kompetensi teknis sesuai bidang', 'weight' => '35%', 'val' => $h],
                    ['key' => 'attendance_score', 'label' => 'Kehadiran', 'desc' => 'Tingkat kehadiran dan ketepatan waktu', 'weight' => '20%', 'val' => $att],
                    ['key' => 'attitude_score', 'label' => 'Sikap', 'desc' => 'Etika, kedisiplinan, tanggung jawab', 'weight' => '20%', 'val' => $ati],
                ];
            @endphp

            <div class="panel form-card">
                <div class="eval-header">
                    <x-avatar :name="$internship->intern?->internProfile?->full_name ?? $internship->intern?->email ?? ''" :size="40" />
                    <div class="eval-header-text">
                        <h3>{{ $internship->intern?->internProfile?->full_name ?? $internship->intern?->email }}</h3>
                        <p>{{ $internship->vacancy?->title }}</p>
                        @php $evG = $internship->intern?->internProfile?->gender ?? null; @endphp
                        @if($evG)
                            <p class="text-caption" style="margin-top:2px">
                                {{ $evG === 'male' ? 'Laki-laki' : 'Perempuan' }}
                            </p>
                        @endif
                        @php $evSkills = $internship->intern?->internProfile?->skills ?? collect(); @endphp
                        @if($evSkills->isNotEmpty())
                            <div style="margin-top:6px">
                                @foreach($evSkills as $evS)
                                    <span class="skill-badge">{{ $evS->name }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <form wire:submit="confirmSave">
                    <div class="form-layout">
                        <div class="form-main">
                            @foreach($fields as $f)
                            <div class="score-field">
                                <div class="score-field-header">
                                    <div>
                                        <div class="score-field-label">{{ $f['label'] }}</div>
                                        <div class="score-field-desc">{{ $f['desc'] }}</div>
                                    </div>
                                    <div class="score-weight">Bobot {{ $f['weight'] }}</div>
                                </div>
                                <div class="score-slider-row">
                                    <input type="range" min="0" max="100" step="1"
                                           wire:model.live="{{ $f['key'] }}"
                                           class="score-slider">
                                    <input type="number" min="0" max="100"
                                           wire:model.live="{{ $f['key'] }}"
                                           class="input input-score">
                                </div>
                                <div class="score-bar-preview">
                                    <div class="score-bar-fill" style="width: {{ $f['val'] }}%;background:{{ $f['val'] >= 80 ? '#10B981' : ($f['val'] >= 60 ? '#F59E0B' : '#DC2626') }}"></div>
                                </div>
                                @error($f['key']) <div class="field-error">{{ $message }}</div> @enderror
                            </div>
                            @endforeach

                            <div class="field" style="margin-top:8px">
                                <label>Catatan</label>
                                <textarea wire:model="remarks" rows="3" class="input"></textarea>
                                @error('remarks') <div class="field-error">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="form-sidebar" style="{{ !$hasAny ? 'opacity:0.5;pointer-events:none' : '' }}">
                            <div class="panel" style="position:sticky;top:16px">
                                <div class="panel-head"><div class="panel-title">Pratinjau Nilai</div></div>
                                <div style="padding:16px;text-align:center">
                                    <div class="grade-display grade-{{ $hasAny ? $grade : 'C' }}">{{ $hasAny ? $grade : 'C' }}</div>
                                    <div class="score-preview-circle" style="padding:8px 0">
                                        <div class="score-preview-num" style="font-size:36px">{{ $hasAny ? number_format($final, 0) : '0' }}</div>
                                        <div class="score-preview-label">Nilai Akhir</div>
                                    </div>
                                    <div class="text-caption" style="text-align:left;margin-top:12px">
                                        @foreach($fields as $f)
                                        <div class="eval-preview-row">
                                            <span>{{ $f['label'] }}</span>
                                            <span>{{ number_format($f['val'], 0) }} x {{ $f['weight'] }}</span>
                                        </div>
                                        @endforeach
                                        <div class="eval-preview-total">
                                            <span>Total</span>
                                            <span>{{ number_format($final, 0) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="display:flex;align-items:center;gap:16px;margin-top:24px">
                        <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait" class="btn-save">
                            <i wire:loading.remove class="ti ti-device-floppy"></i>
                            <span wire:loading.remove>{{ $evaluation ? 'Simpan Perubahan' : 'Simpan Penilaian' }}</span>
                            <span wire:loading class="inline-flex items-center gap-1">
                                <i class="ti ti-loader animate-spin"></i>
                                Menyimpan...
                            </span>
                        </button>
                        <a href="{{ route('supervisor.evaluations.create', '') }}" wire:navigate class="link-cancel">Kembali ke daftar</a>
                    </div>
                </form>
            </div>

            @if($confirmingSave)
            <div class="modal-wrap" aria-labelledby="confirm-save-title" role="dialog" aria-modal="true"
                 x-data
                 @keydown.escape.window="$wire.set('confirmingSave', false)">
                <div class="modal-backdrop" @click="$wire.set('confirmingSave', false)"></div>
                <div class="modal-center">
                    <div class="modal-card modal-card-md">
                        <div class="modal-header">
                            <h3 id="confirm-save-title" class="modal-title">Konfirmasi Penilaian</h3>
                        </div>
                        <div class="modal-body">
                            <p class="text-body-sm" style="margin-bottom:16px">Yakin ingin menyimpan penilaian berikut?</p>
                            <div class="eval-confirm-summary">
                                @foreach($fields as $f)
                                <div class="eval-confirm-row">
                                    <div class="eval-confirm-label">{{ $f['label'] }}</div>
                                    <div class="eval-confirm-bar">
                                        <div class="eval-confirm-bar-fill" style="width:{{ $f['val'] }}%;background:{{ $f['val'] >= 80 ? '#10B981' : ($f['val'] >= 60 ? '#F59E0B' : '#DC2626') }}"></div>
                                    </div>
                                    <div class="eval-confirm-score">{{ number_format($f['val'], 0) }}</div>
                                </div>
                                @endforeach
                                <div class="eval-confirm-footer">
                                    <span class="eval-confirm-footer-label">Nilai Akhir</span>
                                    <div class="eval-confirm-footer-value">
                                        <div class="grade-display grade-{{ $grade }}" style="font-size:16px;padding:2px 8px">{{ $grade }}</div>
                                        <span style="font-size:18px;font-weight:700;color:#1E1C1A">{{ number_format($final, 0) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button wire:click="$set('confirmingSave', false)" class="btn-secondary">Batal</button>
                            <button wire:click="save"
                                    wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-wait"
                                    class="btn-save">
                                <span wire:loading.remove>Ya, Simpan</span>
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
        @endif
    </div>
</div>
