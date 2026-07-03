export default (Alpine) => {
    Alpine.data('alertBar', () => ({
        show: true,
        init() {
            this.show = !localStorage.getItem('alert-dismissed');
        },
        dismiss() {
            this.show = false;
            localStorage.setItem('alert-dismissed', '1');
        }
    }));
};
