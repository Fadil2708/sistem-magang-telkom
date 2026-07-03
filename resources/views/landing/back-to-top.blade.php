<button x-data="backToTop"
        x-show="visible"
        @click="scrollTop"
        x-transition:enter="anim-fade-in"
        x-transition:leave="anim-fade-in"
        x-cloak
        class="back-to-top"
        aria-label="Kembali ke atas">
    <i class="ti ti-arrow-up"></i>
</button>
