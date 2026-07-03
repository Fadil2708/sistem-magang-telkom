export default (Alpine) => {
    Alpine.data('publicNav', () => ({
        navOpen: false,
        toggle() {
            this.navOpen = !this.navOpen;
        },
        close() {
            this.navOpen = false;
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
