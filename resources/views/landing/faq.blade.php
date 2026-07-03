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
