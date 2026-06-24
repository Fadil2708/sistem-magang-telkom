@extends('layouts.public')

@section('content')

{{-- ══════════════════════════════════════════════════════════════
     HERO SECTION — 2-Column Layout with SVG Illustration
     ══════════════════════════════════════════════════════════════ --}}
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

{{-- ══════════════════════════════════════════════════════════════
     REVIEW / ULASAN SECTION (seperti magentaku.id)
     ══════════════════════════════════════════════════════════════ --}}
<div class="welcome-ulasan" id="section-testimonials" data-reveal>
    <div class="ulasan-header">
        <h2 class="welcome-section-title">Ulasan Magang</h2>
        <p class="welcome-section-sub">Apa kata peserta magang sebelumnya</p>
    </div>

    <div class="ulasan-aggregate">
        <div class="ulasan-aggregate-stars">
            <i class="ti ti-star-filled"></i>
            <i class="ti ti-star-filled"></i>
            <i class="ti ti-star-filled"></i>
            <i class="ti ti-star-filled"></i>
            <i class="ti ti-star-filled"></i>
        </div>
        <span class="ulasan-aggregate-score">4.9</span>
        <span class="ulasan-aggregate-label">dari 5.0 • {{ $testimonials->count() }} Ulasan</span>
        <a href="{{ route('public.testimonials') }}" class="ulasan-aggregate-link">Lihat Selengkapnya <i class="ti ti-arrow-right"></i></a>
    </div>

    @if($testimonials->isNotEmpty())
    <div class="testi-carousel" x-data="testiCarousel" style="margin-top:28px">
        <div class="testi-track-wrapper">
            <div class="testi-track" :style="{ transform: `translateX(-${current * 100}%)` }">
                @foreach($testimonials as $testimonial)
                @php
                    $tName = $testimonial->intern?->internProfile?->full_name ?? 'Anonymous';
                    $rating = $testimonial->rating ?? 0;
                @endphp
                <div class="testi-slide">
                    <div class="panel welcome-testi-card">
                        <div class="welcome-testi-author">
                            <x-avatar :name="$tName" :size="36" :font-size="14" />
                            <div>
                                <div class="welcome-testi-name">{{ $tName }}</div>
                                <div class="welcome-testi-inst">{{ $testimonial->intern?->internProfile?->institution_name ?? '' }}</div>
                            </div>
                        </div>
                        @if($rating > 0)
                        <div class="welcome-testi-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="ti {{ $i <= $rating ? 'ti-star-filled' : 'ti-star empty' }}"></i>
                            @endfor
                        </div>
                        @endif
                        <p class="welcome-testi-text">{{ $testimonial->content }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @if($testimonials->count() > 1)
        <div class="testi-dots">
            @foreach($testimonials as $i => $t)
            <button @click="goTo({{ $i }})" :class="{ active: current === {{ $i }} }"
                    :aria-label="'Testimoni ke-{{ $i + 1 }}'" aria-label="Testimoni ke-{{ $i + 1 }}"></button>
            @endforeach
        </div>
        <button @click="prev()" class="testi-arrow prev" aria-label="Sebelumnya"><i class="ti ti-chevron-left"></i></button>
        <button @click="next()" class="testi-arrow next" aria-label="Selanjutnya"><i class="ti ti-chevron-right"></i></button>
        @endif
    </div>
    @else
    <div class="vacancy-empty" style="margin-top:28px">
        <i class="ti ti-message-off"></i>
        <p>Belum ada testimoni. Jadilah yang pertama memberikan testimoni setelah menyelesaikan magang!</p>
    </div>
    @endif
</div>

{{-- ══════════════════════════════════════════════════════════════
     WHAT WE OFFER — Slider Section (ala Magenta)
     ══════════════════════════════════════════════════════════════ --}}
<div class="welcome-offer" id="section-offer" data-reveal
     x-data="offerSlider()"
     x-init="init()"
     @mouseenter="pause = true"
     @mouseleave="pause = false">

    <div class="offer-slider">
        <div class="offer-slide-inner">
            <div class="offer-slide-image">
                <div class="card-benefit">
                    <picture>
                        <source :srcset="slides[current].imageWebp" type="image/webp">
                        <img :src="slides[current].image" :alt="slides[current].label" class="offer-slide-img" loading="lazy">
                    </picture>
                </div>
            </div>
            <div class="offer-slide-content">
                <h2 class="welcome-section-title">Apa yang <span style="color:#C0392B">Telkom</span> Tawarkan untuk Kamu?</h2>
                <p class="welcome-section-sub">Alat untuk Mendapatkan Pengalaman Magang Impian Kamu. Temukan peluang magangmu dengan mudah. Buka pintu kariermu sekarang!</p>
                <h3 class="offer-slide-title" x-text="slides[current].label"></h3>
                <hr>
                <p class="offer-slide-desc" x-text="slides[current].desc"></p>

                <div class="offer-pagination-bottom">
                    <div class="offer-dots">
                        <template x-for="(s, i) in slides" :key="i">
                            <span class="offer-dot" :class="{ active: current === i }" @click="goTo(i)"></span>
                        </template>
                    </div>
                    <div class="offer-actions">
                        <button class="offer-btn offer-btn-prev" @click="prev()" aria-label="Sebelumnya">
                            <i class="ti ti-chevron-left"></i>
                        </button>
                        <button class="offer-btn offer-btn-next" @click="next()" aria-label="Selanjutnya">
                            <i class="ti ti-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script nonce="{{ $cspNonce }}">
    function offerSlider() {
        return {
            current: 0,
            pause: false,
            timer: null,
            slides: [
                {
                    label: '01. Pendaftaran Online',
                    desc: 'Daftar magang secara digital, pantau status lamaran secara real-time tanpa perlu datang ke kantor',
                    image: '{{ asset("images/pendaftaran-online.jpg") }}',
                    imageWebp: '{{ asset("images/pendaftaran-online.webp") }}'
                },
                {
                    label: '02. Logbook & Bimbingan',
                    desc: 'Catat kegiatan harian, kirim laporan, dan dapatkan bimbingan langsung dari pembimbing lapangan',
                    image: '{{ asset("images/telkom-meet-kantor.jpg") }}',
                    imageWebp: '{{ asset("images/telkom-meet-kantor.webp") }}'
                },
                {
                    label: '03. Sertifikat Digital',
                    desc: 'Dapatkan sertifikat resmi dengan QR code yang bisa diverifikasi secara publik kapan saja',
                    image: '{{ asset("images/sertifikat-digital.jpg") }}',
                    imageWebp: '{{ asset("images/sertifikat-digital.webp") }}'
                }
            ],
            init() {
                this.startTimer();
            },
            startTimer() {
                clearInterval(this.timer);
                this.timer = setInterval(() => {
                    if (!this.pause) this.next();
                }, 5000);
            },
            next() {
                this.current = (this.current + 1) % this.slides.length;
                this.startTimer();
            },
            prev() {
                this.current = (this.current - 1 + this.slides.length) % this.slides.length;
                this.startTimer();
            },
            goTo(i) {
                this.current = i;
                this.startTimer();
            }
        };
    }
</script>
@endpush

{{-- ══════════════════════════════════════════════════════════════
     HOW IT WORKS — 4 Step Flow
     ══════════════════════════════════════════════════════════════ --}}
<div class="welcome-howitworks" data-reveal>
    <div class="how-header">
        <h2 class="welcome-section-title">Bagaimana Cara Mendaftar?</h2>
        <p class="welcome-section-sub">Hanya 4 langkah mudah untuk memulai perjalanan magangmu</p>
    </div>
    <div class="how-grid">
        <div class="how-step">
            <div class="how-step-num">1</div>
            <i class="ti ti-user-plus how-step-icon"></i>
            <h3>Daftar Akun</h3>
            <p>Buat akun dan lengkapi profil diri kamu</p>
        </div>
        <div class="how-arrow"></div>
        <div class="how-step">
            <div class="how-step-num">2</div>
            <i class="ti ti-briefcase how-step-icon"></i>
            <h3>Pilih Lowongan</h3>
            <p>Temukan dan lamar posisi yang sesuai minatmu</p>
        </div>
        <div class="how-arrow"></div>
        <div class="how-step">
            <div class="how-step-num">3</div>
            <i class="ti ti-notebook how-step-icon"></i>
            <h3>Ikuti Magang</h3>
            <p>Catat kegiatan harian dan dapatkan bimbingan</p>
        </div>
        <div class="how-arrow"></div>
        <div class="how-step">
            <div class="how-step-num">4</div>
            <i class="ti ti-certificate how-step-icon"></i>
            <h3>Dapatkan Sertifikat</h3>
            <p>Selesaikan evaluasi dan dapatkan sertifikat digital</p>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     REGISTRATION TIMELINE
     ══════════════════════════════════════════════════════════════ --}}
