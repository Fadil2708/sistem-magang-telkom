<div>
    @if(!$internship)
        <div class="panel" style="text-align:center;padding:40px">
            <x-empty-state icon="ti-star" message="Belum Ada Penilaian" />
            <p style="font-size:13px;color:#A8A5A0;margin-top:8px">Penilaian akan muncul setelah magang selesai dan pembimbing mengisi evaluasi.</p>
        </div>
    @elseif(!$internship->evaluation)
        <div class="panel" style="text-align:center;padding:40px">
            <x-empty-state icon="ti-alert-circle" message="Penilaian Belum Diisi" />
            <p style="font-size:13px;color:#A8A5A0;margin-top:8px">Pembimbing belum mengisi evaluasi untuk magang Anda.</p>
        </div>
    @else
        @php $eva = $internship->evaluation; @endphp
        <div style="max-width:600px;margin:0 auto;display:flex;flex-direction:column;gap:20px">
            <div class="panel" style="text-align:center;padding:32px">
                <div class="grade-display grade-{{ $eva->grade }}">
                    <div class="grade-{{ $eva->grade }}" style="margin:0 auto">{{ $eva->grade }}</div>
                </div>
                <h3 style="font-size:28px;font-weight:700;color:#1E1C1A;margin-top:16px">{{ number_format($eva->final_score, 0) }}</h3>
                <p style="font-size:13px;color:#A8A5A0;margin-top:4px">Nilai Akhir</p>
            </div>

            <div class="panel" style="padding:24px">
                <h4 style="font-size:12px;font-weight:700;color:#5C5A55;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:16px">Detail Penilaian</h4>
                <div class="score-grid">
                    <div class="score-item">
                        <div class="score-val" style="color:#DC2626;font-size:20px">{{ number_format($eva->soft_skill_score, 0) }}</div>
                        <div class="score-lbl">Soft Skill</div>
                        <div class="prog-wrap"><div class="prog-bar" style="width:{{ $eva->soft_skill_score }}%;background:#DC2626"></div></div>
                    </div>
                    <div class="score-item">
                        <div class="score-val" style="color:#2563EB;font-size:20px">{{ number_format($eva->hard_skill_score, 0) }}</div>
                        <div class="score-lbl">Hard Skill</div>
                        <div class="prog-wrap"><div class="prog-bar" style="width:{{ $eva->hard_skill_score }}%;background:#2563EB"></div></div>
                    </div>
                    <div class="score-item">
                        <div class="score-val" style="color:#16A34A;font-size:20px">{{ number_format($eva->attendance_score, 0) }}</div>
                        <div class="score-lbl">Kehadiran</div>
                        <div class="prog-wrap"><div class="prog-bar" style="width:{{ $eva->attendance_score }}%;background:#16A34A"></div></div>
                    </div>
                    <div class="score-item">
                        <div class="score-val" style="color:#6B21A8;font-size:20px">{{ number_format($eva->attitude_score, 0) }}</div>
                        <div class="score-lbl">Sikap</div>
                        <div class="prog-wrap"><div class="prog-bar" style="width:{{ $eva->attitude_score }}%;background:#6B21A8"></div></div>
                    </div>
                </div>
                <div style="margin-top:20px;padding-top:16px;border-top:1px solid #E8E6E1;font-size:13px">
                    <span style="color:#A8A5A0">Pembimbing:</span>
                    <span class="font-medium" style="margin-left:8px">{{ $internship->supervisor?->supervisorProfile?->full_name ?? $internship->supervisor?->email ?? '-' }}</span>
                </div>
                @if($eva->remarks)
                <div style="margin-top:16px;padding-top:16px;border-top:1px solid #E8E6E1">
                    <span style="font-size:13px;color:#A8A5A0">Catatan:</span>
                    <p style="margin-top:4px;font-size:13px;color:#5C5A55">{{ $eva->remarks }}</p>
                </div>
                @endif
            </div>

            <div style="padding:16px;background:#F5F4F2;border-radius:10px;font-size:12px;color:#A8A5A0;text-align:center">
                <p>Nilai Akhir = (Soft Skill × 25%) + (Hard Skill × 35%) + (Kehadiran × 20%) + (Sikap × 20%)</p>
                <p style="margin-top:4px">Grade: A ≥ 85 | B ≥ 70 | C ≥ 55 | D < 55</p>
            </div>
        </div>
    @endif
</div>
