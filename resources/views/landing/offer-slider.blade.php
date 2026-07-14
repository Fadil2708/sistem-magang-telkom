@php
    $offerSlides = [
        ['label' => '01. Pendaftaran Online', 'desc' => 'Daftar magang secara digital, pantau status lamaran secara real-time tanpa perlu datang ke kantor', 'image' => asset('images/pendaftaran-online.jpg'), 'imageWebp' => asset('images/pendaftaran-online.webp')],
        ['label' => '02. Logbook & Bimbingan', 'desc' => 'Catat kegiatan harian, kirim laporan, dan dapatkan bimbingan langsung dari pembimbing lapangan', 'image' => asset('images/telkom-meet-kantor.jpg'), 'imageWebp' => asset('images/telkom-meet-kantor.webp')],
        ['label' => '03. Sertifikat Digital', 'desc' => 'Dapatkan sertifikat resmi dengan QR code yang bisa diverifikasi secara publik kapan saja', 'image' => asset('images/sertifikat-digital.jpg'), 'imageWebp' => asset('images/sertifikat-digital.webp')],
    ];
@endphp
<div class="welcome-offer" id="section-offer" data-reveal
     x-data='offerSlider(@json($offerSlides, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP))'
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
                <h2 class="welcome-section-title">Apa yang <span style="color:#2563EB">Telkom</span> Tawarkan untuk Kamu?</h2>
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


