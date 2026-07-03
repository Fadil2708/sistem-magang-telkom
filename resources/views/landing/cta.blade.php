<div class="section-divider-wave"></div>
<div class="welcome-cta" data-reveal>
    <div class="welcome-cta-card">
        <h2 class="welcome-cta-title">Siap Memulai Perjalanan Magang?</h2>
        <p class="welcome-cta-sub">Daftar sekarang dan temukan posisi magang yang sesuai dengan minat dan bakatmu.</p>
        <div class="welcome-cta-actions">
            @auth
                <a href="{{ route('public.vacancies') }}" class="btn-primary btn-hero">
                    <i class="ti ti-briefcase"></i> Lihat Lowongan
                </a>
            @else
                <a href="{{ route('register') }}" class="btn-primary btn-hero">
                    <i class="ti ti-user-plus"></i> Daftar Sekarang
                </a>
                <a href="{{ route('login') }}" class="btn-hero-secondary">
                    <i class="ti ti-login"></i> Masuk
                </a>
            @endauth
        </div>
    </div>
</div>
