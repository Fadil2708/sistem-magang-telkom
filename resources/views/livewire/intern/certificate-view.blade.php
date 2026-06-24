<div>
    <div style="max-width:500px;margin:0 auto">
        @if(!$hasCompletedInternship)
            <div class="panel" style="text-align:center;padding:40px">
                <x-empty-state icon="ti-certificate" message="Sertifikat" />
                <p style="font-size:13px;color:#A8A5A0;margin-top:8px">Anda belum memiliki magang yang selesai. Sertifikat akan tersedia setelah magang selesai dan dinilai.</p>
            </div>
        @elseif(!$certificate)
            <div class="panel" style="text-align:center;padding:40px">
                <x-empty-state icon="ti-clock" message="Sertifikat Belum Diterbitkan" />
                <p style="font-size:13px;color:#A8A5A0;margin-top:8px">Magang Anda sudah selesai, namun sertifikat masih dalam proses penerbitan oleh admin.</p>
            </div>
        @else
            <div class="panel cert-preview-card" style="text-align:center;padding:40px">
                <div class="cert-preview-header">
                    <div class="cert-logo-row">
                        <i class="ti ti-certificate" style="font-size:40px;color:#16A34A"></i>
                    </div>
                </div>

                <h3 style="font-size:22px;font-weight:700;color:#FFFFFF;margin:16px 0 4px">Sertifikat Tersedia</h3>
                <p style="color:#C8BFB6;font-size:13px;margin-bottom:24px">{{ $certificate->certificate_number }}</p>

                <div class="grade-display grade-{{ $certificate->grade }}" style="width:60px;height:60px;margin:0 auto 24px">
                    <div class="grade-{{ $certificate->grade }}" style="font-size:20px">{{ $certificate->grade }}</div>
                </div>

                <div style="max-width:280px;margin:0 auto 28px;text-align:left">
                    <div style="display:flex;justify-content:space-between;font-size:13px;padding:6px 0;border-bottom:1px solid rgba(255,255,255,0.08)">
                        <span style="color:#B0A69B">Nilai Akhir</span>
                        <span class="font-medium" style="color:#F0EBE5">{{ number_format($certificate->final_score, 0) }} / 100</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:13px;padding:6px 0;border-bottom:1px solid rgba(255,255,255,0.08)">
                        <span style="color:#B0A69B">Grade</span>
                        <span class="font-medium" style="color:#F0EBE5">{{ $certificate->grade }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:13px;padding:6px 0">
                        <span style="color:#B0A69B">Diterbitkan</span>
                        <span class="font-medium" style="color:#F0EBE5">{{ $certificate->issued_at?->isoFormat('D MMMM Y') ?? '-' }}</span>
                    </div>
                </div>

                <a href="{{ route('intern.certificates.download', $certificate->id) }}" class="btn-primary" style="display:inline-flex;align-items:center;gap:8px">
                    <i class="ti ti-download"></i> Download Sertifikat
                </a>
            </div>
        @endif
    </div>
</div>
