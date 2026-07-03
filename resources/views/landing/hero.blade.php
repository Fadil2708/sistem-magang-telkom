<div class="welcome-hero" id="section-hero">
    <div class="welcome-hero-bg"></div>
    <div class="hero-pattern-overlay"></div>
    <div class="welcome-hero-shapes">
        <div class="hero-shape hero-shape-1"></div>
        <div class="hero-shape hero-shape-2"></div>
        <div class="hero-shape hero-shape-3"></div>
        <div class="hero-shape hero-shape-4"></div>
    </div>
    <div class="welcome-hero-inner">
        <div class="welcome-hero-content" data-reveal>
            <div class="hero-brand">
                <picture><source srcset="{{ asset('images/TLK.webp') }}" type="image/webp"><img src="{{ asset('images/TLK.png') }}" alt="Telkom" class="hero-brand-logo-img" width="48" height="48"></picture>
                <div class="hero-brand-text">
                    <div class="hero-brand-name">Telkom Sukabumi</div>
                    <div class="hero-brand-sub">Sistem Informasi Pengelolaan Magang & PKL</div>
                </div>
            </div>
            <h1 class="welcome-hero-title">Temukan Kesempatan Magang<br>di <span class="grad-brand-text">Telkom Sukabumi</span></h1>
            <p class="welcome-hero-sub">
                Pendaftaran, monitoring, dan evaluasi program magang dan PKL<br>
                di lingkungan Telkom Sukabumi dalam satu platform digital.
            </p>
            <div class="welcome-hero-actions">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-primary btn-hero">
                        <i class="ti ti-layout-dashboard"></i> Dashboard
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
        <div class="hero-visual" data-reveal
             x-data="{ x: 0, y: 0 }"
             @mousemove="x = (event.offsetX / $el.offsetWidth - 0.5) * 16; y = (event.offsetY / $el.offsetHeight - 0.5) * 16"
             @mouseleave="x = 0; y = 0"
             :style="{ transform: `translate(${x}px, ${y}px)` }">
            <div class="hero-visual-frame">
                <picture>
                    <source srcset="{{ asset('images/gedungtelkom.webp') }}" type="image/webp">
                    <img src="{{ asset('images/gedungtelkom.jpg') }}"
                         alt="Gedung Telkom Sukabumi"
                         class="hero-visual-img"
                         width="800" height="600"
                         loading="eager">
                </picture>
                <div class="hero-visual-glow"></div>
            </div>
        </div>
    </div>
</div>
