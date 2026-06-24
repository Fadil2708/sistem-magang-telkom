<div>
    @php
        $hasNoApp = $applicationStatus === '-';
        $hasPendingApp = in_array($applicationStatus, ['submitted', 'under_review']);
        $hasInterview = $applicationStatus === 'interview_scheduled';
        $isAccepted = $applicationStatus === 'accepted';
        $isRejected = $applicationStatus === 'rejected';
        $isActive = $internshipStatus === 'active';
        $isCompleted = $internshipStatus === 'completed';
    @endphp

    @if($hasNoApp)
    <div class="hero-card">
        <div class="hero-card-content">
            <div class="hero-icon-box hero-icon-brand">
                <i class="ti ti-rocket"></i>
            </div>
            <div class="hero-text">
                <h3 class="hero-title">Mulai Perjalanan Magangmu!</h3>
                <p class="hero-desc">Lengkapi profil dan temukan lowongan yang sesuai dengan minatmu.</p>
            </div>
            <div class="hero-actions">
                <a href="{{ route('intern.profile') }}" class="hero-btn-ghost">
                    <i class="ti ti-user"></i> Profil
                </a>
                <a href="{{ route('intern.vacancies') }}" class="hero-btn-solid">
                    <i class="ti ti-briefcase"></i> Cari Lowongan
                </a>
            </div>
        </div>
    </div>

    @elseif($hasPendingApp || $hasInterview)
    <div class="hero-card">
        <div class="hero-card-content">
            <div class="hero-icon-box hero-icon-amber">
                <i class="ti ti-clock-hour"></i>
            </div>
            <div class="hero-text">
                <h3 class="hero-title">Lamaran Diproses</h3>
                <p class="hero-desc">
                    Lamaran kamu sedang {{ $hasInterview ? 'dijadwalkan interview' : 'direview oleh tim HR' }}. Pantau terus statusnya.
                </p>
            </div>
            <a href="{{ route('intern.applications') }}" class="hero-btn-solid">
                <i class="ti ti-file-description"></i> Cek Status
            </a>
        </div>
    </div>

    @elseif($isAccepted && !$isActive)
    <div class="hero-card" style="background:linear-gradient(135deg,#065F46,#047857)">
        <div class="hero-card-content">
            <div class="hero-icon-box" style="background:rgba(255,255,255,0.1);color:#fff">
                <i class="ti ti-circle-check"></i>
            </div>
            <div class="hero-text">
                <h3 class="hero-title">Selamat! Kamu Diterima!</h3>
                <p class="hero-desc" style="color:rgba(255,255,255,0.7)">
                    Admin akan segera mengatur jadwal dan pembimbing untuk magangmu.
                </p>
            </div>
        </div>
    </div>

    @elseif($isActive)
    <div class="hero-card">
        <div class="hero-card-content">
            <div class="hero-icon-box hero-icon-green">
                <i class="ti ti-notebook"></i>
            </div>
            <div class="hero-text">
                <h3 class="hero-title">
                    {{ $logbookToday ? 'Logbook Hari Ini Sudah Diisi' : 'Jangan Lupa Isi Logbook Hari Ini!' }}
                </h3>
                <p class="hero-desc">
                    @if($logbookToday)
                        Kamu sudah mengisi {{ $logbookThisMonth }} logbook bulan ini. Pertahankan!
                    @else
                        Catat kegiatan magangmu hari ini agar pembimbing bisa memantau.
                    @endif
                </p>
            </div>
            <a href="{{ $logbookToday ? route('intern.logbooks') : route('intern.logbooks.create') }}" class="hero-btn-solid">
                <i class="ti ti-{{ $logbookToday ? 'notebook' : 'plus' }}"></i>
                {{ $logbookToday ? 'Lihat Logbook' : 'Isi Logbook' }}
            </a>
        </div>
    </div>

    @elseif($isCompleted)
    <div class="hero-card">
        <div class="hero-card-content">
            <div class="hero-icon-box hero-icon-brand">
                <i class="ti ti-certificate"></i>
            </div>
            <div class="hero-text">
                <h3 class="hero-title">Magang Selesai!</h3>
                <p class="hero-desc">
                    @if($hasCertificate)
                        Sertifikat sudah tersedia. Kamu juga bisa mengisi testimoni.
                    @else
                        Admin akan segera menerbitkan sertifikat. Pantau terus halaman sertifikat.
                    @endif
                </p>
            </div>
            <a href="{{ route('intern.certificate') }}" class="hero-btn-solid">
                <i class="ti ti-certificate"></i> Sertifikat
            </a>
        </div>
    </div>

    @elseif($isRejected)
    <div class="hero-card" style="background:linear-gradient(135deg,#991B1B,#7F1D1D)">
        <div class="hero-card-content">
            <div class="hero-icon-box" style="background:rgba(255,255,255,0.1);color:#FCA5A5">
                <i class="ti ti-x-circle"></i>
            </div>
            <div class="hero-text">
                <h3 class="hero-title">Lamaran Belum Berhasil</h3>
                <p class="hero-desc" style="color:rgba(255,255,255,0.7)">
                    Jangan menyerah! Cek lowongan lain yang mungkin cocok untukmu.
                </p>
            </div>
            <a href="{{ route('intern.vacancies') }}" class="hero-btn-solid" style="background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.2)">
                <i class="ti ti-briefcase"></i> Cari Lowongan Lain
            </a>
        </div>
    </div>
    @endif

    <div class="stat-card-row">
        <div class="panel" style="display:flex;align-items:center;justify-content:space-between;padding:12px 14px">
            <div>
                <div class="text-caption" style="color:#5C5A55">Lamaran</div>
                <div style="font-size:11px;font-weight:600;color:#312F2D;margin-top:2px;text-transform:capitalize">{{ $applicationStatus === '-' ? 'Belum ada' : str_replace('_', ' ', $applicationStatus) }}</div>
            </div>
            <x-badge :status="$applicationStatus" />
        </div>
        <div class="panel" style="display:flex;align-items:center;justify-content:space-between;padding:12px 14px">
            <div>
                <div class="text-caption" style="color:#5C5A55">Magang</div>
                <div style="font-size:11px;font-weight:600;color:#312F2D;margin-top:2px;text-transform:capitalize">{{ $internshipStatus === '-' ? 'Belum ada' : str_replace('_', ' ', $internshipStatus) }}</div>
            </div>
            <x-badge :status="$internshipStatus" />
        </div>
        <div class="panel" style="display:flex;align-items:center;justify-content:space-between;padding:12px 14px">
            <div>
                <div class="text-caption" style="color:#5C5A55">Logbook</div>
                <div style="font-size:11px;font-weight:600;color:#312F2D;margin-top:2px">{{ $logbookThisMonth }} bulan ini</div>
            </div>
            <x-badge :status="$logbookToday ? 'approved' : ($internshipStatus === 'active' ? 'draft' : 'skipped')" />
        </div>
        <div class="panel" style="display:flex;align-items:center;justify-content:space-between;padding:12px 14px">
            <div>
                <div class="text-caption" style="color:#5C5A55">Laporan</div>
                <div style="font-size:11px;font-weight:600;color:#312F2D;margin-top:2px;text-transform:capitalize">{{ $reportStatus === '-' ? 'Belum ada' : ($reportStatus === 'pending' ? 'Menunggu' : ($reportStatus === 'approved' ? 'Disetujui' : ($reportStatus === 'rejected' ? 'Ditolak' : $reportStatus))) }}</div>
            </div>
            <x-badge :status="$reportStatus" />
        </div>
        <div class="panel" style="display:flex;align-items:center;justify-content:space-between;padding:12px 14px">
            <div>
                <div class="text-caption" style="color:#5C5A55">Sertifikat</div>
                <div style="font-size:11px;font-weight:600;color:#312F2D;margin-top:2px">{{ $hasCertificate ? 'Tersedia' : '—' }}</div>
            </div>
            @if($hasCertificate)
                <a href="{{ route('intern.certificate') }}" class="action-btn success" title="Download">
                    <i class="ti ti-download"></i>
                </a>
            @else
                <x-badge status="draft" />
            @endif
        </div>
    </div>
</div>
