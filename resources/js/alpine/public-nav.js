export default (Alpine) => {
    Alpine.data('publicNav', () => ({
        navOpen: false,
        toggle() {
            this.navOpen = !this.navOpen;
            document.body.style.overflow = this.navOpen ? 'hidden' : '';
        },
        close() {
            this.navOpen = false;
            document.body.style.overflow = '';
        },
        navigate(event, sectionId) {
            if (window.location.pathname === '/') {
                event.preventDefault();
                document.getElementById(sectionId)?.scrollIntoView({ behavior: 'smooth' });
                this.navOpen = false;
            }
        }
    }));
};
