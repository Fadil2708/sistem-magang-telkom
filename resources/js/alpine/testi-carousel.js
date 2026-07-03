export default (Alpine) => {
    Alpine.data('testiCarousel', () => ({
        current: 0,
        autoPlay: null,
        init() {
            this.startAutoPlay();
            this.$el.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') this.prev();
                if (e.key === 'ArrowRight') this.next();
            });
            this.$el.setAttribute('tabindex', '0');
            this.$el.setAttribute('role', 'region');
            this.$el.setAttribute('aria-label', 'Testimonial carousel');
        },
        startAutoPlay() {
            this.autoPlay = setInterval(() => {
                this.current = (this.current + 1) % this.$el.querySelectorAll('.testi-slide').length;
            }, 5000);
        },
        stopAutoPlay() {
            if (this.autoPlay) clearInterval(this.autoPlay);
            this.autoPlay = null;
        },
        goTo(i) {
            this.stopAutoPlay();
            this.current = i;
            this.startAutoPlay();
        },
        prev() {
            const total = this.$el.querySelectorAll('.testi-slide').length;
            this.goTo(this.current === 0 ? total - 1 : this.current - 1);
        },
        next() {
            const total = this.$el.querySelectorAll('.testi-slide').length;
            this.goTo((this.current + 1) % total);
        },
        get trackStyle() {
            return { transform: `translateX(-${this.current * 100}%)` };
        }
    }));
};