<div class="welcome-timeline" data-reveal>
    <div class="timeline-header">
        <h2 class="welcome-section-title">Jadwal Pendaftaran</h2>
        <p class="welcome-section-sub">Pilih gelombang yang sesuai dengan jadwal akademikmu</p>
    </div>
    <div class="timeline-grid">
        <div class="timeline-card">
            <span class="timeline-badge open">Dibuka</span>
            <h3>Gelombang 1</h3>
            <span class="timeline-date">Januari — Maret 2026</span>
            <p>Pendaftaran awal untuk mahasiswa semester genap</p>
        </div>
        <div class="timeline-card">
            <span class="timeline-badge soon">Segera</span>
            <h3>Gelombang 2</h3>
            <span class="timeline-date">April — Juni 2026</span>
            <p>Pendaftaran lanjutan dengan kuota terbatas</p>
        </div>
        <div class="timeline-card">
            <span class="timeline-badge soon">Segera</span>
            <h3>Gelombang 3</h3>
            <span class="timeline-date">Juli — September 2026</span>
            <p>Pendaftaran akhir untuk liburan dan PKL semester</p>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     STATS SECTION — Animated Counter
     ══════════════════════════════════════════════════════════════ --}}
<div class="section-divider-wave"></div>
<div class="welcome-stats" data-reveal>
    <div class="welcome-stats-grid">
        @php
            $statValues = [
                'interns' => $stats['interns'],
                'supervisors' => $stats['supervisors'],
                'completed' => $stats['completed'],
            ];
        @endphp
        @foreach ([
            ['key' => 'interns', 'icon' => 'ti ti-users', 'label' => 'Peserta Magang'],
            ['key' => 'supervisors', 'icon' => 'ti ti-user-star', 'label' => 'Pembimbing'],
            ['key' => 'completed', 'icon' => 'ti ti-certificate', 'label' => 'Lulusan'],
        ] as $s)
        <div class="welcome-stat"
             x-data="{ num: 0, target: {{ $statValues[$s['key']] }}, counting: false }"
             x-init="
                 const obs = new IntersectionObserver(([entry]) => {
                     if (entry.isIntersecting && !counting) {
                         counting = true;
                         obs.unobserve($el);
                         const step = Math.ceil(target / 30);
                         let t = setInterval(() => {
                             num = Math.min(num + step, target);
                             if (num >= target) clearInterval(t);
                         }, 30);
                     }
                 }, { threshold: 0.3 });
                 obs.observe($el);
             ">
            <i class="{{ $s['icon'] }} welcome-stat-icon"></i>
            <span class="welcome-stat-num" x-text="num.toLocaleString() + '+'">0</span>
            <span class="welcome-stat-lbl">{{ $s['label'] }}</span>
        </div>
        @endforeach
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     TESTIMONIALS — moved to top section above
     ══════════════════════════════════════════════════════════════ --}}

