export default (Alpine) => {
    Alpine.data('backToTop', () => ({
        visible: false,
        init() {
            window.addEventListener('scroll', () => {
                this.visible = window.scrollY > 500;
            }, { passive: true });
        },
        scrollTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }));
};
