export default (Alpine) => {
    Alpine.data('scrollProgress', () => ({
        scroll: 0,
        init() {
            window.addEventListener('scroll', () => {
                this.scroll = Math.min(
                    (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100, 100
                );
            }, { passive: true });
        },
        get style() {
            return `width: ${this.scroll}%`;
        }
    }));
};