{{-- ══════════════════════════════════════════════════════════════
     FAQ ACCORDION
     ══════════════════════════════════════════════════════════════ --}}
<div class="welcome-faq" id="section-faq" data-reveal>
    <div class="faq-header">
        <h2 class="welcome-section-title">Pertanyaan Umum</h2>
        <p class="welcome-section-sub">Hal-hal yang sering ditanyakan tentang program magang</p>
    </div>
    <div class="faq-list">
        @forelse($faqs as $faq)
        <div class="faq-item" x-data="{ open: false }">
            <button class="faq-question" @click="open = !open" :aria-expanded="open">
                <span>{{ $faq->question }}</span>
                <i class="ti ti-chevron-down faq-arrow" :class="{ 'rotated': open }"></i>
            </button>
            <div class="faq-answer" x-show="open" x-collapse>
                <p>{{ $faq->answer }}</p>
            </div>
        </div>
        @empty
        <div class="vacancy-empty">
            <i class="ti ti-message-off"></i>
            <p>Belum ada pertanyaan umum.</p>
        </div>
        @endforelse
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     TRUST SIGNALS — Partner Institutions
     ══════════════════════════════════════════════════════════════ --}}
<x-partner-marquee/>

{{-- ══════════════════════════════════════════════════════════════
     SOCIAL MEDIA / GALERI
     ══════════════════════════════════════════════════════════════ --}}
<div class="welcome-galeri" data-reveal>
    <div class="galeri-header">
        <h2 class="welcome-section-title">Ikuti Perjalanan Kami</h2>
        <p class="welcome-section-sub">Lihat kegiatan magang terbaru di Instagram <a href="https://www.instagram.com/telkomsukabumi" target="_blank" rel="noopener noreferrer" class="galeri-link">@telkomsukabumi</a></p>
    </div>
    <div class="galeri-grid">
        <div class="galeri-card">
            <div class="galeri-placeholder">
                <i class="ti ti-photo"></i>
            </div>
        </div>
        <div class="galeri-card">
            <div class="galeri-placeholder">
                <i class="ti ti-photo"></i>
            </div>
        </div>
        <div class="galeri-card">
            <div class="galeri-placeholder">
                <i class="ti ti-photo"></i>
            </div>
        </div>
        <div class="galeri-card">
            <div class="galeri-placeholder">
                <i class="ti ti-photo"></i>
            </div>
        </div>
    </div>
    <div class="galeri-cta">
        <a href="https://www.instagram.com/telkomsukabumi" target="_blank" rel="noopener noreferrer" class="btn-primary">
            <i class="ti ti-brand-instagram"></i> Ikuti Instagram
        </a>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     VACANCIES SECTION — With Featured Badges
     ══════════════════════════════════════════════════════════════ --}}
