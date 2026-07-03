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
            <div class="testi-track" :style="trackStyle">
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
