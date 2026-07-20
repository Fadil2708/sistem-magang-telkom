<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Telkom Sukabumi — Sistem Magang PKL')</title>
    <meta name="description" content="@yield('meta_description', 'Sistem Informasi Pengelolaan Magang & PKL Telkom Sukabumi — pendaftaran, monitoring, dan evaluasi program magang secara digital.')">

    {{-- Canonical --}}
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Favicon & Touch Icons --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/webp" sizes="512x512" href="{{ asset('images/TLK.webp') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/TLK.webp') }}">

    {{-- Open Graph & Twitter Card --}}
    <meta property="og:title" content="@yield('title', 'Telkom Sukabumi — Sistem Magang PKL')">
    <meta property="og:description" content="@yield('meta_description', 'Sistem Informasi Pengelolaan Magang & PKL Telkom Sukabumi — pendaftaran, monitoring, dan evaluasi program magang secara digital.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('images/TLK_BIG.png') }}">
    <meta property="og:image:width" content="1583">
    <meta property="og:image:height" content="864">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="{{ asset('images/TLK_BIG.png') }}">

    {{-- Fonts — preconnect + preload + optimized load --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" media="print" id="font-css">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap">
    </noscript>
    <script nonce="{{ $cspNonce }}">document.getElementById('font-css').media='all'</script>

    {{-- CDN resources --}}
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.33.0/dist/tabler-icons.min.css">

    @vite(['resources/css/app.css'])

    {{-- Structured Data --}}
    <script type="application/ld+json" nonce="{{ $cspNonce }}">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "Sistem Magang & PKL Telkom Sukabumi",
        "url": "{{ url('/') }}",
        "description": "Sistem Informasi Pengelolaan Magang & PKL Telkom Sukabumi — pendaftaran, monitoring, dan evaluasi program magang secara digital."
    }
    </script>
</head>
<body style="background: #F8FAFC;">

    <a href="#main-content" class="skip-link">Lompat ke konten utama</a>

    <div class="scroll-progress" x-data="scrollProgress" :style="style"></div>

    @php
        $announcementEnabled = \App\Models\SiteSetting::getValue('announcement_enabled', '0');
        $announcementText = \App\Models\SiteSetting::getValue('announcement_text', '');
        $announcementDeadline = \App\Models\SiteSetting::getValue('announcement_deadline', '');
    @endphp
    @if($announcementEnabled === '1' && $announcementText)
    <div class="alert-bar" x-data="alertBar"
         x-show="show" role="alert">
        <div class="alert-bar-inner">
            <i class="ti ti-sparkles alert-bar-icon"></i>
            <span class="alert-bar-text">
                <strong>{{ $announcementText }}</strong>
                @if($announcementDeadline)
                Daftar sekarang sebelum <span class="alert-bar-deadline">{{ $announcementDeadline }}</span>.
                @endif
            </span>
            <button @click="dismiss" class="alert-bar-close" aria-label="Tutup pengumuman">
                <i class="ti ti-x"></i>
            </button>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════
         NAVBAR — Sticky blurred glass (updated layout)
         ═══════════════════════════════════════════════════════════ --}}
    <nav class="public-nav" x-data="publicNav"
         @click.away="close"
         @keydown.escape.window="close">
        <a href="{{ url('/') }}" class="public-nav-logo">
            <picture><source srcset="{{ asset('images/TLK_BIG.webp') }}" type="image/webp"><img src="{{ asset('images/TLK_BIG.png') }}" alt="Telkom Sukabumi"></picture>
        </a>

        <button @click="toggle" class="public-nav-toggle" aria-label="Menu">
            <i x-show="!navOpen" class="ti ti-menu-2"></i>
            <i x-show="navOpen" class="ti ti-x"></i>
        </button>

        <div class="public-nav-links" :class="{ open: navOpen }">
            <a href="{{ route('public.vacancies') }}"
               class="{{ request()->routeIs('public.vacancies*') ? 'nav-active' : '' }}">Cari Lowongan</a>
            <a href="{{ route('public.testimonials') }}"
               class="{{ request()->routeIs('public.testimonials*') ? 'nav-active' : '' }}">Testimoni</a>
            <a href="{{ url('/#section-faq') }}"
               class="{{ request()->routeIs('home') ? 'nav-active' : '' }}">FAQ</a>
            <a href="{{ route('public.tentang-kami') }}"
               class="{{ request()->routeIs('public.tentang-kami*') ? 'nav-active' : '' }}">Tentang Kami</a>

            {{-- Mobile-only auth buttons --}}
            <hr class="nav-mobile-hr">
            <div class="nav-mobile-auth">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-nav">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-outline-nav">Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-nav">Daftar</a>
                    @endif
                @endauth
            </div>
        </div>

        <div class="public-nav-actions">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-nav">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-outline-nav"><i class="ti ti-login"></i> Masuk</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-nav"><i class="ti ti-user-plus"></i> Daftar</a>
                @endif
            @endauth
        </div>

        <div x-show="navOpen" @click="close"
             x-transition:enter="transition-opacity duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="nav-overlay"></div>
    </nav>

    @yield('content')
    <div id="main-content"></div>
    {{ $slot ?? '' }}

    <footer class="public-footer">
        <div class="public-footer-grid">
            <div class="public-footer-brand">
                <div class="public-footer-logo">
                    <picture><source srcset="{{ asset('images/TLK_BIG.webp') }}" type="image/webp"><img src="{{ asset('images/TLK_BIG.png') }}" alt="Telkom Sukabumi" height="28"></picture>
                </div>
                <p class="public-footer-desc">Sistem informasi terpadu untuk pendaftaran, monitoring, dan evaluasi program magang dan PKL di lingkungan Telkom Sukabumi.</p>
                <div class="public-footer-social">
                    <a href="https://www.instagram.com/telkomsukabumi" target="_blank" rel="noopener noreferrer" aria-label="Instagram Telkom Sukabumi">
                        <i class="ti ti-brand-instagram"></i>
                    </a>
                    <a href="https://www.linkedin.com/company/telkom-indonesia" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn Telkom Indonesia">
                        <i class="ti ti-brand-linkedin"></i>
                    </a>
                    <a href="https://www.youtube.com/@TelkomIndonesia" target="_blank" rel="noopener noreferrer" aria-label="YouTube Telkom Indonesia">
                        <i class="ti ti-brand-youtube"></i>
                    </a>
                    <a href="https://wa.me/6285881683025" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp Telkom Sukabumi">
                        <i class="ti ti-brand-whatsapp"></i>
                    </a>
                </div>
            </div>
            <div class="public-footer-col">
                <h4 class="public-footer-title">Navigasi</h4>
                <div class="public-footer-links">
                    <a href="{{ route('public.vacancies') }}">Lowongan</a>
                    <a href="{{ route('public.testimonials') }}">Testimoni</a>
                    <a href="{{ url('/#section-faq') }}">FAQ</a>
                    <a href="{{ route('public.tentang-kami') }}">Tentang Kami</a>
                    <a href="{{ route('public.syarat') }}">Syarat & Ketentuan</a>
                    <a href="{{ route('public.privacy') }}">Kebijakan Privasi</a>
                </div>
            </div>
            <div class="public-footer-col">
                <h4 class="public-footer-title">Kontak</h4>
                <a href="https://maps.app.goo.gl/FQyBPdFQeCeWGrug6" target="_blank" rel="noopener noreferrer" class="public-footer-contact">
                    <i class="ti ti-map-pin"></i>
                    Jl. Masjid No.1, Gunungparang,<br>Kec. Cikole, Kota Sukabumi<br>Jawa Barat 43111
                </a>
                <a href="tel:+6285881683025" class="public-footer-contact">
                    <i class="ti ti-phone"></i>
                    +62 858-8168-3025
                </a>
                <a href="mailto:magang@telkomsukabumi.co.id" class="public-footer-contact">
                    <i class="ti ti-mail"></i>
                    magang@telkomsukabumi.co.id
                </a>
            </div>
            <div class="public-footer-col">
                <h4 class="public-footer-title">Lokasi</h4>
                <a href="https://maps.app.goo.gl/FQyBPdFQeCeWGrug6" target="_blank" rel="noopener noreferrer" class="public-footer-map">
                    <picture><source srcset="{{ asset('images/map-thumbnail.webp') }}" type="image/webp"><img src="{{ asset('images/map-thumbnail.png') }}" alt="Peta lokasi Telkom Sukabumi" loading="lazy"></picture>
                </a>
            </div>
        </div>
        <hr class="public-footer-divider">
        <div class="public-footer-bottom">
            <span>&copy; {{ date('Y') }} Telkom Indonesia &middot; Sukabumi</span>
            <span>Dibangun dengan <i class="ti ti-heart-filled" style="color:#C0392B"></i> oleh Tim IT Telkom Sukabumi</span>
        </div>
    </footer>

    <div class="cookie-consent" x-data="cookieConsent"
         x-show="show" x-cloak>
        <div class="cookie-consent-inner">
            <div class="cookie-consent-text">
                <i class="ti ti-cookie cookie-consent-icon"></i>
                <span>Kami menggunakan cookie untuk meningkatkan pengalaman Anda. Dengan melanjutkan, Anda menyetujui penggunaan cookie sesuai <a href="#" class="cookie-consent-link">Kebijakan Privasi</a> kami.</span>
            </div>
            <div class="cookie-consent-actions">
                <button @click="accept" class="btn-primary" style="padding:8px 18px;font-size:12px;white-space:nowrap">Terima</button>
            </div>
        </div>
    </div>

    @auth
        <div class="mobile-cta">
            <a href="{{ route('public.vacancies') }}" class="btn-primary btn-cta">
                <i class="ti ti-briefcase"></i> Lihat Lowongan
            </a>
        </div>
    @else
        <div class="mobile-cta">
            <a href="{{ route('register') }}" class="btn-primary btn-cta">
                <i class="ti ti-user-plus"></i> Daftar Sekarang
            </a>
            <a href="{{ route('login') }}" class="btn-outline-nav" style="flex:1;justify-content:center;padding:12px 18px;font-size:14px;border-radius:12px;text-align:center;">
                <i class="ti ti-login"></i> Masuk
            </a>
        </div>
    @endauth

    <a href="https://wa.me/6285881683025?text=Halo%20Telkom%20Sukabumi%2C%20saya%20ingin%20bertanya%20tentang%20program%20magang."
       target="_blank" rel="noopener noreferrer"
       class="whatsapp-float"
       aria-label="Hubungi via WhatsApp">
        <i class="ti ti-brand-whatsapp"></i>
    </a>

    @vite(['resources/js/app.js'])
    @stack('scripts')
</body>
</html>
