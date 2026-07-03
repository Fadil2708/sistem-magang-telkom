export default (Alpine) => {
    Alpine.data('confirmModal', () => ({
        open: false,
        message: '',
        callback: null,
        show(msg, cb) {
            this.message = msg;
            this.callback = cb;
            this.open = true;
        },
        confirm() {
            if (this.callback) this.callback();
            this.open = false;
            this.callback = null;
        }
    }));
};
