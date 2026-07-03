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