@if(isset($vacancies) && $vacancies->isNotEmpty())
<div class="welcome-vacancies" id="section-vacancies" data-reveal>
    <div class="welcome-vacancies-header">
        <h2 class="welcome-section-title">Lowongan Tersedia</h2>
        <p class="welcome-section-sub">Temukan posisi magang yang sesuai dengan minatmu</p>
    </div>

    {{-- Search Filter Bar (seperti magentaku.id) --}}
    <div class="search-filter-bar" style="margin-bottom:24px">
        <i class="ti ti-search search-filter-icon"></i>
        <input type="text" class="search-filter-input" placeholder="Cari lowongan..." aria-label="Cari lowongan">
        <select class="search-filter-select" aria-label="Filter divisi">
            <option value="">Semua Divisi</option>
            @if(isset($vacancies))
                @foreach($vacancies->pluck('division')->unique()->filter() as $div)
                <option value="{{ $div }}">{{ $div }}</option>
                @endforeach
            @endif
        </select>
        <button class="search-filter-btn"><i class="ti ti-search"></i> Cari</button>
    </div>

    <div class="vacancy-grid" data-reveal-stagger>
        @foreach($vacancies as $vacancy)
        @php
            $isNew = $vacancy->created_at->diffInDays(now()) <= 7;
            $isUrgent = $vacancy->application_deadline && $vacancy->application_deadline->isFuture() && $vacancy->application_deadline->diffInDays(now()) <= 7;
        @endphp
        <div class="panel vacancy-card">
            <div class="vacancy-card-head">
                <div>
                    <h3 class="vacancy-card-title">{{ $vacancy->title }}</h3>
                    @if($vacancy->division)
                    <span class="badge badge-neutral vacancy-card-badge" style="margin-top:4px;display:inline-block">{{ $vacancy->division }}</span>
                    @endif
                </div>
                <div style="display:flex;gap:4px;flex-wrap:wrap;flex-shrink:0">
                    @if($isNew)
                    <span class="vacancy-featured-badge new"><i class="ti ti-sparkles"></i> Baru</span>
                    @endif
                    @if($isUrgent)
                    <span class="vacancy-featured-badge urgent"><i class="ti ti-alert-triangle"></i> Deadline</span>
                    @endif
                </div>
            </div>
            <p class="vacancy-card-desc">{{ Str::limit($vacancy->description, 120) }}</p>
            <div class="vacancy-card-footer">
                <span class="vacancy-card-deadline"
                      x-data="{ left: {{ $vacancy->application_deadline ? $vacancy->application_deadline->isPast() ? 0 : $vacancy->application_deadline->diffInDays(now()) : 0 }} }"
                      x-text="left > 0 ? left + ' hari lagi' : 'Berakhir hari ini'">
                    @if($vacancy->application_deadline)
                    Deadline {{ $vacancy->application_deadline->format('d M Y') }}
                    @endif
                </span>
                <a href="{{ route('public.vacancies.show', $vacancy) }}" class="btn-primary vacancy-card-btn">Lihat</a>
            </div>
        </div>
        @endforeach
    </div>
    <div class="welcome-vacancies-link">
        <a href="{{ route('public.vacancies') }}">
            Lihat Semua Lowongan <i class="ti ti-arrow-right"></i>
        </a>
    </div>
</div>
@else
<div class="welcome-vacancies" data-reveal>
    <div class="welcome-vacancies-header">
        <h2 class="welcome-section-title">Lowongan Tersedia</h2>
        <p class="welcome-section-sub">Temukan posisi magang yang sesuai dengan minatmu</p>
    </div>
    <div class="vacancy-empty">
        <i class="ti ti-briefcase-off"></i>
        <p>Belum ada lowongan saat ini. Pantau terus halaman ini untuk update terbaru.</p>
    </div>
</div>
@endif

{{-- ══════════════════════════════════════════════════════════════
     CTA SECTION
     ══════════════════════════════════════════════════════════════ --}}
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

{{-- ══════════════════════════════════════════════════════════════
     BACK TO TOP
     ══════════════════════════════════════════════════════════════ --}}
<button x-data="{ visible: false }"
        x-init="window.addEventListener('scroll', () => { visible = window.scrollY > 500 }, { passive: true })"
        x-show="visible"
        @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
        x-transition:enter="anim-fade-in"
        x-transition:leave="anim-fade-in"
        x-cloak
        class="back-to-top"
        aria-label="Kembali ke atas">
    <i class="ti ti-arrow-up"></i>
</button>

@endsection
