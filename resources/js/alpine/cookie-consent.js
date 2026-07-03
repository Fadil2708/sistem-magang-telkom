export default (Alpine) => {
    Alpine.data('cookieConsent', () => ({
        show: true,
        init() {
            this.show = !localStorage.getItem('cookie-consent');
        },
        accept() {
            this.show = false;
            localStorage.setItem('cookie-consent', '1');
        }
    }));
};
