export default (Alpine) => {
    Alpine.data('offerSlider', (slides = []) => ({
        current: 0,
        pause: false,
        timer: null,
        slides: slides,
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
    }));
};
