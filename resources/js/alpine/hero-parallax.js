export default (Alpine) => {
    Alpine.data('heroParallax', () => ({
        x: 0,
        y: 0,
        move(event) {
            this.x = (event.offsetX / this.$el.offsetWidth - 0.5) * 16;
            this.y = (event.offsetY / this.$el.offsetHeight - 0.5) * 16;
        },
        reset() {
            this.x = 0;
            this.y = 0;
        },
        get style() {
            return { transform: `translate(${this.x}px, ${this.y}px)` };
        }
    }));
};
